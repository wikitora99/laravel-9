<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductsCategory as Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{ Validator, Storage };


class CategoryController extends Controller
{
    
    public function __construct() 
    {
        //
    }

    public function index() 
    {
        if ( auth()->user()->role_id == 3) {
            return redirect()->back()->with('error', 'Halaman tidak ditemukan !');
        }

        $categories = Category::orderBy('id', 'asc')->get();
        return view('product.categories.index',compact('categories'));
    }

    public function create() 
    {    
        if ( auth()->user()->role_id == 3) {
            return redirect()->back()->with('error', 'Halaman tidak ditemukan !');
        }

        return view('product.categories.create');
    }

    public function store(Request $request)
    {
        if ( auth()->user()->role_id == 3) {
            return redirect()->back()->with('error', 'Halaman tidak ditemukan !');
        }

        $rules = [
        'name' => 'required|string|max:255|unique:products_categories,name',
        'desc' => 'required|string|max:255'
        ];

        $eMessage = [
        'name.required' => 'Kategori tidak boleh kosong !',
        'desc.required' => 'Deskripsi tidak boleh kosong !', 
        ];
        
        $validator = Validator::make($request->all(), $rules, $eMessage);

        if ($validator->fails()){
        return redirect()->back()->with('warning', $validator->errors()->first());
        }
        
        $category = new Category;
        $category->name = $request->name;
        $category->desc = $request->desc;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambah !');
    }

    public function edit(Category $category) 
    {
        if ( auth()->user()->role_id == 3) {
            return redirect()->back()->with('error', 'Halaman tidak ditemukan !');
        }

        return view('product.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ( auth()->user()->role_id == 3) {
            return redirect()->back()->with('error', 'Halaman tidak ditemukan !');
        }
        
        if ($request->name != $category->name){
            $name_rules = ['required', 'string', 'max:255', 'unique:products_categories,name'];
        }else{
            $name_rules = ['required'];
        }

        $rules = [
        'name' => $name_rules,
        'desc' => 'required|string|max:255'
        ];

        $eMessage = [
        'name.required' => 'Kategori tidak boleh kosong !', 
        'desc.required' => 'Deskripsi tidak boleh kosong !', 
        ];
        
        $validator = Validator::make($request->all(), $rules, $eMessage);

        if ($validator->fails()){
        return redirect()->back()->with('warning', $validator->errors()->first());
        }

        $category->update([
            'name' => $request->name,
            'desc' => $request->desc
        ]);

        return redirect()->route('category.index')->with('success','Kategori berhasil diubah !');
    }

    public function destroy(Category $category)
    {
        if ( auth()->user()->role_id == 3) {
            return redirect()->back()->with('error', 'Halaman tidak ditemukan !');
        }
        
        $products = $category->products->count();

        if ($products > 0){
            return redirect()->back()->with('info', "Tidak dapat menghapus Kategori, terdapat {$products} Produk di dalam Kategori ini !");
        }

        $category->delete();
        
        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
