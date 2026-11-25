@extends('layouts.business')

@section('title', 'Manajemen Reservasi - Sunny Bakery')

@section('content')
<div class="flex h-screen overflow-hidden bg-[#FAF7F2]"
     x-data="{
         // Sidebar & Dropdown States
         mobileSidebarOpen: false,
         sidebarCollapsed: false,
         profileDropdownOpen: false,
         notificationDropdownOpen: false,
         dateRangeDropdownOpen: false,
         sortDropdownOpen: false,
         summaryPeriodDropdownOpen: false,
         openActionRowId: null,

         // Active Filters & View States
         activeStatusTab: 'Semua',
         searchQuery: '',
         selectedDateRange: '2 - 8 Mei 2025',
         sortBy: 'Terbaru',
         summaryPeriod: 'Minggu ini',

         // Right Panel Filter Drafts (applied via Terapkan Filter)
         filterDraft: {
             status: 'Semua Status',
             paymentMethod: 'Semua Metode',
             dateRange: '2 Mei 2025 - 8 Mei 2025'
         },
         appliedFilter: {
             status: 'Semua Status',
             paymentMethod: 'Semua Metode',
             dateRange: '2 Mei 2025 - 8 Mei 2025'
         },

         // Pagination State
         currentPage: 1,
         perPage: 8,

         // Calendar State (Mei 2025 default, active day = 2)
         calendarMonthIndex: 4, // 0-indexed: 4 = Mei
         calendarYear: 2025,
         selectedCalendarDay: 2,
         filterByCalendarDay: false,
         monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],

         // Modal States
         detailModalItem: null,
         isManualModalOpen: false,
         manualForm: {
             customerName: '',
             customerPhone: '',
             pickupDate: '2 Mei 2025',
             pickupSlot: '16.00 - 20.00',
             foodName: 'Roti Cokelat Surplus',
             itemCount: 2,
             totalPrice: 30000,
             paymentMethod: 'QRIS',
             paymentStatus: 'Lunas',
             status: 'Dikonfirmasi',
             notes: ''
         },
         manualFormError: '',

         // Dummy Data: 18 Reservasi Makanan Surplus (Halaman 1 persis mengikuti referensi visual)
         reservations: [
             {
                 id: '#RES-250502-001',
                 pickupCode: 'WL-50201A',
                 createdAt: '2 Mei 2025, 08.30',
                 sortTs: 202505020830,
                 customerName: 'Dinda Ayu',
                 customerPhone: '0812 3456 7890',
                 avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '2 Mei 2025',
                 pickupDay: 2,
                 pickupMonth: 4,
                 pickupSlot: '16.00 - 20.00',
                 status: 'Menunggu',
                 statusTimestamp: 'Dibuat 08.30',
                 total: 75000,
                 itemCount: 5,
                 paymentMethod: 'Transfer Bank',
                 paymentStatus: 'Menunggu',
                 items: [
                     { name: 'Roti Cokelat Belgia', qty: 3, price: 15000 },
                     { name: 'Croissant Butter', qty: 2, price: 15000 }
                 ],
                 notes: 'Mohon dipisah kantong kertasnya ya kak.'
             },
             {
                 id: '#RES-250501-015',
                 pickupCode: 'WL-50115B',
                 createdAt: '1 Mei 2025, 17.45',
                 sortTs: 202505011745,
                 customerName: 'Budi Santoso',
                 customerPhone: '0813 9876 5432',
                 avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '1 Mei 2025',
                 pickupDay: 1,
                 pickupMonth: 4,
                 pickupSlot: '18.00 - 20.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 17.50',
                 total: 60000,
                 itemCount: 4,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 items: [
                     { name: 'Chicken Sandwich Segar', qty: 2, price: 15000 },
                     { name: 'Donut Mix Box', qty: 2, price: 15000 }
                 ],
                 notes: 'Diambil sepulang kerja sekitar jam 18.30.'
             },
             {
                 id: '#RES-250501-014',
                 pickupCode: 'WL-50114C',
                 createdAt: '1 Mei 2025, 16.10',
                 sortTs: 202505011610,
                 customerName: 'Citra Lestari',
                 customerPhone: '0821 2345 6789',
                 avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '1 Mei 2025',
                 pickupDay: 1,
                 pickupMonth: 4,
                 pickupSlot: '17.00 - 19.00',
                 status: 'Siap Diambil',
                 statusTimestamp: 'Siap 16.55',
                 total: 45000,
                 itemCount: 3,
                 paymentMethod: 'E-Wallet',
                 paymentStatus: 'Lunas',
                 items: [
                     { name: 'Salad Sayur Organik', qty: 3, price: 15000 }
                 ],
                 notes: 'Tanpa dressing pedas.'
             },
             {
                 id: '#RES-250430-020',
                 pickupCode: 'WL-43020D',
                 createdAt: '30 Apr 2025, 19.20',
                 sortTs: 202504301920,
                 customerName: 'Fahri Ramadhan',
                 customerPhone: '0811 2233 4455',
                 avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '30 Apr 2025',
                 pickupDay: 30,
                 pickupMonth: 3,
                 pickupSlot: '19.00 - 21.00',
                 status: 'Selesai',
                 statusTimestamp: 'Selesai 19.45',
                 total: 90000,
                 itemCount: 6,
                 paymentMethod: 'Cash',
                 paymentStatus: 'Lunas',
                 items: [
                     { name: 'Paket Bakery Surprise', qty: 6, price: 15000 }
                 ],
                 notes: 'Sudah diambil langsung oleh pelanggan.'
             },
             {
                 id: '#RES-250430-019',
                 pickupCode: 'WL-43019E',
                 createdAt: '30 Apr 2025, 15.00',
                 sortTs: 202504301500,
                 customerName: 'Siti Nurhaliza',
                 customerPhone: '0896 1122 3344',
                 avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '30 Apr 2025',
                 pickupDay: 30,
                 pickupMonth: 3,
                 pickupSlot: '16.00 - 18.00',
                 status: 'Dibatalkan',
                 statusTimestamp: 'Dibatalkan 15.30',
                 total: 90000,
                 itemCount: 2,
                 paymentMethod: '—',
                 paymentStatus: 'Dibatalkan',
                 items: [
                     { name: 'Whole Wheat Sourdough', qty: 2, price: 45000 }
                 ],
                 notes: 'Dibatalkan pelanggan karena berhalangan hadir.'
             },
             {
                 id: '#RES-250429-012',
                 pickupCode: 'WL-42912F',
                 createdAt: '29 Apr 2025, 18.15',
                 sortTs: 202504291815,
                 customerName: 'Andi Wijaya',
                 customerPhone: '0812 8765 4321',
                 avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '29 Apr 2025',
                 pickupDay: 29,
                 pickupMonth: 3,
                 pickupSlot: '18.00 - 20.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 18.20',
                 total: 72000,
                 itemCount: 4,
                 paymentMethod: 'Transfer Bank',
                 paymentStatus: 'Lunas',
                 items: [
                     { name: 'Cinnamon Roll Glaze', qty: 4, price: 18000 }
                 ],
                 notes: '-'
             },
             {
                 id: '#RES-250429-011',
                 pickupCode: 'WL-42911G',
                 createdAt: '29 Apr 2025, 14.30',
                 sortTs: 202504291430,
                 customerName: 'Putri Amelia',
                 customerPhone: '0822 3344 5566',
                 avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '29 Apr 2025',
                 pickupDay: 29,
                 pickupMonth: 3,
                 pickupSlot: '17.00 - 19.00',
                 status: 'Menunggu',
                 statusTimestamp: 'Dibuat 14.30',
                 total: 55000,
                 itemCount: 3,
                 paymentMethod: 'Transfer Bank',
                 paymentStatus: 'Menunggu',
                 items: [
                     { name: 'Red Velvet Slice', qty: 2, price: 20000 },
                     { name: 'Es Kopi Susu Gula Aren', qty: 1, price: 15000 }
                 ],
                 notes: 'Menunggu konfirmasi bukti transfer.'
             },
             {
                 id: '#RES-250428-010',
                 pickupCode: 'WL-42810H',
                 createdAt: '28 Apr 2025, 20.05',
                 sortTs: 202504282005,
                 customerName: 'Rizky Pratama',
                 customerPhone: '0838 7788 9900',
                 avatar: 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '28 Apr 2025',
                 pickupDay: 28,
                 pickupMonth: 3,
                 pickupSlot: '19.00 - 21.00',
                 status: 'Selesai',
                 statusTimestamp: 'Selesai 19.30',
                 total: 85000,
                 itemCount: 5,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 items: [
                     { name: 'Croissant Almond', qty: 5, price: 17000 }
                 ],
                 notes: 'Terima kasih, rotinya masih sangat lembut!'
             },
             // Halaman 2 (Data Tambahan untuk Total 18 Reservasi)
             {
                 id: '#RES-250502-002',
                 pickupCode: 'WL-50202J',
                 createdAt: '2 Mei 2025, 09.15',
                 sortTs: 202505020915,
                 customerName: 'Nadia Kusuma',
                 customerPhone: '0812 9900 1122',
                 avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '2 Mei 2025',
                 pickupDay: 2,
                 pickupMonth: 4,
                 pickupSlot: '17.00 - 19.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 09.20',
                 total: 48000,
                 itemCount: 3,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Roti Keju Susu', qty: 3, price: 16000 }],
                 notes: 'Bawa totebag sendiri.'
             },
             {
                 id: '#RES-250502-003',
                 pickupCode: 'WL-50203K',
                 createdAt: '2 Mei 2025, 10.05',
                 sortTs: 202505021005,
                 customerName: 'Bagas Saputra',
                 customerPhone: '0856 4455 6677',
                 avatar: 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '2 Mei 2025',
                 pickupDay: 2,
                 pickupMonth: 4,
                 pickupSlot: '16.00 - 18.00',
                 status: 'Siap Diambil',
                 statusTimestamp: 'Siap 15.45',
                 total: 36000,
                 itemCount: 3,
                 paymentMethod: 'E-Wallet',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Donut Mix Box', qty: 3, price: 12000 }],
                 notes: '-'
             },
             {
                 id: '#RES-250503-004',
                 pickupCode: 'WL-50304L',
                 createdAt: '3 Mei 2025, 11.20',
                 sortTs: 202505031120,
                 customerName: 'Rina Marlina',
                 customerPhone: '0813 2211 3344',
                 avatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '6 Mei 2025',
                 pickupDay: 6,
                 pickupMonth: 4,
                 pickupSlot: '17.00 - 20.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 11.25',
                 total: 64000,
                 itemCount: 4,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Tiramisu Cup', qty: 4, price: 16000 }],
                 notes: '-'
             },
             {
                 id: '#RES-250503-005',
                 pickupCode: 'WL-50305M',
                 createdAt: '3 Mei 2025, 13.00',
                 sortTs: 202505031300,
                 customerName: 'Hendra Gunawan',
                 customerPhone: '0819 8877 6655',
                 avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '6 Mei 2025',
                 pickupDay: 6,
                 pickupMonth: 4,
                 pickupSlot: '18.00 - 20.00',
                 status: 'Menunggu',
                 statusTimestamp: 'Dibuat 13.00',
                 total: 40000,
                 itemCount: 2,
                 paymentMethod: 'Transfer Bank',
                 paymentStatus: 'Menunggu',
                 items: [{ name: 'Chicken Sandwich', qty: 2, price: 20000 }],
                 notes: '-'
             },
             {
                 id: '#RES-250504-006',
                 pickupCode: 'WL-50406N',
                 createdAt: '4 Mei 2025, 14.10',
                 sortTs: 202505041410,
                 customerName: 'Maya Kartika',
                 customerPhone: '0821 7766 5544',
                 avatar: 'https://images.unsplash.com/photo-1544725176-7c40e5a71c5e?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '7 Mei 2025',
                 pickupDay: 7,
                 pickupMonth: 4,
                 pickupSlot: '16.00 - 19.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 14.15',
                 total: 54000,
                 itemCount: 3,
                 paymentMethod: 'E-Wallet',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Matcha Latte & Croissant', qty: 3, price: 18000 }],
                 notes: '-'
             },
             {
                 id: '#RES-250505-007',
                 pickupCode: 'WL-50507P',
                 createdAt: '5 Mei 2025, 15.30',
                 sortTs: 202505051530,
                 customerName: 'Dimas Anggara',
                 customerPhone: '0812 3344 9988',
                 avatar: 'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '8 Mei 2025',
                 pickupDay: 8,
                 pickupMonth: 4,
                 pickupSlot: '18.00 - 20.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 15.35',
                 total: 50000,
                 itemCount: 5,
                 paymentMethod: 'Cash',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Roti Cokelat', qty: 5, price: 10000 }],
                 notes: '-'
             },
             {
                 id: '#RES-250505-008',
                 pickupCode: 'WL-50508Q',
                 createdAt: '5 Mei 2025, 16.00',
                 sortTs: 202505051600,
                 customerName: 'Aulia Rahma',
                 customerPhone: '0857 1122 8899',
                 avatar: 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '8 Mei 2025',
                 pickupDay: 8,
                 pickupMonth: 4,
                 pickupSlot: '17.00 - 19.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 16.05',
                 total: 66000,
                 itemCount: 3,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Sourdough Slice Pack', qty: 3, price: 22000 }],
                 notes: '-'
             },
             {
                 id: '#RES-250506-009',
                 pickupCode: 'WL-50609R',
                 createdAt: '6 Mei 2025, 09.40',
                 sortTs: 202505060940,
                 customerName: 'Yoga Pratama',
                 customerPhone: '0818 5544 3322',
                 avatar: 'https://images.unsplash.com/photo-1504257432389-52343af06ae3?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '9 Mei 2025',
                 pickupDay: 9,
                 pickupMonth: 4,
                 pickupSlot: '16.00 - 18.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 09.45',
                 total: 42000,
                 itemCount: 3,
                 paymentMethod: 'E-Wallet',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Banana Bread Loaf', qty: 3, price: 14000 }],
                 notes: '-'
             },
             // Halaman 3
             {
                 id: '#RES-250507-010',
                 pickupCode: 'WL-50710S',
                 createdAt: '7 Mei 2025, 10.15',
                 sortTs: 202505071015,
                 customerName: 'Larasati Dewi',
                 customerPhone: '0812 6677 8899',
                 avatar: 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '9 Mei 2025',
                 pickupDay: 9,
                 pickupMonth: 4,
                 pickupSlot: '18.00 - 20.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 10.20',
                 total: 78000,
                 itemCount: 4,
                 paymentMethod: 'Transfer Bank',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Pastry Assorted Box', qty: 4, price: 19500 }],
                 notes: '-'
             },
             {
                 id: '#RES-250508-011',
                 pickupCode: 'WL-50811T',
                 createdAt: '8 Mei 2025, 11.50',
                 sortTs: 202505081150,
                 customerName: 'Farhan Hakim',
                 customerPhone: '0815 9988 7766',
                 avatar: 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=crop&w=120&q=80',
                 pickupDate: '9 Mei 2025',
                 pickupDay: 9,
                 pickupMonth: 4,
                 pickupSlot: '19.00 - 21.00',
                 status: 'Dikonfirmasi',
                 statusTimestamp: 'Dikonfirmasi 11.55',
                 total: 52000,
                 itemCount: 4,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 items: [{ name: 'Garlic Bread Baguette', qty: 4, price: 13000 }],
                 notes: '-'
             }
         ],

         // Summary Period Presets (Minggu ini matches exact numbers from mockup)
         summaryPresets: {
             'Hari ini': { total: 5, confirmed: 2, ready: 1, done: 1, pending: 1, cancelled: 0 },
             'Minggu ini': { total: 18, confirmed: 11, ready: 2, done: 2, pending: 3, cancelled: 0 },
             'Bulan ini': { total: 74, confirmed: 42, ready: 5, done: 19, pending: 6, cancelled: 2 }
         },

         // Dynamic delta when user creates or updates reservations
         extraAddedCount: 0,

         get currentSummary() {
             const base = this.summaryPresets[this.summaryPeriod] || this.summaryPresets['Minggu ini'];
             if (this.summaryPeriod !== 'Minggu ini') return base;
             // Reflect live additions gracefully while keeping default mockup numbers intact
             return {
                 total: base.total + this.extraAddedCount,
                 confirmed: base.confirmed + this.extraAddedCount,
                 ready: base.ready,
                 done: base.done,
                 pending: base.pending,
                 cancelled: base.cancelled
             };
         },

         // Tab Counts (Matches mockup defaults: 18, 3, 11, 2, 2, 0; reflects live changes)
         get tabCounts() {
             const total = this.reservations.length;
             const pending = this.reservations.filter(r => r.status === 'Menunggu').length;
             const confirmed = this.reservations.filter(r => r.status === 'Dikonfirmasi').length;
             const ready = this.reservations.filter(r => r.status === 'Siap Diambil').length;
             const done = this.reservations.filter(r => r.status === 'Selesai').length;
             const cancelled = this.reservations.filter(r => r.status === 'Dibatalkan').length;
             return {
                 'Semua': total,
                 'Menunggu': pending,
                 'Dikonfirmasi': confirmed,
                 'Siap Diambil': ready,
                 'Selesai': done,
                 'Dibatalkan': cancelled
             };
         },

         // Filtered & Sorted Reservations
         get filteredReservations() {
             let list = this.reservations.filter(item => {
                 // Tab status filter
                 if (this.activeStatusTab !== 'Semua' && item.status !== this.activeStatusTab) {
                     return false;
                 }
                 // Right panel applied status filter
                 if (this.appliedFilter.status !== 'Semua Status' && item.status !== this.appliedFilter.status) {
                     return false;
                 }
                 // Right panel applied payment method filter
                 if (this.appliedFilter.paymentMethod !== 'Semua Metode' && item.paymentMethod !== this.appliedFilter.paymentMethod) {
                     return false;
                 }
                 // Calendar day filter (only active when user explicitly clicks a calendar day)
                 if (this.filterByCalendarDay && this.selectedCalendarDay !== null) {
                     if (item.pickupDay !== this.selectedCalendarDay || item.pickupMonth !== this.calendarMonthIndex) {
                         return false;
                     }
                 }
                 // Search query
                 if (this.searchQuery.trim() !== '') {
                     const q = this.searchQuery.toLowerCase().trim();
                     const matchId = item.id.toLowerCase().includes(q);
                     const matchName = item.customerName.toLowerCase().includes(q);
                     const matchPhone = item.customerPhone.toLowerCase().includes(q);
                     if (!matchId && !matchName && !matchPhone) return false;
                 }
                 return true;
             });

             // Keep default mockup order for Page 1 when default 'Terbaru' is active and no custom sort
             if (this.sortBy === 'Terlama') {
                 list = [...list].sort((a, b) => a.sortTs - b.sortTs);
             } else if (this.sortBy === 'Total Tertinggi') {
                 list = [...list].sort((a, b) => b.total - a.total);
             } else if (this.sortBy === 'Total Terendah') {
                 list = [...list].sort((a, b) => a.total - b.total);
             } else if (this.sortBy === 'Nama A-Z') {
                 list = [...list].sort((a, b) => a.customerName.localeCompare(b.customerName));
             }

             return list;
         },

         get totalPages() {
             return Math.max(1, Math.ceil(this.filteredReservations.length / this.perPage));
         },

         get paginatedReservations() {
             const safePage = Math.min(this.currentPage, this.totalPages);
             const start = (safePage - 1) * this.perPage;
             return this.filteredReservations.slice(start, start + this.perPage);
         },

         // Calendar Grid Builder (Monday-first, matching Sen Sel Rab Kam Jum Sab Min in mockup)
         get calendarDays() {
             const year = this.calendarYear;
             const month = this.calendarMonthIndex;
             const firstDayOfMonth = new Date(year, month, 1);
             const lastDayOfMonth = new Date(year, month + 1, 0);
             const prevMonthLastDay = new Date(year, month, 0).getDate();

             // Convert JS getDay() (0=Sun) to Monday-first index (0=Mon .. 6=Sun)
             const startOffset = (firstDayOfMonth.getDay() + 6) % 7;
             const days = [];

             // Previous month trailing days (e.g. 28, 29, 30 for Mei 2025)
             for (let i = startOffset - 1; i >= 0; i--) {
                 const d = prevMonthLastDay - i;
                 days.push({ day: d, isCurrentMonth: false, dots: d === 29 ? ['#16A34A'] : (d === 30 ? ['#16A34A'] : []) });
             }

             // Current month days
             const dotMapMei = {
                 1: ['#16A34A'],
                 2: ['#16A34A', '#F59E0B'],
                 6: ['#3B82F6'],
                 7: ['#F59E0B'],
                 8: ['#16A34A', '#F59E0B'],
                 9: ['#16A34A']
             };

             for (let d = 1; d <= lastDayOfMonth.getDate(); d++) {
                 const dots = (month === 4 && year === 2025) ? (dotMapMei[d] || []) : [];
                 days.push({ day: d, isCurrentMonth: true, dots: dots });
             }

             // Fill up to complete week rows (keep compact 21 or 35 cells)
             while (days.length % 7 !== 0) {
                 days.push({ day: days.length - lastDayOfMonth.getDate() - startOffset + 1, isCurrentMonth: false, dots: [] });
             }

             return days;
         },

         // Helpers
         formatRupiah(amount) {
             return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
         },

         statusBadgeClass(status) {
             switch (status) {
                 case 'Menunggu': return 'bg-[#FEF3C7] text-[#B45309] border border-[#FDE68A]';
                 case 'Dikonfirmasi': return 'bg-[#DCF2E4] text-[#1B5E3A] border border-[#BBF7D0]';
                 case 'Siap Diambil': return 'bg-[#E0F2FE] text-[#0369A1] border border-[#BAE6FD]';
                 case 'Selesai': return 'bg-[#F3F4F6] text-[#374151] border border-[#E5E7EB]';
                 case 'Dibatalkan': return 'bg-[#FEE2E2] text-[#DC2626] border border-[#FECACA]';
                 default: return 'bg-stone-100 text-stone-700';
             }
         },

         paymentDotClass(status, method) {
             if (method === '—' || status === 'Dibatalkan') return '';
             if (status === 'Menunggu') return 'bg-[#F59E0B]';
             return 'bg-[#16A34A]';
         },

         // Actions
         setTab(tab) {
             this.activeStatusTab = tab;
             this.currentPage = 1;
         },

         applyRightFilter() {
             this.appliedFilter = { ...this.filterDraft };
             if (this.filterDraft.status !== 'Semua Status') {
                 this.activeStatusTab = this.filterDraft.status;
             } else {
                 this.activeStatusTab = 'Semua';
             }
             this.currentPage = 1;
             this.$dispatch('show-toast', { msg: 'Filter reservasi berhasil diterapkan.' });
         },

         resetFilters() {
             this.activeStatusTab = 'Semua';
             this.searchQuery = '';
             this.sortBy = 'Terbaru';
             this.selectedDateRange = '2 - 8 Mei 2025';
             this.filterByCalendarDay = false;
             this.selectedCalendarDay = 2;
             this.filterDraft = {
                 status: 'Semua Status',
                 paymentMethod: 'Semua Metode',
                 dateRange: '2 Mei 2025 - 8 Mei 2025'
             };
             this.appliedFilter = { ...this.filterDraft };
             this.currentPage = 1;
             this.$dispatch('show-toast', { msg: 'Semua filter telah direset.' });
         },

         selectCalendarDay(cell) {
             if (!cell.isCurrentMonth) return;
             if (this.filterByCalendarDay && this.selectedCalendarDay === cell.day) {
                 this.filterByCalendarDay = false;
                 this.$dispatch('show-toast', { msg: 'Filter tanggal kalender dinonaktifkan.' });
             } else {
                 this.selectedCalendarDay = cell.day;
                 this.filterByCalendarDay = true;
                 this.currentPage = 1;
                 this.$dispatch('show-toast', { msg: `Menampilkan jadwal pengambilan tanggal ${cell.day} ${this.monthNames[this.calendarMonthIndex]} ${this.calendarYear}.` });
             }
         },

         prevMonth() {
             if (this.calendarMonthIndex === 0) {
                 this.calendarMonthIndex = 11;
                 this.calendarYear--;
             } else {
                 this.calendarMonthIndex--;
             }
             this.filterByCalendarDay = false;
         },

         nextMonth() {
             if (this.calendarMonthIndex === 11) {
                 this.calendarMonthIndex = 0;
                 this.calendarYear++;
             } else {
                 this.calendarMonthIndex++;
             }
             this.filterByCalendarDay = false;
         },

         updateReservationStatus(item, newStatus) {
             const now = new Date();
             const hh = String(now.getHours()).padStart(2, '0');
             const mm = String(now.getMinutes()).padStart(2, '0');
             item.status = newStatus;
             if (newStatus === 'Dikonfirmasi') {
                 item.statusTimestamp = `Dikonfirmasi ${hh}.${mm}`;
                 if (item.paymentStatus === 'Menunggu') item.paymentStatus = 'Lunas';
             } else if (newStatus === 'Siap Diambil') {
                 item.statusTimestamp = `Siap ${hh}.${mm}`;
             } else if (newStatus === 'Selesai') {
                 item.statusTimestamp = `Selesai ${hh}.${mm}`;
                 item.paymentStatus = 'Lunas';
             } else if (newStatus === 'Dibatalkan') {
                 item.statusTimestamp = `Dibatalkan ${hh}.${mm}`;
                 item.paymentStatus = 'Dibatalkan';
             }
             this.openActionRowId = null;
             this.$dispatch('show-toast', { msg: `Status ${item.id} diperbarui menjadi ${newStatus}.` });
         },

         openManualModal() {
             this.manualFormError = '';
             this.manualForm = {
                 customerName: '',
                 customerPhone: '',
                 pickupDate: '2 Mei 2025',
                 pickupSlot: '16.00 - 20.00',
                 foodName: 'Roti Cokelat Surplus',
                 itemCount: 2,
                 totalPrice: 30000,
                 paymentMethod: 'QRIS',
                 paymentStatus: 'Lunas',
                 status: 'Dikonfirmasi',
                 notes: ''
             };
             this.isManualModalOpen = true;
         },

         submitManualReservation() {
             if (!this.manualForm.customerName.trim() || !this.manualForm.customerPhone.trim()) {
                 this.manualFormError = 'Nama pelanggan dan nomor telepon wajib diisi.';
                 return;
             }
             const seq = String(this.reservations.length + 1).padStart(3, '0');
             const newEntry = {
                 id: `#RES-250502-${seq}`,
                 pickupCode: `WL-502${seq}M`,
                 createdAt: '2 Mei 2025, Baru saja',
                 sortTs: 202505022359,
                 customerName: this.manualForm.customerName.trim(),
                 customerPhone: this.manualForm.customerPhone.trim(),
                 avatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=120&q=80',
                 pickupDate: this.manualForm.pickupDate,
                 pickupDay: 2,
                 pickupMonth: 4,
                 pickupSlot: this.manualForm.pickupSlot,
                 status: this.manualForm.status,
                 statusTimestamp: this.manualForm.status === 'Menunggu' ? 'Dibuat Baru saja' : `${this.manualForm.status} Baru saja`,
                 total: Number(this.manualForm.totalPrice) || 0,
                 itemCount: Number(this.manualForm.itemCount) || 1,
                 paymentMethod: this.manualForm.paymentMethod,
                 paymentStatus: this.manualForm.paymentStatus,
                 items: [
                     { name: this.manualForm.foodName, qty: Number(this.manualForm.itemCount) || 1, price: Math.round((Number(this.manualForm.totalPrice) || 0) / (Number(this.manualForm.itemCount) || 1)) }
                 ],
                 notes: this.manualForm.notes.trim() || 'Reservasi manual via Merchant Portal.'
             };
             this.reservations.unshift(newEntry);
             this.extraAddedCount++;
             this.currentPage = 1;
             this.isManualModalOpen = false;
             this.$dispatch('show-toast', { msg: `Reservasi manual ${newEntry.id} untuk ${newEntry.customerName} berhasil dibuat!` });
         },

         exportCsv() {
             const headers = ['ID Reservasi', 'Waktu Dibuat', 'Pelanggan', 'Telepon', 'Tanggal Ambil', 'Slot Waktu', 'Status', 'Jumlah Item', 'Total (Rp)', 'Metode Pembayaran', 'Status Pembayaran'];
             const rows = this.filteredReservations.map(r => [
                 r.id, r.createdAt, r.customerName, r.customerPhone, r.pickupDate, r.pickupSlot, r.status, r.itemCount, r.total, r.paymentMethod, r.paymentStatus
             ]);
             const q = String.fromCharCode(34);
             const csv = [headers, ...rows].map(row => row.map(v => q + String(v).split(q).join(q + q) + q).join(',')).join('\n');
             const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
             const url = URL.createObjectURL(blob);
             const a = document.createElement('a');
             a.href = url;
             a.download = 'manajemen-reservasi-sunny-bakery.csv';
             a.click();
             URL.revokeObjectURL(url);
             this.$dispatch('show-toast', { msg: 'Data Manajemen Reservasi (.csv) berhasil diekspor!' });
         }
     }">

    <!-- ======================================================= -->
    <!-- 1. SIDEBAR KIRI (DESKTOP DOCKED & MOBILE DRAWER)        -->
    <!-- ======================================================= -->

    <!-- Mobile Backdrop -->
    <div x-cloak x-show="mobileSidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileSidebarOpen = false"
         class="fixed inset-0 z-40 bg-stone-900/50 backdrop-blur-xs lg:hidden">
    </div>

    <!-- Sidebar Container -->
    <aside :class="[
               mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
               sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'
           ]"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-stone-200/80 flex flex-col justify-between transition-all duration-300 ease-in-out lg:static lg:z-auto shrink-0">

        <div class="px-4 pt-6 pb-4 flex flex-col h-full overflow-y-auto custom-scrollbar">

            <!-- Brand Logo & 'Business' Pill -->
            <div class="flex items-center justify-between mb-6 px-1.5">
                <a href="{{ url('/?view=dashboard') }}" class="inline-flex flex-col group">
                    <div class="flex items-center text-[22px] font-black tracking-tight leading-none">
                        <span class="text-[#F59E0B] text-xs -mt-2 mr-0.5 font-bold" x-show="!sidebarCollapsed">✨</span>
                        <span class="text-[#1B5E3A]" x-show="!sidebarCollapsed">What's</span>
                        <span class="text-[#E67E22] ml-1" x-show="!sidebarCollapsed">Left</span>
                        <span class="text-[#1B5E3A] text-xl font-black" x-show="sidebarCollapsed" x-cloak>WL</span>
                        <span class="text-[#F59E0B] text-xs -mt-2 ml-0.5 font-bold" x-show="!sidebarCollapsed">✨</span>
                    </div>
                    <div class="mt-1.5 self-center" x-show="!sidebarCollapsed">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-[#EBF5F0] text-[#1B5E3A] tracking-wide">
                            Business
                        </span>
                    </div>
                </a>

                <!-- Close Sidebar on Mobile -->
                <button type="button"
                        @click="mobileSidebarOpen = false"
                        aria-label="Tutup menu sidebar"
                        class="p-1.5 rounded-lg text-stone-400 hover:text-stone-700 hover:bg-stone-100 lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-1 flex-1" aria-label="Navigasi Merchant">

                <!-- 1. Dashboard -->
                <a href="{{ url('/?view=dashboard') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
                </a>

                <!-- 2. Makanan Surplus -->
                <a href="{{ url('/?view=makanan-surplus') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Makanan Surplus</span>
                </a>

                <!-- 3. Produksi Harian -->
                <a href="{{ url('/?view=produksi-harian') }}"
                   class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Produksi Harian</span>
                </a>

                <!-- 4. Reservasi (AKTIF / MENYALA HIJAU + BADGE 5) -->
                <a href="{{ url('/?view=reservations-management') }}"
                   aria-current="page"
                   class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-bold bg-[#EBF5F0] text-[#1B5E3A] shadow-2xs transition-all duration-150">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="w-5 h-5 shrink-0 text-[#1B5E3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="!sidebarCollapsed" class="truncate">Reservasi</span>
                    </div>
                    <span x-show="!sidebarCollapsed"
                          class="w-5 h-5 rounded-full bg-[#EA580C] text-white text-[11px] font-bold flex items-center justify-center shrink-0 shadow-xs">
                        5
                    </span>
                </a>

                <!-- 5. Pelanggan -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Pelanggan dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Pelanggan</span>
                </button>

                <div class="my-2.5 border-t border-stone-100"></div>

                <!-- 6. Analitik -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Analitik dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Analitik</span>
                </button>

                <!-- 7. Promo & Diskon -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Promo & Diskon dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Promo & Diskon</span>
                </button>

                <!-- 8. Review -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Review dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Review</span>
                </button>

                <div class="my-2.5 border-t border-stone-100"></div>

                <!-- 9. Pengaturan Toko -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Pengaturan Toko dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Pengaturan Toko</span>
                </button>

                <!-- 10. Akun & Tim -->
                <button type="button"
                        @click="$dispatch('show-toast', { msg: 'Menu Akun & Tim dipilih' })"
                        class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm transition-all duration-150 text-left text-stone-600 hover:bg-stone-50 hover:text-stone-900 font-medium">
                    <svg class="w-5 h-5 shrink-0 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Akun & Tim</span>
                </button>
            </nav>

            <!-- Bottom Motivational Card (Sesuai Referensi Visual) -->
            <div x-show="!sidebarCollapsed"
                 class="mt-6 p-4 rounded-2xl bg-[#F4F8F5] border border-[#DCF2E4] relative overflow-hidden">
                <!-- Eco Bag & Food Illustration -->
                <div class="mb-3 flex items-center justify-center">
                    <div class="relative w-24 h-16 flex items-center justify-center">
                        <div class="w-12 h-14 rounded-lg bg-[#86EFAC]/55 border border-[#22C55E]/50 flex flex-col items-center justify-center shadow-2xs relative">
                            <div class="w-5 h-2.5 border-2 border-[#1B5E3A] border-b-0 rounded-t-full -mt-6 mb-1.5 bg-transparent"></div>
                            <span class="text-base leading-none">🌱</span>
                        </div>
                        <span class="absolute -bottom-0.5 left-1 text-xl drop-shadow-xs">🥗</span>
                        <span class="absolute -bottom-0.5 right-1 text-lg drop-shadow-xs">🥐</span>
                    </div>
                </div>
                <p class="text-[11.5px] font-medium text-[#374151] leading-relaxed">
                    Kelola reservasi dengan mudah dan pastikan setiap makanan tersalurkan dengan tepat. 🌱
                </p>
            </div>
        </div>
    </aside>

    <!-- ======================================================= -->
    <!-- 2. MAIN WORKSPACE (TOPBAR + CONTENT 2-KOLOM)            -->
    <!-- ======================================================= -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-stone-200/80 px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0 z-30">
            <!-- Left: Hamburger Toggle -->
            <div class="flex items-center gap-3">
                <button type="button"
                        @click="mobileSidebarOpen = true"
                        aria-label="Buka menu navigasi"
                        class="p-2 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-stone-900 transition lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <button type="button"
                        @click="sidebarCollapsed = !sidebarCollapsed"
                        aria-label="Perkecil atau perluas sidebar"
                        class="hidden lg:inline-flex p-2 rounded-xl text-stone-500 hover:bg-stone-100 hover:text-stone-900 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <!-- Right: Notification Bell + Merchant Profile -->
            <div class="flex items-center gap-3 sm:gap-4">

                <!-- Notification Bell Dropdown -->
                <div class="relative" @click.away="notificationDropdownOpen = false">
                    <button type="button"
                            @click="notificationDropdownOpen = !notificationDropdownOpen"
                            aria-label="Notifikasi reservasi"
                            class="relative p-2 rounded-xl text-stone-600 hover:bg-stone-100 hover:text-[#1B5E3A] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.9" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-[#EA580C] text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white">
                            5
                        </span>
                    </button>

                    <!-- Notification Popover -->
                    <div x-cloak x-show="notificationDropdownOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-2 z-50">
                        <div class="px-4 py-2.5 border-b border-stone-100 flex items-center justify-between">
                            <span class="text-sm font-bold text-[#1F2937]">Notifikasi Reservasi</span>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-[#EBF5F0] text-[#1B5E3A]">5 baru</span>
                        </div>
                        <div class="divide-y divide-stone-100 max-h-64 overflow-y-auto custom-scrollbar">
                            <div class="px-4 py-3 hover:bg-stone-50 transition">
                                <p class="text-xs font-semibold text-[#1F2937]">Reservasi baru #RES-250502-001</p>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Dinda Ayu memesan 5 item (Rp 75.000) • Menunggu konfirmasi.</p>
                            </div>
                            <div class="px-4 py-3 hover:bg-stone-50 transition">
                                <p class="text-xs font-semibold text-[#1F2937]">Pembayaran QRIS Lunas</p>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Budi Santoso (#RES-250501-015) telah menyelesaikan pembayaran.</p>
                            </div>
                            <div class="px-4 py-3 hover:bg-stone-50 transition">
                                <p class="text-xs font-semibold text-[#1F2937]">Pengingat Slot Pengambilan</p>
                                <p class="text-[11px] text-[#6B7280] mt-0.5">Citra Lestari dijadwalkan mengambil pesanan pukul 17.00 - 19.00.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Merchant Profile Dropdown -->
                <div class="relative" @click.away="profileDropdownOpen = false">
                    <button type="button"
                            @click="profileDropdownOpen = !profileDropdownOpen"
                            class="flex items-center gap-2.5 p-1 pr-2 rounded-xl hover:bg-stone-50 transition">
                        <div class="w-9 h-9 rounded-full bg-[#EBF5F0] border border-[#DCF2E4] flex items-center justify-center overflow-hidden shrink-0">
                            <span class="text-base">🥐</span>
                        </div>
                        <div class="hidden sm:flex flex-col text-left leading-tight">
                            <span class="text-[13px] font-bold text-[#1F2937]">Sunny Bakery</span>
                            <span class="text-[11px] text-[#6B7280] font-medium">Owner</span>
                        </div>
                        <svg class="w-4 h-4 text-stone-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Profile Menu -->
                    <div x-cloak x-show="profileDropdownOpen"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-stone-200/80 py-1.5 z-50">
                        <a href="{{ url('/?view=dashboard') }}" class="block px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 hover:text-[#1B5E3A]">Dashboard Merchant</a>
                        <a href="{{ url('/') }}" class="block px-4 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 hover:text-[#1B5E3A]">Lihat Halaman Konsumen</a>
                        <div class="my-1 border-t border-stone-100"></div>
                        <a href="{{ url('/?view=login') }}" class="block px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Keluar Akun</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- SCROLLABLE MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7">
            <div class="max-w-[1440px] mx-auto">

                <!-- PAGE HEADER ROW -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-xl sm:text-[22px] font-extrabold text-[#1F2937] tracking-tight">
                                Manajemen Reservasi
                            </h1>
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-[#EBF5F0] text-[#1B5E3A]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-[13px] text-[#6B7280] mt-0.5">
                            Pantau dan kelola semua reservasi makanan surplus.
                        </p>
                    </div>

                    <!-- Primary Action Button -->
                    <div class="shrink-0">
                        <button type="button"
                                @click="openManualModal()"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-[13px] font-bold shadow-sm shadow-[#1B5E3A]/20 transition-all duration-150 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Buat Reservasi Manual</span>
                        </button>
                    </div>
                </div>

                <!-- 2-COLUMN WORKSPACE GRID -->
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-5 items-start">

                    <!-- =================================================== -->
                    <!-- LEFT COLUMN (9/12): TABS, CONTROLS, TABLE & PAGING  -->
                    <!-- =================================================== -->
                    <div class="xl:col-span-9 space-y-4 min-w-0">

                        <!-- STATUS TABS BAR -->
                        <div class="border-b border-stone-200/80 overflow-x-auto no-scrollbar">
                            <div class="flex items-center gap-1 sm:gap-4 min-w-max" role="tablist" aria-label="Filter status reservasi">
                                <!-- Semua -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Semua'"
                                        @click="setTab('Semua')"
                                        :class="activeStatusTab === 'Semua' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#6B7280] hover:text-[#1F2937] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Semua</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#EBF5F0] text-[#1B5E3A]" x-text="tabCounts['Semua']"></span>
                                </button>

                                <!-- Menunggu -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Menunggu'"
                                        @click="setTab('Menunggu')"
                                        :class="activeStatusTab === 'Menunggu' ? 'border-[#D97706] text-[#B45309] font-bold' : 'border-transparent text-[#D97706] hover:text-[#B45309] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Menunggu</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEF3C7] text-[#B45309]" x-text="tabCounts['Menunggu']"></span>
                                </button>

                                <!-- Dikonfirmasi -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Dikonfirmasi'"
                                        @click="setTab('Dikonfirmasi')"
                                        :class="activeStatusTab === 'Dikonfirmasi' ? 'border-[#1B5E3A] text-[#1B5E3A] font-bold' : 'border-transparent text-[#2E7D32] hover:text-[#1B5E3A] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Dikonfirmasi</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#DCF2E4] text-[#1B5E3A]" x-text="tabCounts['Dikonfirmasi']"></span>
                                </button>

                                <!-- Siap Diambil -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Siap Diambil'"
                                        @click="setTab('Siap Diambil')"
                                        :class="activeStatusTab === 'Siap Diambil' ? 'border-[#0284C7] text-[#0369A1] font-bold' : 'border-transparent text-[#0284C7] hover:text-[#0369A1] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Siap Diambil</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E0F2FE] text-[#0369A1]" x-text="tabCounts['Siap Diambil']"></span>
                                </button>

                                <!-- Selesai -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Selesai'"
                                        @click="setTab('Selesai')"
                                        :class="activeStatusTab === 'Selesai' ? 'border-[#374151] text-[#1F2937] font-bold' : 'border-transparent text-[#4B5563] hover:text-[#1F2937] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Selesai</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#F3F4F6] text-[#4B5563]" x-text="tabCounts['Selesai']"></span>
                                </button>

                                <!-- Dibatalkan -->
                                <button type="button"
                                        role="tab"
                                        :aria-selected="activeStatusTab === 'Dibatalkan'"
                                        @click="setTab('Dibatalkan')"
                                        :class="activeStatusTab === 'Dibatalkan' ? 'border-[#DC2626] text-[#DC2626] font-bold' : 'border-transparent text-[#DC2626] hover:text-[#B91C1C] font-semibold'"
                                        class="inline-flex items-center gap-1.5 px-2.5 pb-3 pt-1 text-[13px] border-b-2 transition-colors whitespace-nowrap cursor-pointer">
                                    <span>Dibatalkan</span>
                                    <span class="px-1.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEE2E2] text-[#DC2626]" x-text="tabCounts['Dibatalkan']"></span>
                                </button>
                            </div>
                        </div>

                        <!-- SEARCH & CONTROLS ROW -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                            <!-- Search Input -->
                            <div class="relative flex-1 max-w-md">
                                <span class="absolute inset-y-0 left-3.5 flex items-center text-stone-400 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </span>
                                <input type="text"
                                       x-model="searchQuery"
                                       @input="currentPage = 1"
                                       placeholder="Cari nama pelanggan / ID reservasi..."
                                       aria-label="Cari nama pelanggan atau ID reservasi"
                                       class="w-full pl-10 pr-4 py-2.5 text-[13px] bg-white border border-stone-200/90 rounded-xl text-[#1F2937] placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A] transition">
                            </div>

                            <!-- Right Controls: Date Range, Sort, Export -->
                            <div class="flex flex-wrap items-center gap-2">
                                <!-- Date Range Selector -->
                                <div class="relative" @click.away="dateRangeDropdownOpen = false">
                                    <button type="button"
                                            @click="dateRangeDropdownOpen = !dateRangeDropdownOpen"
                                            class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-stone-200/90 rounded-xl text-[12.5px] font-semibold text-[#1F2937] hover:border-stone-300 transition cursor-pointer">
                                        <svg class="w-4 h-4 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span x-text="selectedDateRange"></span>
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="dateRangeDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                        <template x-for="range in ['Hari ini (2 Mei 2025)', '2 - 8 Mei 2025', '28 Apr - 2 Mei 2025', 'Bulan Mei 2025']" :key="range">
                                            <button type="button"
                                                    @click="selectedDateRange = range; dateRangeDropdownOpen = false; $dispatch('show-toast', { msg: 'Rentang tanggal diubah ke ' + range })"
                                                    :class="selectedDateRange === range ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                    class="w-full text-left px-3.5 py-2 text-xs transition">
                                                <span x-text="range"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Sort Dropdown -->
                                <div class="relative" @click.away="sortDropdownOpen = false">
                                    <button type="button"
                                            @click="sortDropdownOpen = !sortDropdownOpen"
                                            class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white border border-stone-200/90 rounded-xl text-[12.5px] font-semibold text-[#1F2937] hover:border-stone-300 transition cursor-pointer">
                                        <span class="text-[#6B7280] font-medium">Urutkan:</span>
                                        <span x-text="sortBy"></span>
                                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="sortDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1.5 w-44 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                        <template x-for="opt in ['Terbaru', 'Terlama', 'Total Tertinggi', 'Total Terendah', 'Nama A-Z']" :key="opt">
                                            <button type="button"
                                                    @click="sortBy = opt; sortDropdownOpen = false; currentPage = 1"
                                                    :class="sortBy === opt ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                    class="w-full text-left px-3.5 py-2 text-xs transition">
                                                <span x-text="opt"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Export Button -->
                                <button type="button"
                                        @click="exportCsv()"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-white border border-stone-200/90 rounded-xl text-[12.5px] font-semibold text-[#1F2937] hover:bg-stone-50 hover:border-stone-300 transition cursor-pointer">
                                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    <span>Export</span>
                                </button>
                            </div>
                        </div>

                        <!-- ACTIVE CALENDAR FILTER BANNER (IF SELECTED) -->
                        <div x-cloak x-show="filterByCalendarDay"
                             class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-[#EBF5F0] border border-[#DCF2E4] text-xs text-[#1B5E3A]">
                            <div class="flex items-center gap-2 font-semibold">
                                <span>📅 Menampilkan jadwal pengambilan tanggal <strong x-text="selectedCalendarDay + ' ' + monthNames[calendarMonthIndex] + ' ' + calendarYear"></strong></span>
                            </div>
                            <button type="button"
                                    @click="filterByCalendarDay = false"
                                    class="font-bold underline hover:text-[#14472C] cursor-pointer">
                                Tampilkan Semua Tanggal
                            </button>
                        </div>

                        <!-- RESERVATIONS TABLE CARD -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card overflow-hidden">

                            <!-- Desktop & Tablet Table -->
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse min-w-[780px]">
                                    <thead>
                                        <tr class="bg-[#F8F8F7] border-b border-stone-200/80 text-[12px] font-bold text-[#4B5563]">
                                            <th class="py-3.5 pl-5 pr-3">Reservasi</th>
                                            <th class="py-3.5 px-3">Pelanggan</th>
                                            <th class="py-3.5 px-3">Tanggal & Waktu</th>
                                            <th class="py-3.5 px-3">Status</th>
                                            <th class="py-3.5 px-3">Total</th>
                                            <th class="py-3.5 px-3">Pembayaran</th>
                                            <th class="py-3.5 pl-3 pr-5 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-stone-100 text-[13px]">
                                        <template x-for="item in paginatedReservations" :key="item.id">
                                            <tr class="hover:bg-[#FAF8F5]/80 transition-colors group">
                                                <!-- 1. Reservasi (ID + Timestamp) -->
                                                <td class="py-3.5 pl-5 pr-3 align-middle whitespace-nowrap">
                                                    <div class="font-bold text-[#1F2937] text-[12.5px] tracking-tight" x-text="item.id"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.createdAt"></div>
                                                </td>

                                                <!-- 2. Pelanggan (Avatar + Name + Phone) -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="flex items-center gap-2.5">
                                                        <img :src="item.avatar"
                                                             :alt="item.customerName"
                                                             class="w-8 h-8 rounded-full object-cover ring-1 ring-stone-200 shrink-0 bg-stone-100">
                                                        <div>
                                                            <div class="font-bold text-[#1F2937] text-[12.5px]" x-text="item.customerName"></div>
                                                            <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.customerPhone"></div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- 3. Tanggal & Waktu Pengambilan -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="font-semibold text-[#1F2937] text-[12.5px]" x-text="item.pickupDate"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.pickupSlot"></div>
                                                </td>

                                                <!-- 4. Status Badge + Stamp -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                                          :class="statusBadgeClass(item.status)"
                                                          x-text="item.status"></span>
                                                    <div class="text-[10.5px] text-[#6B7280] mt-1" x-text="item.statusTimestamp"></div>
                                                </td>

                                                <!-- 5. Total & Jumlah Item -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="font-bold text-[#1F2937] text-[12.5px]" x-text="formatRupiah(item.total)"></div>
                                                    <div class="text-[11px] text-[#6B7280] mt-0.5" x-text="item.itemCount + ' item'"></div>
                                                </td>

                                                <!-- 6. Pembayaran -->
                                                <td class="py-3.5 px-3 align-middle whitespace-nowrap">
                                                    <div class="flex items-center gap-1.5 font-semibold text-[#1F2937] text-[12px]">
                                                        <span x-show="item.paymentMethod !== '—'"
                                                              class="w-1.5 h-1.5 rounded-full shrink-0"
                                                              :class="paymentDotClass(item.paymentStatus, item.paymentMethod)"></span>
                                                        <span x-text="item.paymentMethod"></span>
                                                    </div>
                                                    <div class="text-[11px] mt-0.5"
                                                         :class="item.paymentStatus === 'Dibatalkan' ? 'text-[#DC2626]' : 'text-[#6B7280]'"
                                                         x-text="item.paymentStatus"></div>
                                                </td>

                                                <!-- 7. Aksi (Eye + Three-dots menu) -->
                                                <td class="py-3.5 pl-3 pr-5 align-middle whitespace-nowrap">
                                                    <div class="flex items-center justify-center gap-1.5 relative">
                                                        <!-- Detail Button -->
                                                        <button type="button"
                                                                @click="detailModalItem = item"
                                                                :aria-label="'Lihat detail reservasi ' + item.id"
                                                                title="Lihat Detail"
                                                                class="w-8 h-8 rounded-lg border border-stone-200/80 bg-white text-stone-500 hover:text-[#1B5E3A] hover:border-[#1B5E3A]/40 hover:bg-[#EBF5F0]/50 flex items-center justify-center transition cursor-pointer">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </button>

                                                        <!-- Three-Dots Menu -->
                                                        <div class="relative" @click.away="if (openActionRowId === item.id) openActionRowId = null">
                                                            <button type="button"
                                                                    @click="openActionRowId = openActionRowId === item.id ? null : item.id"
                                                                    :aria-label="'Opsi lainnya untuk ' + item.id"
                                                                    title="Aksi Lainnya"
                                                                    class="w-8 h-8 rounded-lg border border-stone-200/80 bg-white text-stone-500 hover:text-[#1F2937] hover:bg-stone-50 flex items-center justify-center transition cursor-pointer">
                                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                    <circle cx="12" cy="5" r="1.6"/>
                                                                    <circle cx="12" cy="12" r="1.6"/>
                                                                    <circle cx="12" cy="19" r="1.6"/>
                                                                </svg>
                                                            </button>

                                                            <!-- Row Action Dropdown -->
                                                            <div x-cloak x-show="openActionRowId === item.id"
                                                                 x-transition
                                                                 class="absolute right-0 mt-1.5 w-48 bg-white rounded-xl shadow-xl border border-stone-200/90 py-1.5 z-40 text-left">
                                                                <button type="button"
                                                                        @click="detailModalItem = item; openActionRowId = null"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-50 flex items-center gap-2">
                                                                    <span>👁️</span> Lihat Detail Reservasi
                                                                </button>
                                                                <button type="button"
                                                                        x-show="item.status === 'Menunggu'"
                                                                        @click="updateReservationStatus(item, 'Dikonfirmasi')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-[#1B5E3A] hover:bg-[#EBF5F0] flex items-center gap-2">
                                                                    <span>✅</span> Konfirmasi Pesanan
                                                                </button>
                                                                <button type="button"
                                                                        x-show="item.status === 'Dikonfirmasi'"
                                                                        @click="updateReservationStatus(item, 'Siap Diambil')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-[#0369A1] hover:bg-[#E0F2FE]/60 flex items-center gap-2">
                                                                    <span>🛍️</span> Tandai Siap Diambil
                                                                </button>
                                                                <button type="button"
                                                                        x-show="item.status === 'Siap Diambil' || item.status === 'Dikonfirmasi'"
                                                                        @click="updateReservationStatus(item, 'Selesai')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-[#1B5E3A] hover:bg-[#EBF5F0] flex items-center gap-2">
                                                                    <span>🎉</span> Tandai Selesai
                                                                </button>
                                                                <div class="my-1 border-t border-stone-100" x-show="item.status !== 'Dibatalkan' && item.status !== 'Selesai'"></div>
                                                                <button type="button"
                                                                        x-show="item.status !== 'Dibatalkan' && item.status !== 'Selesai'"
                                                                        @click="updateReservationStatus(item, 'Dibatalkan')"
                                                                        class="w-full px-3.5 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 flex items-center gap-2">
                                                                    <span>❌</span> Batalkan Reservasi
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>

                                        <!-- Empty State -->
                                        <tr x-show="paginatedReservations.length === 0" x-cloak>
                                            <td colspan="7" class="py-12 px-6 text-center">
                                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-[#FAF6F0] text-2xl mb-3">📋</div>
                                                <p class="text-sm font-bold text-[#1F2937]">Tidak ada reservasi ditemukan</p>
                                                <p class="text-xs text-[#6B7280] mt-1">Coba ubah kata kunci pencarian atau reset filter yang aktif.</p>
                                                <button type="button"
                                                        @click="resetFilters()"
                                                        class="mt-3 px-4 py-2 rounded-xl bg-[#EBF5F0] text-[#1B5E3A] text-xs font-bold hover:bg-[#DCF2E4] transition cursor-pointer">
                                                    Reset Filter
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- PAGINATION FOOTER (< 1 2 3 >) -->
                            <div class="px-5 py-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-white">
                                <p class="text-xs text-[#6B7280]">
                                    Menampilkan <span class="font-bold text-[#1F2937]" x-text="paginatedReservations.length"></span> dari <span class="font-bold text-[#1F2937]" x-text="filteredReservations.length"></span> reservasi
                                </p>

                                <nav class="flex items-center gap-1.5" aria-label="Navigasi halaman reservasi">
                                    <!-- Prev -->
                                    <button type="button"
                                            @click="if (currentPage > 1) currentPage--"
                                            :disabled="currentPage <= 1"
                                            aria-label="Halaman sebelumnya"
                                            class="w-8 h-8 rounded-lg border border-stone-200/80 flex items-center justify-center text-stone-500 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>

                                    <!-- Page Numbers -->
                                    <template x-for="page in totalPages" :key="page">
                                        <button type="button"
                                                @click="currentPage = page"
                                                :aria-current="currentPage === page ? 'page' : false"
                                                :class="currentPage === page
                                                    ? 'bg-[#1B5E3A] text-white border-[#1B5E3A] font-bold shadow-xs'
                                                    : 'bg-white text-[#4B5563] border-stone-200/80 hover:bg-stone-50 font-semibold'"
                                                class="w-8 h-8 rounded-lg border text-xs flex items-center justify-center transition cursor-pointer"
                                                x-text="page">
                                        </button>
                                    </template>

                                    <!-- Next -->
                                    <button type="button"
                                            @click="if (currentPage < totalPages) currentPage++"
                                            :disabled="currentPage >= totalPages"
                                            aria-label="Halaman berikutnya"
                                            class="w-8 h-8 rounded-lg border border-stone-200/80 flex items-center justify-center text-stone-500 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- =================================================== -->
                    <!-- RIGHT COLUMN (3/12): RINGKASAN, FILTER & KALENDER   -->
                    <!-- =================================================== -->
                    <div class="xl:col-span-3 space-y-4">

                        <!-- CARD 1: RINGKASAN RESERVASI -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-4">
                            <div class="flex items-center justify-between mb-3.5">
                                <h2 class="text-[14px] font-bold text-[#1F2937]">Ringkasan Reservasi</h2>

                                <!-- Period Selector -->
                                <div class="relative" @click.away="summaryPeriodDropdownOpen = false">
                                    <button type="button"
                                            @click="summaryPeriodDropdownOpen = !summaryPeriodDropdownOpen"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-stone-200 text-[11px] font-semibold text-[#4B5563] hover:bg-stone-50 transition cursor-pointer">
                                        <span x-text="summaryPeriod"></span>
                                        <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-cloak x-show="summaryPeriodDropdownOpen"
                                         x-transition
                                         class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-stone-200/80 py-1 z-30">
                                        <template x-for="p in ['Hari ini', 'Minggu ini', 'Bulan ini']" :key="p">
                                            <button type="button"
                                                    @click="summaryPeriod = p; summaryPeriodDropdownOpen = false"
                                                    :class="summaryPeriod === p ? 'bg-[#EBF5F0] text-[#1B5E3A] font-bold' : 'text-stone-700 hover:bg-stone-50'"
                                                    class="w-full text-left px-3 py-1.5 text-[11.5px] transition">
                                                <span x-text="p"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- 2x3 Metric Grid -->
                            <div class="grid grid-cols-2 gap-2.5">
                                <!-- Total Reservasi -->
                                <button type="button" @click="setTab('Semua')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-stone-200 transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#FEF3C7] text-[#D97706] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.total"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Total Reservasi</div>
                                    </div>
                                </button>

                                <!-- Dikonfirmasi -->
                                <button type="button" @click="setTab('Dikonfirmasi')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#DCF2E4] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#DCF2E4] text-[#1B5E3A] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.confirmed"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Dikonfirmasi</div>
                                    </div>
                                </button>

                                <!-- Siap Diambil -->
                                <button type="button" @click="setTab('Siap Diambil')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#BAE6FD] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.ready"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Siap Diambil</div>
                                    </div>
                                </button>

                                <!-- Selesai -->
                                <button type="button" @click="setTab('Selesai')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-stone-200 transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#F3F4F6] text-[#4B5563] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.done"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Selesai</div>
                                    </div>
                                </button>

                                <!-- Menunggu -->
                                <button type="button" @click="setTab('Menunggu')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#FDE68A] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#FFEDD5] text-[#EA580C] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.pending"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Menunggu</div>
                                    </div>
                                </button>

                                <!-- Dibatalkan -->
                                <button type="button" @click="setTab('Dibatalkan')"
                                        class="flex items-start gap-2.5 p-2.5 rounded-xl bg-[#FDFBF8] border border-stone-100 hover:border-[#FECACA] transition text-left cursor-pointer">
                                    <span class="w-8 h-8 rounded-lg bg-[#FEE2E2] text-[#DC2626] flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-base font-extrabold text-[#1F2937] leading-tight" x-text="currentSummary.cancelled"></div>
                                        <div class="text-[10.5px] font-medium text-[#6B7280] leading-tight mt-0.5">Dibatalkan</div>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- CARD 2: FILTER PANEL -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-4">
                            <div class="flex items-center justify-between mb-3.5">
                                <h2 class="text-[14px] font-bold text-[#1F2937]">Filter</h2>
                                <button type="button"
                                        @click="resetFilters()"
                                        class="text-xs font-bold text-[#1B5E3A] hover:text-[#14472C] hover:underline cursor-pointer">
                                    Reset
                                </button>
                            </div>

                            <div class="space-y-3">
                                <!-- Status Dropdown -->
                                <div>
                                    <label for="filter-status" class="block text-[11.5px] font-bold text-[#374151] mb-1">Status</label>
                                    <select id="filter-status"
                                            x-model="filterDraft.status"
                                            class="w-full px-3 py-2 text-xs font-medium bg-white border border-stone-200/90 rounded-xl text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                                        <option value="Semua Status">Semua Status</option>
                                        <option value="Menunggu">Menunggu</option>
                                        <option value="Dikonfirmasi">Dikonfirmasi</option>
                                        <option value="Siap Diambil">Siap Diambil</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>

                                <!-- Metode Pembayaran Dropdown -->
                                <div>
                                    <label for="filter-payment" class="block text-[11.5px] font-bold text-[#374151] mb-1">Metode Pembayaran</label>
                                    <select id="filter-payment"
                                            x-model="filterDraft.paymentMethod"
                                            class="w-full px-3 py-2 text-xs font-medium bg-white border border-stone-200/90 rounded-xl text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                                        <option value="Semua Metode">Semua Metode</option>
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="QRIS">QRIS</option>
                                        <option value="E-Wallet">E-Wallet</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>

                                <!-- Tanggal Reservasi -->
                                <div>
                                    <label for="filter-date" class="block text-[11.5px] font-bold text-[#374151] mb-1">Tanggal Reservasi</label>
                                    <div class="relative">
                                        <input id="filter-date"
                                               type="text"
                                               x-model="filterDraft.dateRange"
                                               class="w-full pl-3 pr-9 py-2 text-xs font-medium bg-white border border-stone-200/90 rounded-xl text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                                        <span class="absolute inset-y-0 right-3 flex items-center text-stone-400 pointer-events-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <!-- Apply Filter Button -->
                                <button type="button"
                                        @click="applyRightFilter()"
                                        class="w-full mt-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-[#1B5E3A]/40 bg-[#F4F9F5] hover:bg-[#1B5E3A] text-[#1B5E3A] hover:text-white text-xs font-bold transition-colors cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                    </svg>
                                    <span>Terapkan Filter</span>
                                </button>
                            </div>
                        </div>

                        <!-- CARD 3: KALENDER PENGAMBILAN -->
                        <div class="bg-white rounded-2xl border border-stone-200/80 shadow-card p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-[14px] font-bold text-[#1F2937]">Kalender Pengambilan</h2>
                                <div class="flex items-center gap-1">
                                    <button type="button"
                                            @click="prevMonth()"
                                            aria-label="Bulan sebelumnya"
                                            class="w-6 h-6 rounded-lg hover:bg-stone-100 flex items-center justify-center text-stone-500 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                    </button>
                                    <button type="button"
                                            @click="nextMonth()"
                                            aria-label="Bulan berikutnya"
                                            class="w-6 h-6 rounded-lg hover:bg-stone-100 flex items-center justify-center text-stone-500 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="text-xs font-bold text-[#1F2937] mb-3" x-text="monthNames[calendarMonthIndex] + ' ' + calendarYear"></div>

                            <!-- Day Headers -->
                            <div class="grid grid-cols-7 text-center text-[10.5px] font-bold text-[#6B7280] mb-1.5">
                                <span>Sen</span>
                                <span>Sel</span>
                                <span>Rab</span>
                                <span>Kam</span>
                                <span>Jum</span>
                                <span>Sab</span>
                                <span>Min</span>
                            </div>

                            <!-- Calendar Cells -->
                            <div class="grid grid-cols-7 gap-y-1 text-center">
                                <template x-for="(cell, idx) in calendarDays" :key="idx">
                                    <div class="flex flex-col items-center justify-center py-0.5">
                                        <button type="button"
                                                @click="selectCalendarDay(cell)"
                                                :disabled="!cell.isCurrentMonth"
                                                :class="[
                                                    !cell.isCurrentMonth ? 'text-stone-300 cursor-default' : 'cursor-pointer',
                                                    cell.isCurrentMonth && selectedCalendarDay === cell.day ? 'bg-[#1B5E3A] text-white font-bold shadow-xs' : (cell.isCurrentMonth ? 'text-[#1F2937] hover:bg-[#EBF5F0] font-semibold' : '')
                                                ]"
                                                class="w-7 h-7 rounded-full text-[11.5px] flex items-center justify-center transition"
                                                x-text="cell.day">
                                        </button>
                                        <!-- Pickup Indicator Dots -->
                                        <div class="flex items-center justify-center gap-0.5 h-1.5 mt-0.5">
                                            <template x-for="(dotColor, dIdx) in cell.dots" :key="dIdx">
                                                <span class="w-1 h-1 rounded-full" :style="'background-color:' + dotColor"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- ======================================================= -->
    <!-- MODAL 1: DETAIL RESERVASI                               -->
    <!-- ======================================================= -->
    <div x-cloak x-show="detailModalItem !== null"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs"
         @keydown.escape.window="detailModalItem = null">
        <div x-show="detailModalItem !== null"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="detailModalItem = null"
             class="w-full max-w-lg bg-white rounded-3xl shadow-modal border border-stone-200/80 overflow-hidden">
            <template x-if="detailModalItem">
                <div>
                    <!-- Header -->
                    <div class="px-6 py-4 bg-[#FAF6F0] border-b border-stone-200/70 flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-[#1F2937]" x-text="detailModalItem.id"></h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold"
                                      :class="statusBadgeClass(detailModalItem.status)"
                                      x-text="detailModalItem.status"></span>
                            </div>
                            <p class="text-xs text-[#6B7280] mt-0.5">
                                Kode Pengambilan: <strong class="text-[#1B5E3A]" x-text="detailModalItem.pickupCode"></strong>
                            </p>
                        </div>
                        <button type="button"
                                @click="detailModalItem = null"
                                aria-label="Tutup detail reservasi"
                                class="w-8 h-8 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-white flex items-center justify-center transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <!-- Customer & Pickup Info -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="p-3.5 rounded-2xl bg-[#FAF8F5] border border-stone-100 flex items-center gap-3">
                                <img :src="detailModalItem.avatar" :alt="detailModalItem.customerName" class="w-10 h-10 rounded-full object-cover">
                                <div class="min-w-0">
                                    <div class="text-xs font-bold text-[#1F2937] truncate" x-text="detailModalItem.customerName"></div>
                                    <div class="text-[11px] text-[#6B7280]" x-text="detailModalItem.customerPhone"></div>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-[#FAF8F5] border border-stone-100">
                                <div class="text-[11px] text-[#6B7280] font-medium">Jadwal Pengambilan</div>
                                <div class="text-xs font-bold text-[#1F2937] mt-0.5" x-text="detailModalItem.pickupDate"></div>
                                <div class="text-[11px] font-semibold text-[#1B5E3A]" x-text="detailModalItem.pickupSlot + ' WIB'"></div>
                            </div>
                        </div>

                        <!-- Ordered Items -->
                        <div>
                            <h4 class="text-xs font-bold text-[#374151] uppercase tracking-wider mb-2">Rincian Makanan Surplus</h4>
                            <div class="rounded-2xl border border-stone-200/70 divide-y divide-stone-100 overflow-hidden">
                                <template x-for="(food, fIdx) in detailModalItem.items" :key="fIdx">
                                    <div class="px-4 py-2.5 flex items-center justify-between text-xs bg-white">
                                        <div>
                                            <span class="font-bold text-[#1F2937]" x-text="food.name"></span>
                                            <span class="text-[#6B7280] ml-1.5" x-text="'× ' + food.qty"></span>
                                        </div>
                                        <span class="font-bold text-[#1F2937]" x-text="formatRupiah(food.qty * food.price)"></span>
                                    </div>
                                </template>
                                <div class="px-4 py-3 bg-[#FAF8F5] flex items-center justify-between text-xs">
                                    <span class="font-bold text-[#4B5563]">Total (<span x-text="detailModalItem.itemCount"></span> item) • <span x-text="detailModalItem.paymentMethod"></span></span>
                                    <span class="text-sm font-extrabold text-[#1B5E3A]" x-text="formatRupiah(detailModalItem.total)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="p-3.5 rounded-2xl bg-[#F4F9F5] border border-[#DCF2E4] text-xs">
                            <span class="font-bold text-[#1B5E3A]">Catatan:</span>
                            <span class="text-[#374151] ml-1" x-text="detailModalItem.notes"></span>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-stone-50 border-t border-stone-100 flex flex-wrap items-center justify-end gap-2">
                        <button type="button"
                                x-show="detailModalItem.status === 'Menunggu'"
                                @click="updateReservationStatus(detailModalItem, 'Dikonfirmasi')"
                                class="px-4 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition cursor-pointer">
                            Konfirmasi Pesanan
                        </button>
                        <button type="button"
                                x-show="detailModalItem.status === 'Dikonfirmasi'"
                                @click="updateReservationStatus(detailModalItem, 'Siap Diambil')"
                                class="px-4 py-2 rounded-xl bg-[#0284C7] hover:bg-[#0369A1] text-white text-xs font-bold transition cursor-pointer">
                            Tandai Siap Diambil
                        </button>
                        <button type="button"
                                x-show="detailModalItem.status === 'Siap Diambil'"
                                @click="updateReservationStatus(detailModalItem, 'Selesai')"
                                class="px-4 py-2 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold transition cursor-pointer">
                            Selesaikan Pengambilan
                        </button>
                        <button type="button"
                                @click="detailModalItem = null"
                                class="px-4 py-2 rounded-xl border border-stone-200 bg-white text-stone-700 hover:bg-stone-100 text-xs font-bold transition cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ======================================================= -->
    <!-- MODAL 2: BUAT RESERVASI MANUAL                          -->
    <!-- ======================================================= -->
    <div x-cloak x-show="isManualModalOpen"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-xs"
         @keydown.escape.window="isManualModalOpen = false">
        <div x-show="isManualModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="isManualModalOpen = false"
             class="w-full max-w-lg bg-white rounded-3xl shadow-modal border border-stone-200/80 overflow-hidden">

            <div class="px-6 py-4 bg-[#FAF6F0] border-b border-stone-200/70 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-[#1F2937]">Buat Reservasi Manual</h3>
                    <p class="text-xs text-[#6B7280] mt-0.5">Catat reservasi walk-in atau pesanan via WhatsApp/telepon.</p>
                </div>
                <button type="button"
                        @click="isManualModalOpen = false"
                        aria-label="Tutup modal buat reservasi manual"
                        class="w-8 h-8 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-white flex items-center justify-center transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submitManualReservation()" class="p-6 space-y-4">
                <div x-show="manualFormError" x-cloak class="px-3.5 py-2.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-600" x-text="manualFormError"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Nama Pelanggan *</label>
                        <input type="text" x-model="manualForm.customerName" placeholder="Contoh: Raka Pratama"
                               class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Nomor Telepon *</label>
                        <input type="text" x-model="manualForm.customerPhone" placeholder="0812 xxxx xxxx"
                               class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Menu Makanan Surplus</label>
                        <select x-model="manualForm.foodName"
                                class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                            <option value="Roti Cokelat Surplus">Roti Cokelat Surplus</option>
                            <option value="Chicken Sandwich Segar">Chicken Sandwich Segar</option>
                            <option value="Salad Sayur Organik">Salad Sayur Organik</option>
                            <option value="Donut Mix Box">Donut Mix Box</option>
                            <option value="Paket Bakery Surprise">Paket Bakery Surprise</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Slot Pengambilan</label>
                        <select x-model="manualForm.pickupSlot"
                                class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                            <option value="16.00 - 18.00">16.00 - 18.00 WIB</option>
                            <option value="16.00 - 20.00">16.00 - 20.00 WIB</option>
                            <option value="17.00 - 19.00">17.00 - 19.00 WIB</option>
                            <option value="18.00 - 20.00">18.00 - 20.00 WIB</option>
                            <option value="19.00 - 21.00">19.00 - 21.00 WIB</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Jumlah Item</label>
                        <input type="number" min="1" x-model="manualForm.itemCount"
                               class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#374151] mb-1">Total (Rp)</label>
                        <input type="number" min="0" step="1000" x-model="manualForm.totalPrice"
                               class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-[#374151] mb-1">Pembayaran</label>
                        <select x-model="manualForm.paymentMethod"
                                class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                            <option value="Cash">Cash</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-[#374151] mb-1">Catatan (Opsional)</label>
                    <input type="text" x-model="manualForm.notes" placeholder="Tambahkan catatan khusus pelanggan..."
                           class="w-full px-3.5 py-2.5 text-xs border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]">
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            @click="isManualModalOpen = false"
                            class="px-4 py-2.5 rounded-xl border border-stone-200 text-xs font-bold text-stone-700 hover:bg-stone-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-xs font-bold shadow-sm transition cursor-pointer">
                        Simpan Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
