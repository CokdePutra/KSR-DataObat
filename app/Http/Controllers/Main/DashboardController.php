<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\OutgoingMedicineDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('main.dashboard.index');
    }

    public function chartInOutMedicines(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $dataForMonths = [];

        // Query for incoming data
        $incoming = Batch::whereBetween('created_at', [$start, $end])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(quantity) as total_quantity')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        foreach ($incoming as $result) {
            $year = $result->year;
            $month = $result->month;
            $totalQuantity = $result->total_quantity;

            // Initialize the month's entry if it doesn't exist
            if (!isset($dataForMonths[$year])) {
                $dataForMonths[$year] = [];
            }

            $dataForMonths[$year][$month]['incoming'] = $totalQuantity;
        }

        // Query for outgoing data
        $outgoing = DB::table('outgoing_medicine_details')
            ->join('outgoing_medicines', 'outgoing_medicine_details.outgoing_medicine_id', '=', 'outgoing_medicines.id')
            ->whereBetween('outgoing_medicines.outgoing_date', [$start, $end])
            ->selectRaw('YEAR(outgoing_medicines.outgoing_date) as year, MONTH(outgoing_medicines.outgoing_date) as month, SUM(outgoing_medicine_details.quantity) as total_quantity')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        foreach ($outgoing as $result) {
            $year = $result->year;
            $month = $result->month;
            $totalQuantity = $result->total_quantity;

            // Initialize the month's entry if it doesn't exist
            if (!isset($dataForMonths[$year])) {
                $dataForMonths[$year] = [];
            }

            // Add outgoing data for the existing month
            if (!isset($dataForMonths[$year][$month])) {
                $dataForMonths[$year][$month] = [];
            }

            $dataForMonths[$year][$month]['outgoing'] = $totalQuantity;
        }

        // Fill in default values for missing months and set them to 0
        $currentDate = $start->copy();
        $endDate = $end->copy();
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;

            if (!isset($dataForMonths[$year])) {
                $dataForMonths[$year] = [];
            }

            if (!isset($dataForMonths[$year][$month])) {
                $dataForMonths[$year][$month] = [];
            }

            if (!isset($dataForMonths[$year][$month]['incoming'])) {
                $dataForMonths[$year][$month]['incoming'] = 0;
            }

            if (!isset($dataForMonths[$year][$month]['outgoing'])) {
                $dataForMonths[$year][$month]['outgoing'] = 0;
            }

            $currentDate->addMonth();
        }


        return response()->json($dataForMonths);
    }

    public function pieChartInOutByCategory(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        if ($request->category == 'incoming') {
            $results = DB::table('batches')
                ->join('medicines', 'batches.medicine_id', '=', 'medicines.id')
                ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->whereBetween('batches.created_at', [$start, $end])
                ->select('medicines.name as medicine', DB::raw('SUM(batches.quantity) as total_quantity'))
                // ->select('categories.name as category', DB::raw('SUM(batches.quantity) as total_quantity'))
                ->groupBy('medicine')
                ->get();
        } else {
            $results = DB::table('outgoing_medicine_details')
                ->join('medicines', 'outgoing_medicine_details.medicine_id', '=', 'medicines.id')
                // ->join('categories', 'medicines.category_id', '=', 'categories.id')
                ->join('outgoing_medicines', 'outgoing_medicine_details.outgoing_medicine_id', '=', 'outgoing_medicines.id')
                ->whereBetween('outgoing_medicines.outgoing_date', [$start, $end])
                // ->select('category.name as category', DB::raw('SUM(outgoing_medicine_details.quantity) as total_quantity'))
                ->select('medicines.name as medicine', DB::raw('SUM(outgoing_medicine_details.quantity) as total_quantity'))
                ->groupBy('medicine')
                ->get();
        }

        foreach ($results as $item) {
            $data[] = [
                'medicine' => $item->medicine,
                'total_quantity' => $item->total_quantity,
            ];
        }

        // $stock = Batch::whereBetween('created_at', [$start, $end])
        $stock = Batch::where('expired_date', '>', now())
                        ->sum('stock');

        return response()->json([
            'data' => $data,
            'stock' => $stock
        ]);
    }
}
