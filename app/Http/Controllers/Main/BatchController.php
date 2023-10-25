<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BatchController extends Controller
{
    public function medicineDetail($id)
    {
        $medicine = Medicine::with('category')->where('id', $id)->firstOrFail();

        $currentStock = $medicine->batches->where('medicine_id', $id)->sum('stock');

        return response()->json([
            'medicine' => $medicine,
            'currentStock' => $currentStock
        ]);
    }

    public function index()
    {
        $batches = Batch::all();
        return view('main.batch.index', compact('batches'));
    }

    public function create()
    {
        $medicines = Medicine::where('is_active', true)->pluck('name', 'id')->toArray();
        return view('main.batch.create', compact('medicines'));
    }

    public function store(BatchRequest $request)
    {
        try {
            $uuid = Str::uuid();
            $uuidWithoutHyphens = str_replace('-', '', $uuid->toString());
            $data = [
                'user_id' => auth()->user()->id,
                'medicine_id' => $request->medicine_id,
                'batch_number' => 'BATCH-' . substr($uuidWithoutHyphens, 0, 20),
                'quantity' => $request->quantity,
                'stock' => $request->quantity,
                'expired_date' => $request->expired_date,
            ];

            Batch::create($data);

            return redirect()->route('batch.index')->with([
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

    public function edit($id)
    {
        $medicines = Medicine::where('is_active', true)->pluck('name', 'id')->toArray();
        $batch = Batch::findOrFail($id);
        return view('main.batch.edit', compact('medicines', 'batch'));
    }

    public function update(BatchRequest $request)
    {
        try {
            $batch = Batch::findOrFail($request->id);
            $uuid = Str::uuid();
            $uuidWithoutHyphens = str_replace('-', '', $uuid->toString());
            $data = [
                'medicine_id' => $request->medicine_id,
                'batch_number' => 'BATCH-' . substr($uuidWithoutHyphens, 0, 20),
                'quantity' => $request->quantity,
                'stock' => $request->quantity,
                'expired_date' => $request->expired_date,
                'is_active' => $request->status,
            ];

            $batch->update($data);

            return redirect()->route('batch.index')->with([
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

    public function delete($id)
    {
        try {
            $batch = Batch::find($id);
            $batch->delete();

            return redirect()->route('batch.index')->with([
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

    public function print(Request $request)
    {
        try {
            $start = $request->start_date;
            $end = $request->end_date;

            $batches = Batch::with('medicine', 'user')->whereBetween('expired_date', [$start, $end])->get();
            // $stock = Batch::whereBetween('expired_date', [$start, $end])
            //             ->sum('stock');

            $pdf = \PDF::loadview('main.batch.print', compact('batches', 'start', 'end'));
            $pdf->setPaper('a3', 'landscape');
            return $pdf->download('BatchesReport - ' . time() . '.pdf');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
