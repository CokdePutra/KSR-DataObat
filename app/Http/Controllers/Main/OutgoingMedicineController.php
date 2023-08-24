<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\OutgoingMedicineRequest;
use App\Models\Batch;
use App\Models\Medicine;
use App\Models\OutgoingMedicine;
use App\Models\OutgoingMedicineDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutgoingMedicineController extends Controller
{
    public function index()
    {
        $outgoingMedicines = OutgoingMedicine::all();
        return view('main.outgoing.index', compact('outgoingMedicines'));
    }

    public function create()
    {
        return view('main.outgoing.create');
    }

    public function medicineSearch($keyword)
    {
        $category = explode('-', $keyword)[0];
        $keyword = (explode('-', $keyword)[1] == "" ? 'empty' : explode('-', $keyword)[1]);

        if ($category == 'searchDetail') {
            $medicine = Medicine::where('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('medicine_code', 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('category', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('is_active', true);
                })
                ->whereHas('batches', function ($query) {
                    $query->where('is_active', true);
                })
                ->with(['category', 'batches'])
                ->where('is_active', true)
                ->get();
        }
        return response()->json($medicine);
    }

    public function store(OutgoingMedicineRequest $request)
    {
        try {
            DB::beginTransaction();
            $outgoingMedicine = OutgoingMedicine::create([
                'description' => $request->description,
                'outgoing_date' => $request->out_date
            ]);

            foreach($request->batch_number as $key => $value) {
                $batch = Batch::where('batch_number', $value)->first();
                OutgoingMedicineDetail::create([
                    'outgoing_medicine_id' => $outgoingMedicine->id,
                    'medicine_id' => $batch->medicine_id,
                    'batch_id' => $batch->id,
                    'quantity' => $request->quantity[$key]
                ]);

                $batch->update([
                    'quantity' => $batch->quantity - $request->quantity[$key]
                ]);
            }

            DB::commit();
            return redirect()->route('outgoing.index')->with([
                'status' => 'success',
                'message' => 'Data saved successfully',
                'title' => 'Success'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with([
                'status' => 'error',
                'message' => $e->getMessage(),
                // 'message' => 'Something went wrong',
                'title' => 'Failed'
            ])->withInput();
        }
    }
}
