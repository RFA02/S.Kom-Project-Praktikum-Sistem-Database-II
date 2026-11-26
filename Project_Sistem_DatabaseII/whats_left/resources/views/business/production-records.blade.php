
@extends('layouts.business')

@section('title', 'Produksi Harian - What\'s Left')

@section('content')

<div
    class="flex h-screen overflow-hidden bg-[#FAF7F2]"
    x-data="productionRecordsPage()"
>

    <x-business.sidebar active="production-records" />

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

    <x-business.topbar />

    <main class="flex-1 overflow-y-auto custom-scrollbar p-4 sm:p-6 lg:p-7 space-y-6">

        <!-- PAGE HEADER -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <div class="flex items-center gap-2 text-xs text-stone-500 mb-2">
                    <span>Business</span>
                    <span>/</span>
                    <span class="text-[#1B5E3A] font-semibold">Produksi Harian</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-stone-900">
                    Produksi Harian
                </h1>

                <p class="text-sm text-stone-500 mt-1">
                    Pantau dan catat aktivitas produksi makanan tokomu.
                </p>
            </div>

            <button
                type="button"
                @click="openAddModal()"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#1B5E3A] hover:bg-[#14472C] text-white text-sm font-bold shadow-sm transition">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M12 4v16m8-8H4"/>
                </svg>

                Tambah Catatan Produksi
            </button>

        </div>


        <!-- SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            <!-- Total Produksi -->
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 7l9-4 9 4v10l-9 4-9-4V7zm9-4v18m-9-14l9 4 9-4"/>
                        </svg>
                    </div>

                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                        Total
                    </span>
                </div>

                <p class="text-sm text-stone-500 mt-4">Total Produksi</p>

                <div class="flex items-baseline gap-1 mt-1">
                    <h2 class="text-3xl font-black text-stone-900"
                        x-text="formatNumber(totalProduction)">
                    </h2>
                    <span class="text-xs text-stone-500">porsi</span>
                </div>

                <p class="text-xs text-stone-400 mt-2">
                    Jumlah seluruh makanan diproduksi
                </p>
            </div>


            <!-- Regular Sold -->
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                        Terjual
                    </span>
                </div>

                <p class="text-sm text-stone-500 mt-4">Terjual Reguler</p>

                <div class="flex items-baseline gap-1 mt-1">
                    <h2 class="text-3xl font-black text-stone-900"
                        x-text="formatNumber(totalRegularSold)">
                    </h2>
                    <span class="text-xs text-stone-500">porsi</span>
                </div>

                <p class="text-xs text-stone-400 mt-2">
                    Penjualan dengan harga normal
                </p>
            </div>


            <!-- Surplus -->
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">
                        Surplus
                    </span>
                </div>

                <p class="text-sm text-stone-500 mt-4">Makanan Surplus</p>

                <div class="flex items-baseline gap-1 mt-1">
                    <h2 class="text-3xl font-black text-stone-900"
                        x-text="formatNumber(totalSurplus)">
                    </h2>
                    <span class="text-xs text-stone-500">porsi</span>
                </div>

                <p class="text-xs text-stone-400 mt-2">
                    Makanan yang belum terjual reguler
                </p>
            </div>


            <!-- Waste -->
            <div class="bg-white border border-stone-200/80 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between">
                    <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"/>
                        </svg>
                    </div>

                    <span class="text-xs font-semibold text-red-600 bg-red-50 px-2.5 py-1 rounded-full">
                        Waste
                    </span>
                </div>

                <p class="text-sm text-stone-500 mt-4">Makanan Terbuang</p>

                <div class="flex items-baseline gap-1 mt-1">
                    <h2 class="text-3xl font-black text-stone-900"
                        x-text="formatNumber(totalWaste)">
                    </h2>
                    <span class="text-xs text-stone-500">porsi</span>
                </div>

                <p class="text-xs text-stone-400 mt-2">
                    Makanan yang tidak terjual dan terbuang
                </p>
            </div>

        </div>


        <!-- PRODUCTION OVERVIEW -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            <!-- Production Ratio -->
            <div class="xl:col-span-1 bg-white border border-stone-200/80 rounded-2xl p-5 shadow-sm">

                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-bold text-stone-900">Ringkasan Produksi</h3>
                        <p class="text-xs text-stone-500 mt-1">Distribusi hasil produksi</p>
                    </div>

                    <span class="text-xs font-semibold text-stone-500 bg-stone-100 px-2.5 py-1 rounded-lg">
                        Keseluruhan
                    </span>
                </div>

                <div class="flex items-center justify-center py-5">

                    <div class="relative w-44 h-44">

                        <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">

                            <circle
                                cx="60"
                                cy="60"
                                r="45"
                                fill="none"
                                stroke="#F1F5F9"
                                stroke-width="13"
                            />

                            <circle
                                cx="60"
                                cy="60"
                                r="45"
                                fill="none"
                                stroke="#1B5E3A"
                                stroke-width="13"
                                stroke-linecap="round"
                                :stroke-dasharray="`${regularPercentage * 2.827} 282.7`"
                            />

                            <circle
                                cx="60"
                                cy="60"
                                r="45"
                                fill="none"
                                stroke="#F59E0B"
                                stroke-width="13"
                                stroke-linecap="round"
                                :stroke-dasharray="`${surplusPercentage * 2.827} 282.7`"
                                :stroke-dashoffset="-(regularPercentage * 2.827)"
                            />

                            <circle
                                cx="60"
                                cy="60"
                                r="45"
                                fill="none"
                                stroke="#EF4444"
                                stroke-width="13"
                                stroke-linecap="round"
                                :stroke-dasharray="`${wastePercentage * 2.827} 282.7`"
                                :stroke-dashoffset="-((regularPercentage + surplusPercentage) * 2.827)"
                            />

                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-xs text-stone-400 font-medium">Total Produksi</span>
                            <span class="text-2xl font-black text-stone-900"
                                  x-text="formatNumber(totalProduction)">
                            </span>
                            <span class="text-[10px] text-stone-400">porsi</span>
                        </div>

                    </div>
                </div>

                <div class="space-y-3">

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#1B5E3A]"></span>
                            <span class="text-xs text-stone-600">Terjual Reguler</span>
                        </div>

                        <span class="text-xs font-bold text-stone-800"
                              x-text="regularPercentage + '%'">
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#F59E0B]"></span>
                            <span class="text-xs text-stone-600">Surplus</span>
                        </div>

                        <span class="text-xs font-bold text-stone-800"
                              x-text="surplusPercentage + '%'">
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span>
                            <span class="text-xs text-stone-600">Terbuang</span>
                        </div>

                        <span class="text-xs font-bold text-stone-800"
                              x-text="wastePercentage + '%'">
                        </span>
                    </div>

                </div>
            </div>


            <!-- Production Insight -->
            <div class="xl:col-span-2 bg-white border border-stone-200/80 rounded-2xl p-5 shadow-sm">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">

                    <div>
                        <h3 class="font-bold text-stone-900">Catatan Produksi Terbaru</h3>
                        <p class="text-xs text-stone-500 mt-1">
                            Aktivitas produksi makanan tokomu
                        </p>
                    </div>

                    <div class="flex items-center gap-2">

                        <span class="text-xs font-semibold text-[#1B5E3A] bg-[#EBF5F0] px-3 py-2 rounded-lg">
                            Mengikuti filter riwayat
                        </span>

                    </div>
                </div>

                <div class="space-y-5">

                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="font-semibold text-stone-600">Penjualan Reguler</span>
                            <span class="font-bold text-[#1B5E3A]"
                                  x-text="regularPercentage + '%'">
                            </span>
                        </div>

                        <div class="w-full h-2.5 rounded-full bg-stone-100 overflow-hidden">
                            <div
                                class="h-full rounded-full bg-[#1B5E3A] transition-all duration-500"
                                :style="`width: ${regularPercentage}%`">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="font-semibold text-stone-600">Makanan Surplus</span>
                            <span class="font-bold text-amber-600"
                                  x-text="surplusPercentage + '%'">
                            </span>
                        </div>

                        <div class="w-full h-2.5 rounded-full bg-stone-100 overflow-hidden">
                            <div
                                class="h-full rounded-full bg-[#F59E0B] transition-all duration-500"
                                :style="`width: ${surplusPercentage}%`">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-xs mb-2">
                            <span class="font-semibold text-stone-600">Makanan Terbuang</span>
                            <span class="font-bold text-red-500"
                                  x-text="wastePercentage + '%'">
                            </span>
                        </div>

                        <div class="w-full h-2.5 rounded-full bg-stone-100 overflow-hidden">
                            <div
                                class="h-full rounded-full bg-red-500 transition-all duration-500"
                                :style="`width: ${wastePercentage}%`">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-7 p-4 rounded-xl bg-[#EBF5F0] border border-[#1B5E3A]/10 flex items-start gap-3">

                    <div class="w-9 h-9 shrink-0 rounded-lg bg-white flex items-center justify-center text-[#1B5E3A]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-bold text-[#1B5E3A]">Informasi Produksi</p>
                        <p class="text-xs text-stone-600 mt-1 leading-relaxed">
                            Catat jumlah produksi setiap hari agar kamu dapat memantau jumlah makanan terjual, surplus, dan makanan yang terbuang.
                        </p>
                    </div>

                </div>
            </div>

        </div>


        <!-- PRODUCTION TABLE -->
        <div class="bg-white border border-stone-200/80 rounded-2xl shadow-sm overflow-hidden">

            <div class="flex flex-col sm:flex-row gap-2">

                <!-- PENCARIAN -->
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-stone-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                    <input
                        type="text"
                        x-model="search"
                        placeholder="Cari nama makanan..."
                        class="w-full sm:w-52 pl-9 pr-3 py-2.5 text-xs rounded-xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                    >
                </div>

                <!-- FILTER PERIODE -->
                <select
                    x-model="selectedPeriod"
                    class="px-3 py-2.5 text-xs rounded-xl border border-stone-200 bg-white text-stone-700 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                >
                    <option value="all">Semua Riwayat</option>
                    <option value="today">Hari Ini</option>
                    <option value="last7">7 Hari Terakhir</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                    <option value="lastMonth">Bulan Sebelumnya</option>
                    <option value="custom">Rentang Tanggal</option>
                </select>

            </div>

            <!-- RENTANG TANGGAL KHUSUS -->
            <div
                x-cloak
                x-show="selectedPeriod === 'custom'"
                x-transition
                class="mt-4 flex flex-col sm:flex-row sm:items-center gap-3"
            >
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-stone-600 mb-1">
                        Dari tanggal
                    </label>

                    <input
                        type="date"
                        x-model="dateFrom"
                        :max="dateTo || undefined"
                        class="w-full px-3 py-2.5 text-xs rounded-xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                    >
                </div>

                <div class="flex-1">
                    <label class="block text-xs font-semibold text-stone-600 mb-1">
                        Sampai tanggal
                    </label>

                    <input
                        type="date"
                        x-model="dateTo"
                        :min="dateFrom || undefined"
                        class="w-full px-3 py-2.5 text-xs rounded-xl border border-stone-200 focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                    >
                </div>
            </div>


            <!-- TABLE -->
            <div class="overflow-x-auto">

                <table class="w-full min-w-[850px] text-left">

                    <thead class="bg-[#FAF7F2] border-b border-stone-100">

                        <tr class="text-[11px] uppercase tracking-wider text-stone-500">

                            <th class="px-5 py-4 font-bold">Tanggal</th>
                            <th class="px-5 py-4 font-bold">Nama Makanan</th>
                            <th class="px-5 py-4 font-bold text-center">Diproduksi</th>
                            <th class="px-5 py-4 font-bold text-center">Terjual</th>
                            <th class="px-5 py-4 font-bold text-center">Surplus</th>
                            <th class="px-5 py-4 font-bold text-center">Terbuang</th>
                            <th class="px-5 py-4 font-bold text-center">Aksi</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-stone-100">

                        <template x-for="record in filteredRecords" :key="record.id">

                            <tr class="hover:bg-stone-50/70 transition">

                                <td class="px-5 py-4">
                                    <span class="text-xs font-medium text-stone-600"
                                          x-text="formatDate(record.date)">
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        <div class="w-9 h-9 rounded-lg bg-[#EBF5F0] flex items-center justify-center text-lg shrink-0"
                                             x-text="record.emoji">
                                        </div>

                                        <div>
                                            <p class="text-xs font-bold text-stone-800"
                                               x-text="record.food">
                                            </p>
                                            <p class="text-[10px] text-stone-400"
                                               x-text="record.category">
                                            </p>
                                        </div>

                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-bold text-stone-800"
                                          x-text="formatNumber(record.production)">
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-semibold text-emerald-600"
                                          x-text="formatNumber(record.sold)">
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-semibold text-amber-600"
                                          x-text="formatNumber(record.surplus)">
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <span class="text-xs font-semibold text-red-500"
                                          x-text="formatNumber(record.waste)">
                                    </span>
                                </td>

                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-center gap-1">

                                        <button
                                            type="button"
                                            @click="editRecord(record)"
                                            class="p-2 rounded-lg text-stone-400 hover:text-[#1B5E3A] hover:bg-[#EBF5F0] transition"
                                            title="Edit">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>

                                        </button>

                                        <button
                                            type="button"
                                            @click="deleteRecord(record.id)"
                                            class="p-2 rounded-lg text-stone-400 hover:text-red-600 hover:bg-red-50 transition"
                                            title="Hapus">

                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"/>
                                            </svg>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        </template>

                        <tr x-show="filteredRecords.length === 0">
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="text-3xl mb-2">📋</div>
                                <p class="text-sm font-bold text-stone-700">Tidak ada catatan produksi</p>
                                <p class="text-xs text-stone-400 mt-1">
                                    Coba ubah pencarian atau tambahkan catatan baru.
                                </p>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- TABLE FOOTER -->
            <div class="px-5 py-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-3">

                <p class="text-xs text-stone-500">
                    Menampilkan
                    <span class="font-bold text-stone-800" x-text="filteredRecords.length"></span>
                    catatan produksi
                </p>

                <button
                    type="button"
                    @click="
                        search = '';
                        selectedPeriod = 'all';
                        dateFrom = '';
                        dateTo = '';
                    "
                    class="text-xs font-semibold text-[#1B5E3A] hover:underline">
                    Reset Filter
                </button>

            </div>

        </div>


        <!-- FOOTER NOTE -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4">

            <p class="text-[11px] text-stone-400">
                What's Left — Business Management
            </p>

        </div>

    </main>

    </div>



<!-- ADD / EDIT MODAL -->
<div
    x-cloak
    x-show="modalOpen"
    x-transition.opacity
    class="fixed inset-0 z-[70] bg-stone-900/50 backdrop-blur-sm overflow-y-auto overscroll-contain"
    @click.self="modalOpen = false"
    @keydown.escape.window="modalOpen = false"
>
    <div class="min-h-full flex items-center justify-center p-3 sm:p-5">

        <div
            x-show="modalOpen"
            x-transition
            class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col overflow-hidden"
            style="height: min(850px, calc(100dvh - 2.5rem));"
        >

            <!-- HEADER: TETAP DI ATAS -->
            <div class="px-6 py-5 border-b border-stone-100 flex items-center justify-between shrink-0">
                <div>
                    <h3
                        class="text-xl font-black text-stone-900"
                        x-text="editingId ? 'Edit Catatan Produksi' : 'Tambah Catatan Produksi'"
                    ></h3>

                    <p class="text-sm text-stone-500 mt-1">
                        Lengkapi informasi produksi makanan.
                    </p>
                </div>

                <button
                    type="button"
                    @click="modalOpen = false"
                    class="p-2 rounded-lg hover:bg-stone-100 text-stone-400"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- FORM -->
            <form
                @submit.prevent="saveRecord()"
                class="flex flex-col flex-1 min-h-0 overflow-hidden"
            >

                <!-- AREA YANG BISA DI-SCROLL -->
                <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain custom-scrollbar px-6 py-5 space-y-5">

                    <!-- NAMA MAKANAN -->
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-2">
                            Nama Makanan
                        </label>

                        <select
                            x-model="form.food_id"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-stone-200 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20 focus:border-[#1B5E3A]"
                        >
                            <option value="">Pilih makanan</option>
                            <template x-for="food in foods" :key="food.id">
                                <option :value="food.id" x-text="food.name"></option>
                            </template>
                        </select>
                    </div>

                    <!-- KATEGORI DAN TANGGAL -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-2">
                                Kategori
                            </label>

                            <input
                                type="text"
                                :value="selectedCategory"
                                readonly
                                class="w-full px-4 py-3 rounded-xl border border-stone-200 text-sm bg-stone-50 text-stone-600"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-2">
                                Tanggal Produksi
                            </label>

                            <input
                                type="date"
                                x-model="form.date"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                            >
                        </div>

                    </div>

                    <!-- TOTAL PRODUKSI -->
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-2">
                            Jumlah Produksi
                        </label>

                        <div class="relative">
                            <input
                                type="number"
                                min="1"
                                x-model.number="form.production"
                                required
                                class="w-full px-4 py-3 pr-16 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#1B5E3A]/20"
                            >

                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-stone-400">
                                porsi
                            </span>
                        </div>
                    </div>

                    <!-- PEMBAGIAN PRODUKSI -->
                    <div>
                        <label class="block text-sm font-bold text-stone-700 mb-3">
                            Hasil Produksi
                        </label>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-2">
                                    Terjual Reguler
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    x-model.number="form.sold"
                                    required
                                    class="w-full px-3 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-2">
                                    Surplus
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    x-model.number="form.surplus"
                                    required
                                    class="w-full px-3 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                                >
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-stone-600 mb-2">
                                    Terbuang
                                </label>

                                <input
                                    type="number"
                                    min="0"
                                    x-model.number="form.waste"
                                    required
                                    class="w-full px-3 py-3 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
                                >
                            </div>

                        </div>
                    </div>

                    <!-- VALIDASI -->
                    <div class="rounded-2xl border border-stone-200 overflow-hidden">

                        <div class="px-4 py-3 bg-[#FAF7F2] border-b border-stone-200">
                            <div class="flex items-center justify-between gap-3">

                                <div>
                                    <h4 class="text-sm font-bold text-stone-800">
                                        Pembagian Hasil Produksi
                                    </h4>

                                    <p class="text-xs text-stone-500 mt-1">
                                        Periksa kembali hasil produksi.
                                    </p>
                                </div>

                                <span
                                    class="text-[10px] font-bold px-2.5 py-1 rounded-full"
                                    :class="Number(form.production) > 0 && (Number(form.sold) + Number(form.surplus) + Number(form.waste)) === Number(form.production)
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-amber-100 text-amber-700'"
                                    x-text="Number(form.production) > 0 && (Number(form.sold) + Number(form.surplus) + Number(form.waste)) === Number(form.production) ? 'Sesuai' : 'Perlu diperiksa'"
                                ></span>

                            </div>
                        </div>

                        <div class="p-4 space-y-4">

                            <div class="grid grid-cols-3 gap-3">

                                <div class="rounded-xl bg-emerald-50 p-3">
                                    <p class="text-xs text-emerald-700">Terjual</p>
                                    <p class="text-xl font-black text-emerald-800"
                                       x-text="formatNumber(form.sold || 0)"></p>
                                </div>

                                <div class="rounded-xl bg-amber-50 p-3">
                                    <p class="text-xs text-amber-700">Surplus</p>
                                    <p class="text-xl font-black text-amber-800"
                                       x-text="formatNumber(form.surplus || 0)"></p>
                                </div>

                                <div class="rounded-xl bg-red-50 p-3">
                                    <p class="text-xs text-red-700">Terbuang</p>
                                    <p class="text-xl font-black text-red-700"
                                       x-text="formatNumber(form.waste || 0)"></p>
                                </div>

                            </div>

                            <div class="space-y-2">

                                <div class="flex justify-between text-xs">
                                    <span class="text-stone-500">Hasil tercatat</span>

                                    <strong class="text-stone-800"
                                        x-text="formatNumber(Number(form.sold || 0) + Number(form.surplus || 0) + Number(form.waste || 0)) + ' / ' + formatNumber(form.production || 0) + ' porsi'">
                                    </strong>
                                </div>

                                <div class="w-full h-2 rounded-full bg-stone-100 overflow-hidden">
                                    <div
                                        class="h-full rounded-full transition-all"
                                        :class="(Number(form.sold || 0) + Number(form.surplus || 0) + Number(form.waste || 0)) > Number(form.production || 0) ? 'bg-red-500' : 'bg-[#1B5E3A]'"
                                        :style="`width: ${Math.min(100, Number(form.production || 0) > 0 ? ((Number(form.sold || 0) + Number(form.surplus || 0) + Number(form.waste || 0)) / Number(form.production)) * 100 : 0)}%`"
                                    ></div>
                                </div>

                                <p
                                    class="text-xs"
                                    :class="(Number(form.sold || 0) + Number(form.surplus || 0) + Number(form.waste || 0)) === Number(form.production || 0) && Number(form.production || 0) > 0 ? 'text-emerald-700' : 'text-amber-700'"
                                    x-text="Number(form.production || 0) === 0
                                        ? 'Masukkan jumlah produksi terlebih dahulu.'
                                        : (Number(form.sold || 0) + Number(form.surplus || 0) + Number(form.waste || 0)) === Number(form.production || 0)
                                            ? 'Pembagian hasil produksi sudah lengkap.'
                                            : 'Masih ada selisih ' + formatNumber(Math.abs(Number(form.production || 0) - Number(form.sold || 0) - Number(form.surplus || 0) - Number(form.waste || 0))) + ' porsi.'"
                                ></p>

                            </div>

                        </div>
                    </div>

                    <!-- ERROR VALIDATION -->
                    <div
                        x-show="formError"
                        x-cloak
                        class="p-3 rounded-xl bg-red-50 border border-red-100 text-xs text-red-600"
                        x-text="formError"
                    ></div>

                </div>

                <!-- FOOTER: TIDAK IKUT DI-SCROLL -->
                <div class="shrink-0 px-6 py-4 border-t border-stone-100 bg-white flex items-center justify-end gap-3">

                    <button
                        type="button"
                        @click="modalOpen = false"
                        class="px-5 py-3 rounded-xl text-sm font-bold text-stone-600 bg-stone-100 hover:bg-stone-200 transition"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-3 rounded-xl text-sm font-bold text-white bg-[#1B5E3A] hover:bg-[#14472C] transition"
                    >
                        <span x-text="editingId ? 'Simpan Perubahan' : 'Simpan Catatan'"></span>
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>



    <!-- DELETE CONFIRMATION MODAL -->
    <div
        x-cloak
        x-show="deleteModalOpen"
        x-transition.opacity
        class="fixed inset-0 z-[80] flex items-center justify-center p-4 bg-stone-900/50 backdrop-blur-sm"
        @click.self="deleteModalOpen = false"
    >

        <div class="bg-white w-full max-w-sm rounded-2xl p-6 shadow-2xl text-center">

            <div class="w-14 h-14 mx-auto rounded-full bg-red-50 text-red-500 flex items-center justify-center mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h12"/>
                </svg>
            </div>

            <h3 class="font-black text-lg text-stone-900">
                Hapus Catatan Produksi?
            </h3>

            <p class="text-xs text-stone-500 mt-2 leading-relaxed">
                Catatan yang dihapus tidak dapat dikembalikan.
            </p>

            <div class="flex gap-3 mt-6">

                <button
                    type="button"
                    @click="deleteModalOpen = false"
                    class="flex-1 py-3 rounded-xl bg-stone-100 text-stone-700 text-xs font-bold hover:bg-stone-200">
                    Batal
                </button>

                <button
                    type="button"
                    @click="confirmDelete()"
                    class="flex-1 py-3 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700">
                    Ya, Hapus
                </button>

            </div>

        </div>

    </div>

</div>


<!-- ALPINE DATA -->
<script>
function productionRecordsPage() {
    return {

        mobileSidebarOpen: false,

        search: '',
        selectedPeriod: 'all',
        dateFrom: '',
        dateTo: '',

        modalOpen: false,
        deleteModalOpen: false,

        editingId: null,
        deletingId: null,

        formError: '',

        toast: {
            show: false,
            message: ''
        },

        defaultDate: @js(now()->toDateString()),
        foods: @js($foodOptions),

        form: {
            food_id: '',
            date: @js(now()->toDateString()),
            production: 0,
            sold: 0,
            surplus: 0,
            waste: 0
        },

        records: @js($records),

        get selectedFood() {
            return this.foods.find(food => String(food.id) === String(this.form.food_id));
        },

        get selectedCategory() {
            return this.selectedFood ? this.selectedFood.category : 'Pilih makanan terlebih dahulu';
        },

        get filteredRecords() {
            let result = [...this.records];

            // Pencarian nama makanan
            if (this.search.trim() !== '') {
                result = result.filter(record =>
                    record.food.toLowerCase().includes(this.search.toLowerCase())
                );
            }

            // Gunakan tanggal lokal, bukan UTC
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            const formatLocalDate = (date) => {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            };

            const todayString = formatLocalDate(today);

            // Hari ini
            if (this.selectedPeriod === 'today') {
                result = result.filter(record => record.date === todayString);
            }

            // 7 hari terakhir termasuk hari ini
            if (this.selectedPeriod === 'last7') {
                const start = new Date(today);
                start.setDate(start.getDate() - 6);

                result = result.filter(record =>
                    record.date >= formatLocalDate(start) &&
                    record.date <= todayString
                );
            }

            // Minggu ini, dimulai Senin
            if (this.selectedPeriod === 'week') {
                const start = new Date(today);
                const day = start.getDay();

                start.setDate(start.getDate() - (day === 0 ? 6 : day - 1));

                result = result.filter(record =>
                    record.date >= formatLocalDate(start) &&
                    record.date <= todayString
                );
            }

            // Bulan ini
            if (this.selectedPeriod === 'month') {
                const start = new Date(today.getFullYear(), today.getMonth(), 1);

                result = result.filter(record =>
                    record.date >= formatLocalDate(start) &&
                    record.date <= todayString
                );
            }

            // Bulan sebelumnya
            if (this.selectedPeriod === 'lastMonth') {
                const start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const end = new Date(today.getFullYear(), today.getMonth(), 0);

                result = result.filter(record =>
                    record.date >= formatLocalDate(start) &&
                    record.date <= formatLocalDate(end)
                );
            }

            // Rentang tanggal khusus
            if (this.selectedPeriod === 'custom') {
                if (this.dateFrom) {
                    result = result.filter(record => record.date >= this.dateFrom);
                }

                if (this.dateTo) {
                    result = result.filter(record => record.date <= this.dateTo);
                }
            }

            // Urutkan dari tanggal terbaru
            return result.sort((a, b) => b.date.localeCompare(a.date));
        },

        get totalProduction() {
            return this.filteredRecords.reduce((sum, record) => sum + Number(record.production), 0);
        },

        get totalRegularSold() {
            return this.filteredRecords.reduce((sum, record) => sum + Number(record.sold), 0);
        },

        get totalSurplus() {
            return this.filteredRecords.reduce((sum, record) => sum + Number(record.surplus), 0);
        },

        get totalWaste() {
            return this.filteredRecords.reduce((sum, record) => sum + Number(record.waste), 0);
        },

        get regularPercentage() {
            return this.totalProduction
                ? Math.round((this.totalRegularSold / this.totalProduction) * 100)
                : 0;
        },

        get surplusPercentage() {
            return this.totalProduction
                ? Math.round((this.totalSurplus / this.totalProduction) * 100)
                : 0;
        },

        get wastePercentage() {
            return this.totalProduction
                ? Math.round((this.totalWaste / this.totalProduction) * 100)
                : 0;
        },

        formatNumber(number) {
            return Number(number || 0).toLocaleString('id-ID');
        },

        formatDate(date) {
            return new Date(date + 'T00:00:00').toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        },

        showToast(message, type = 'success') {
            this.$dispatch('show-toast', { msg: message, type });
        },

        resetForm() {
            this.form = {
                food_id: '',
                date: this.defaultDate,
                production: 0,
                sold: 0,
                surplus: 0,
                waste: 0
            };

            this.formError = '';
            this.editingId = null;
        },

        openAddModal() {
            this.resetForm();
            this.modalOpen = true;
        },

        editRecord(record) {
            this.editingId = record.id;
            this.form = {
                food_id: record.food_id,
                date: record.date,
                production: Number(record.production),
                sold: Number(record.sold),
                surplus: Number(record.surplus),
                waste: Number(record.waste)
            };

            this.formError = '';
            this.modalOpen = true;
        },

        async saveRecord() {
            const production = Number(this.form.production);
            const sold = Number(this.form.sold);
            const surplus = Number(this.form.surplus);
            const waste = Number(this.form.waste);

            if (!this.form.food_id || !this.form.date) {
                this.formError = 'Pilih makanan dan tanggal produksi terlebih dahulu.';
                return;
            }

            if ([production, sold, surplus, waste].some(value => !Number.isInteger(value) || value < 0)) {
                this.formError = 'Semua jumlah harus berupa bilangan bulat non-negatif.';
                return;
            }

            if (sold + surplus + waste > production) {
                this.formError = 'Jumlah terjual + surplus + terbuang tidak boleh melebihi jumlah produksi.';
                return;
            }

            const payload = {
                food_id: Number(this.form.food_id),
                record_date: this.form.date,
                production_quantity: production,
                regular_sold_quantity: sold,
                surplus_quantity: surplus,
                waste_quantity: waste
            };

            const editing = Boolean(this.editingId);
            const url = editing
                ? `/business/production-records/${this.editingId}`
                : '/business/production-records';

            try {
                const response = await fetch(url, {
                    method: editing ? 'PUT' : 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok) {
                    const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
                    this.formError = firstError || result.message || 'Data produksi gagal disimpan.';
                    return;
                }

                this.modalOpen = false;
                this.showToast(result.message || 'Data produksi berhasil disimpan.');
                window.location.reload();
            } catch (error) {
                this.formError = 'Tidak dapat terhubung ke server. Silakan coba kembali.';
            }
        },

        deleteRecord(id) {
            this.deletingId = id;
            this.deleteModalOpen = true;
        },

        async confirmDelete() {
            if (!this.deletingId) return;

            try {
                const response = await fetch(`/business/production-records/${this.deletingId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    this.showToast(result.message || 'Catatan produksi gagal dihapus.');
                    return;
                }

                this.deleteModalOpen = false;
                this.deletingId = null;
                this.showToast(result.message || 'Catatan produksi berhasil dihapus.');
                window.location.reload();
            } catch (error) {
                this.showToast('Tidak dapat terhubung ke server. Silakan coba kembali.');
            }
        },

    };
}
</script>

@endsection
