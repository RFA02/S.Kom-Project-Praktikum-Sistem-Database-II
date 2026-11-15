# AGENTS.md - What's Left (Frontend & Laravel Context)

<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

---

## 1. Project Overview
**What's Left** is a web platform designed to reduce food waste (aligned with UN SDGs) by connecting food businesses (restaurants, cafes, bakeries, UMKM, catering) with consumers.
- **Core Purpose 1 (Food Rescue):** Businesses list surplus, edible food at discounted prices before closing hours. Consumers browse, reserve, and pick up items within specified time slots.
- **Core Purpose 2 (Food Waste Prevention):** Businesses track daily production and sales records to analyze sales patterns and receive production quantity recommendations to prevent future surplus.
- **Flow:** Production → Sales → Surplus Management → Data Analytics.

## 2. User Roles & Scope
The frontend application serves two distinct user roles with separate layouts and navigation flows:
1. **Consumer (Public & Authenticated):**
   - **Landing Page (`/`):** Hero search (food/business/location), value propositions, popular surplus food carousel, "How it Works" 4-step guide.
   - **Explore Foods (`/explore`):** Search bar, sidebar filters (Category, Location/Radius slider up to 20km, Price range, Business rating), category quick-tabs (`Semua`, `Makanan Berat`, `Roti & Kue`, `Minuman`, `Snack`, `Dessert`), grid/list view toggle, map view toggle, pagination.
   - **Food Details (Modal/Page):** Multi-photo gallery, freshness indicator ("Dibuat hari ini pukul..."), original vs. discounted price + savings badge, countdown timer for reservation slot, stock counter, quantity selector, pickup window & address, "Reserve Sekarang" CTA.
   - **Reservation Flow (Multi-step Modal/Page):** Step 1: Order Details (quantity, pickup time slot selector) → Step 2: Location & Time → Step 3: Payment Method (GoPay, OVO, DANA, Virtual Account) → Step 4: Confirmation. Includes order summary, service fee calculation, and food-saving impact message.
   - **My Reservations (`/reservations`):** Tabbed status filter (`Semua`, `Akan Diambil`, `Sudah Diambil`, `Selesai`, `Dibatalkan`), active reservation cards with booking codes (e.g., `WL24520X`), pickup instructions sidebar, personal food-waste impact stats (portions saved, grams prevented).
   - **Notifications (`/notifications`):** Categorized notification feed (`Semua`, `Reservasi`, `Pengambilan`, `Promo`, `Sistem`), "Mark all as read", push notification prompt.
2. **Business (Merchant Portal):**
   - **Dashboard:** Overview of daily surplus, active reservations, revenue from rescued food, and quick actions.
   - **Food Management:** Master catalog CRUD for menu items.
   - **Production Records:** Daily input of produced quantities vs. sold quantities (feeds into analytics).
   - **Surplus Listings:** Publishing surplus stock, setting discount prices, defining pickup time slots, and managing listing expiration.
   - **Reservations Management:** Verifying customer pickup codes, updating order statuses (`Akan Diambil` → `Selesai` / `Dibatalkan`).
   - **Analytics:** Sales trends, waste reduction metrics, and data-driven production recommendations.

## 3. UI/UX & Design System Guidelines
Based on the established mockups, adhere strictly to the following visual language:
- **Brand Identity:** Friendly, eco-conscious, fresh, and trustworthy.
- **Color Palette:**
  - **Primary Brand Green:** Deep forest/leaf green (`#1B5E3A` / `#2E7D32`) for primary buttons, active tabs, and brand highlights.
  - **Secondary Accent Orange/Amber:** Warm orange (`#F59E0B` / `#E67E22`) for discount badges (`30% OFF`), highlights ("Second Chance", "Populer"), and warning statuses (`Akan Diambil`).
  - **Backgrounds:** Warm cream/off-white (`#FDFBF7` / `#FAF6F0`) for hero sections and callout banners; pure white (`#FFFFFF`) for cards and modals; soft light gray (`#F4F5F7`) for page canvas.
  - **Text:** Charcoal/Dark Slate (`#1F2937`) for headings; Muted Gray (`#6B7280`) for secondary metadata; Strikethrough gray for original prices; Bold Red/Orange (`#D9534F` / `#E67E22`) for discounted prices.
- **Typography & Localization:**
  - **Language:** All UI copy, labels, statuses, and formatting MUST be in **Bahasa Indonesia** (e.g., `Rp 10.500`, `Ambil hari ini, 18.00 - 20.00`, `Hemat Rp 4.500`).
  - **Currency Format:** Indonesian Rupiah (`Rp X.XXX`) with dot separators, no decimals.
- **Component Patterns:**
  - **Food Cards:** Top-left discount badge (`XX% OFF`), top-right wishlist heart icon, 4:3 food image, verified merchant name with checkmark badge, bold item title, discounted price + strikethrough original price, pickup window with clock icon, distance with pin icon.
  - **Modals:** Rounded corners (`16px`–`24px`), soft backdrop blur, clear close (`X`) button, two-column layout on desktop for detail & checkout views.

## 4. Frontend Architecture & Coding Conventions
- **Component Structure:**
  - Separate components into `components/common/` (buttons, badges, modals, inputs), `components/consumer/` (FoodCard, FilterSidebar, ReservationStepper), and `components/business/` (ProductionForm, SurplusTable, AnalyticsChart).
- **State & Data Handling:**
  - Ensure all reservation flows handle stock concurrency gracefully (disable "Reserve Sekarang" if `stock === 0` or countdown reaches `00:00:00`).
  - Format dates, countdown timers, and currency via centralized utility functions (`utils/formatCurrency.ts`, `utils/dateTime.ts`).
- **Database Alignment Awareness:**
  - This is a Database Systems II (*Praktikum Sistem Database II*) project. Keep frontend data models/TypeScript interfaces strictly aligned with backend API payloads and relational entities (Users, Businesses, Foods, ProductionRecords, SurplusListings, Reservations, Payments, Notifications).
  - Do not hardcode mock data inside UI components; isolate mock data in a dedicated `mocks/` or `services/` layer so swapping to real REST endpoints or Laravel Blade/Inertia controllers is seamless.

## 5. Instructions for AI Agents
- When generating new pages or components, match the **Consumer** and **Business** mockup layouts, spacing, and Indonesian microcopy.
- Always implement responsive designs (mobile-first grid adjustments for food cards and collapsible filter drawers on mobile).
- Prioritize accessibility: include proper `aria-labels` for icon-only buttons (wishlist, notifications, modal close) and semantic HTML forms for reservation and production inputs.
 