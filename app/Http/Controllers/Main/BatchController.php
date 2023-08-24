<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\Medicine;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function medicineDetail($id)
    {
        $medicine = Medicine::with('category')->where('id', $id)->firstOrFail();

        return response()->json($medicine);
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
            $data = [
                'user_id' => auth()->user()->id,
                'medicine_id' => $request->medicine_id,
                'batch_number' => generateBatchNumber(),
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
            $data = [
                'medicine_id' => $request->medicine_id,
                'batch_number' => generateBatchNumber(),
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
}
