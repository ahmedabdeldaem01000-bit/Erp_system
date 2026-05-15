<?php

namespace App\Service\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class CategoryService
{

    public function index()
    {
        $category = Category::get();
        return $category;
    }





    public function store($request)
    {




        $category = Category::create([
            'name' => $request->name,
        ]);



        return $category;
    }

    public function update($request, $id)
    {
        $category = Category::findOrFail($id);


        $category->update([

            'name' => $request->name,
        ]);

        return $category;
    }
    public function show(string $id)
    {
        $category = Category::findOrFail($id);
        return $category;
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);



        $category->delete();

        return true;
    }
}