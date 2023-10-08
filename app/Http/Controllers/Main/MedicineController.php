<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    private $defaultImage = 'assets/uploads/medicines/default.jpg';

    public function index() {
        $medicines = Medicine::with('category')->get();
        return view('main.medicine.index', compact('medicines'));
    }

    public function create()
    {
        $units = [
            // 'Strip', 'Botol', 'Kotak', 'Dos', 'Satuan', 'Tube', 'Ampul', 'Vial'
            'Botol', 'Tablet', 'Strip', 'Sachet', 'Kapsul', 'Box'
        ];
        $categories = Category::where('is_active', true)->pluck('name', 'id')->prepend('Choose Category...', '')->toArray();
        return view('main.medicine.create', compact('categories', 'units'));
    }

    public function store(MedicineRequest $request)
    {
        try {
            $data = [
                'user_id' => auth()->user()->id,
                'category_id' => $request->category_id,
                'name' => $request->name,
                'medicine_code' => $request->medicine_code,
                'unit' => $request->unit != 'Lainnya' ? $request->unit : $request->other_unit,
                'description' => $request->description,
            ];

            if($request->hasFile('image')) {
                $fileExtension = $request->file('image')->getClientOriginalExtension();
                $fileName = str_replace(' ', '', $request->name) . '-' . time() . '.' . $fileExtension;
                $savePath = 'assets/uploads/medicines';

                if(!file_exists($savePath)) {
                    mkdir($savePath, 666, true);
                }

                $request->file('image')->move($savePath, $fileName);
                $data['image'] = $savePath . '/' . $fileName;
            }

            Medicine::create($data);

            return redirect()->route('medicine.index')->with([
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
        $units = [
            // 'Strip', 'Botol', 'Kotak', 'Dos', 'Satuan', 'Tube', 'Ampul', 'Vial'
            'Botol', 'Tablet', 'Strip', 'Sachet', 'Kapsul', 'Box'
        ];
        $categories = Category::where('is_active', true)->pluck('name', 'id')->prepend('Choose Category...', '')->toArray();
        $medicine = Medicine::findOrFail($id);

        return view('main.medicine.edit', compact('medicine', 'units', 'categories'));
    }

    public function update(MedicineRequest $request)
    {
        try {
            $medicine = Medicine::findOrFail($request->id);
            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'stock' => $request->stock,
                'unit' => $request->unit != 'Lainnya' ? $request->unit : $request->other_unit,
                'description' => $request->description,
                // 'expired_date' => $request->expired_date,
                'is_active' => $request->status,
            ];

            if($request->hasFile('image')) {
                // if($medicine->image != $this->defaultImage) {
                //     unlink($medicine->image);
                // }
                $fileExtension = $request->file('image')->getClientOriginalExtension();
                $fileName = str_replace(' ', '', $request->name) . '-' . time() . '.' . $fileExtension;
                $savePath = 'assets/uploads/medicines';

                if(!file_exists($savePath)) {
                    mkdir($savePath, 666, true);
                }

                $request->file('image')->move($savePath, $fileName);
                $data['image'] = $savePath . '/' . $fileName;
            }

            $medicine->update($data);

            return redirect()->route('medicine.index')->with([
                'status' => 'success',
                'message' => 'Data updated successfully',
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
            $medicine = Medicine::find($id);
            $medicine->delete();

            return redirect()->route('medicine.index')->with([
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

    public function print(Request $request) {
        try {
            $start = $request->start_date;
            $end = date('Y-m-d H:i:s', strtotime($request->end_date . ' +1 day'));

            $medicines = Medicine::with('category')->whereBetween('created_at', [$start, $end])->get();

            $pdf = \PDF::loadview('main.medicine.print', compact('medicines', 'start', 'end'));
            $pdf->setPaper('a3', 'landscape');
            return $pdf->download('MedicinesReport - ' . time() . '.pdf');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
