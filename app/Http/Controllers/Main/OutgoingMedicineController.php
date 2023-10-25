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
use PDF;

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

    public function edit($id)
    {
        $outgoingMedicines = OutgoingMedicine::with('details')->where('id', $id)->firstOrFail();
        return view('main.outgoing.edit')->with([
            'outgoingMedicines' => $outgoingMedicines
        ]);
    }

    public function medicineOnDatabase($id) {
        $outgoing = OutgoingMedicine::with('details.batch.medicine')->where('id', $id)->firstOrFail();

        return response()->json($outgoing);
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
                    $query->where('is_active', true)
                            ->where('stock', '>', 0);
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
                'outgoing_date' => $request->out_date,
                'user_id' => auth()->user()->id,
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
                    'stock' => $batch->stock - $request->quantity[$key]
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

    public function update(OutgoingMedicineRequest $request)
    {
        // dd($request->all());
        try {
            $outgoingMedicineDetail = OutgoingMedicineDetail::where('outgoing_medicine_id',$request->outgoing_medicine_id)->get();
            foreach ($outgoingMedicineDetail as $detail) {
                $batch = Batch::where('id', $detail->batch_id)->first();
                $batch->update([
                    'stock' => $batch->stock + $detail->quantity
                ]);
            }

            // delete data before update
            OutgoingMedicineDetail::where('outgoing_medicine_id', $request->outgoing_medicine_id)->delete();


            foreach($request->batch_number as $key => $value) {
                $batch = Batch::where('batch_number', $value)->first();
                OutgoingMedicineDetail::create([
                    'outgoing_medicine_id' => $request->outgoing_medicine_id,
                    'medicine_id' => $batch->medicine_id,
                    'batch_id' => $batch->id,
                    'quantity' => $request->quantity[$key]
                ]);

                $batch->update([
                    'stock' => $batch->stock - $request->quantity[$key]
                ]);
            }

            return redirect()->route('outgoing.index')->with([
                'status' => 'success',
                'message' => 'Data saved successfully',
                'title' => 'Success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => $e->getMessage(),
                // 'message' => 'Something went wrong',
                'title' => 'Failed'
            ])->withInput();
        }
    }

    public function detail($outgoingId)
    {
        $outgoingMedicineDetail = OutgoingMedicineDetail::with(['medicine', 'batch'])->where('outgoing_medicine_id', $outgoingId)->get();

        return response()->json($outgoingMedicineDetail);
    }

    public function print(Request $request) {
        try {
            $start = $request->start_date;
            $endPlusOne = date('Y-m-d H:i:s', strtotime($request->end_date . ' +1 day'));
            $end = $request->end_date;

            $data = OutgoingMedicine::whereBetween('outgoing_date', [$start, $endPlusOne])->get();
            // $stock = Batch::whereBetween('expired_date', [$start, $end])
            //             ->sum('stock');

            $pdf = \PDF::loadview('main.outgoing.print', compact('data', 'start', 'end'));
            $pdf->setPaper('a3', 'landscape');
            return $pdf->download('MedicineOutgoingReport - ' . time() . '.pdf');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $outgoing = OutgoingMedicine::find($id);

            $outgoingMedicineDetail = OutgoingMedicineDetail::where('outgoing_medicine_id',$outgoing->id)->get();

            foreach ($outgoingMedicineDetail as $detail) {
                $batch = Batch::where('id', $detail->batch_id)->first();
                $batch->update([
                    'stock' => $batch->stock + $detail->quantity
                ]);
            }

            $outgoing->delete();

            return redirect()->route('outgoing.index')->with([
                'status' => 'success',
                'message' => 'Data deleted successfully',
                'title' => 'Success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => $e->getMessage(),
                'title' => 'Failed'
            ]);
        }
    }
}
