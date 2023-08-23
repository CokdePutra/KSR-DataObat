<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('main.category.index', compact('categories'));
    }

    public function create()
    {
        return view('main.category.create');
    }

    public function store(CategoryRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
            ];

            Category::create($data);

            return redirect()->route('category.index')->with([
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
        $category = Category::findOrFail($id);

        return view('main.category.edit', compact('category'));
    }

    public function update(CategoryRequest $request)
    {
        try {
            $category = Category::findOrFail($request->id);
            $data = [
                'name' => $request->name,
                'is_active' => $request->status,
            ];

            $category->update($data);

            return redirect()->route('category.index')->with([
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
}
