<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Food;
use App\Models\ProductionRecord;
use App\Http\Controllers\Concerns\ResolvesBusiness;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductionRecordController extends Controller
{
    use ResolvesBusiness;

    public function page(Request $request)
    {
        $business = $this->getBusiness($request);

        $foods = $business->foods()
            ->active()
            ->orderBy('food_name')
            ->get();

        $foodOptions = $foods->map(function ($food) {
            return [
                'id' => $food->id,
                'name' => $food->food_name,
                'category' => $food->category,
            ];
        })->values();

        $records = ProductionRecord::with('food')
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->orderByDesc('record_date')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'food_id' => $record->food_id,
                    'food' => $record->food?->food_name ?? 'Makanan',
                    'category' => $record->food?->category ?? 'Other',
                    'emoji' => match ($record->food?->category) {
                        'Main Course' => '🍱',
                        'Bread & Pastry' => '🥐',
                        'Snack' => '🍪',
                        'Beverage' => '🥤',
                        'Dessert' => '🍰',
                        default => '🍽️',
                    },
                    'date' => $record->record_date->toDateString(),
                    'production' => (int) $record->production_quantity,
                    'sold' => (int) $record->regular_sold_quantity,
                    'surplus' => (int) $record->surplus_quantity,
                    'waste' => (int) $record->waste_quantity,
                ];
            })
            ->values();

        return view('business.production-records', compact('business', 'foods', 'foodOptions', 'records'));
    }

    public function index(Request $request)
    {
        $business = $this->getBusiness($request);

        $records = ProductionRecord::whereHas('food', function ($query) use ($business) {
            $query->where('business_id', $business->id);
        })->get();

        return response()->json([
            'message' => 'Data produksi berhasil diambil.',
            'data' => $records,
        ], 200);
    }

    public function store(Request $request)
    {
        $business = $this->getBusiness($request);

        $validated = $request->validate([
            'food_id' => 'required|integer',
            'record_date' => 'required|date|before_or_equal:today',
            'production_quantity' => 'required|integer|min:1',
            'regular_sold_quantity' => 'required|integer|min:0',
            'surplus_quantity' => 'required|integer|min:0',
            'waste_quantity' => 'required|integer|min:0',
        ]);

        if (
            $validated['regular_sold_quantity'] +
            $validated['surplus_quantity'] +
            $validated['waste_quantity'] > $validated['production_quantity']
        ) {
            return response()->json([
                'message' => 'Jumlah terjual reguler + surplus + terbuang tidak boleh melebihi jumlah produksi.',
            ], 422);
        }

        $food = $business->foods()->active()->find($validated['food_id']);

        if (!$food) {
            return response()->json([
                'message' => 'Makanan tidak ditemukan atau bukan milik Business Anda.',
            ], 404);
        }

        if (ProductionRecord::where('food_id', $food->id)->whereDate('record_date', $validated['record_date'])->exists()) {
            return response()->json([
                'message' => 'Catatan produksi untuk makanan ini pada tanggal tersebut sudah ada. Edit catatan yang sudah ada.',
            ], 422);
        }

        unset($validated['food_id']);

        $record = $food->productionRecords()->create($validated);

        return response()->json([
            'message' => 'Data produksi berhasil ditambahkan.',
            'data' => $record->load('food'),
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $record = ProductionRecord::where('id', $id)
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Data produksi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail data produksi berhasil diambil.',
            'data' => $record,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $record = ProductionRecord::where('id', $id)
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Data produksi tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'food_id' => 'sometimes|required|integer',
            'record_date' => 'sometimes|required|date|before_or_equal:today',
            'production_quantity' => 'sometimes|required|integer|min:1',
            'regular_sold_quantity' => 'sometimes|required|integer|min:0',
            'surplus_quantity' => 'sometimes|required|integer|min:0',
            'waste_quantity' => 'sometimes|required|integer|min:0',
        ]);

        $foodId = $validated['food_id'] ?? $record->food_id;
        $food = $business->foods()->active()->find($foodId);

        if (!$food) {
            return response()->json([
                'message' => 'Makanan tidak ditemukan atau bukan milik Business Anda.',
            ], 404);
        }

        $production = $validated['production_quantity'] ?? (int) $record->production_quantity;
        $sold = $validated['regular_sold_quantity'] ?? (int) $record->regular_sold_quantity;
        $surplus = $validated['surplus_quantity'] ?? (int) $record->surplus_quantity;
        $waste = $validated['waste_quantity'] ?? (int) $record->waste_quantity;

        if ($sold + $surplus + $waste > $production) {
            return response()->json([
                'message' => 'Jumlah terjual reguler + surplus + terbuang tidak boleh melebihi jumlah produksi.',
            ], 422);
        }

        $effectiveDate = Carbon::parse($validated['record_date'] ?? $record->record_date)->toDateString();

        if (
            (isset($validated['record_date']) || isset($validated['food_id'])) &&
            ProductionRecord::where('food_id', $food->id)
                ->whereDate('record_date', $effectiveDate)
                ->where('id', '!=', $record->id)
                ->exists()
        ) {
            return response()->json([
                'message' => 'Catatan produksi untuk makanan ini pada tanggal tersebut sudah ada.',
            ], 422);
        }

        unset($validated['food_id']);
        $validated['food_id'] = $food->id;

        $record->update($validated);

        return response()->json([
            'message' => 'Data produksi berhasil diperbarui.',
            'data' => $record->fresh('food'),
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $business = $this->getBusiness($request);

        $record = ProductionRecord::where('id', $id)
            ->whereHas('food', function ($query) use ($business) {
                $query->where('business_id', $business->id);
            })
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Data produksi tidak ditemukan.',
            ], 404);
        }

        $record->delete();

        return response()->json([
            'message' => 'Data produksi berhasil dihapus.',
        ], 200);
    }
}
