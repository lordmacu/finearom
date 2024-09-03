<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:product list', ['only' => ['index']]);
        $this->middleware('can:product create', ['only' => ['create', 'store']]);
        $this->middleware('can:product edit', ['only' => ['edit', 'update']]);
        $this->middleware('can:product delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $products_query = Product::query();
        $clients = Client::all();
        $productsAll = Product::all();


        if ($request->has('search')) {
            $search = $request->input('search');
            $products_query->where(function ($query) use ($search) {
                $query->where('product_name', 'LIKE', "%$search%")
                      ->orWhere('code', 'LIKE', "%$search%");
            });
        }

        if ($request->has('client_id') && $request->input('client_id') != '') {
            $products_query->where('client_id', $request->input('client_id'));
        }

        if ($request->has('product_id') && $request->input('product_id') != '') {
            $products_query->where('id', $request->input('product_id'));
        }

        if ($sort_by = $request->query('sort_by')) {
            $sort_order = $request->query('sort_order', 'asc');
            $products_query->orderBy($sort_by, $sort_order);
        } else {
            $products_query->latest();
        }

        $products = $products_query->paginate(config('admin.paginate.per_page'))
                                   ->appends($request->except('page'))
                                   ->onEachSide(config('admin.paginate.each_side'));

        return view('admin.product.index', compact('products', 'clients','productsAll'));
    }

    public function ajaxProducts(Request $request)
{
    $search = $request->input('search');

    $products = Product::query();

    // Si existe una búsqueda, filtramos los productos
    if ($search) {
        $products->where(function ($query) use ($search) {
            $query->where('product_name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search); // Esto permite buscar directamente por ID si es necesario
        });
    }

    // Seleccionamos los campos necesarios para Select2
    $result = $products->select('id', 'product_name as text') // 'text' es requerido por Select2
                       ->limit(10) // Limita el número de resultados
                       ->get();

    // Retornamos el resultado como un JSON
    return response()->json($result);
}


    public function create()
    {
        $clients = Client::all();
        return view('admin.product.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:products,code',
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'client_id' => 'required|exists:clients,id',
        ]);

        Product::create([
            'code' => $request->code,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'client_id' => $request->client_id,
        ]);

        $cacheKey = "client_products_{$request->client_id}";
        Cache::forget($cacheKey);

        return redirect()->route('admin.product.index')
                         ->with('message', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $clients = Client::all();
        return view('admin.product.edit', compact('product', 'clients'));
    }

    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:products,code,' . $product->id,
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'client_id' => 'required|exists:clients,id',
        ]);

        $product->update($request->all());

        $cacheKey = "client_products_{$request->client_id}";
        Cache::forget($cacheKey);


        return redirect()->route('admin.product.index')
                         ->with('message', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.product.index')
                         ->with('message', 'Product deleted successfully');
    }
}
