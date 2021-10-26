<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $variants = Variant::with('productVariant')->get();
        // $variants = Variant::with('productVariant')->select('productVariant.variant_id', 'productVariant.variant')->distinct()->get()->groupBy('productVariant.variant_id');
        // $variants = ProductVariant::with('variant')->select('variant_id', 'variant')->distinct()->get()->groupBy('variant_id');
        $products = Product::with('productVariantPrice.productVariantOne', 'productVariantPrice.productVariantTwo', 'productVariantPrice.productVariantThree')->paginate(2);
        return view('products.index', compact(
            'products', $products,
            'variants', $variants
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function image(Request $request)
    {
        $fileName = $request->file === null ? '---' : $request->file->storeAs('image/product', time() . '.' . $request->file->getClientOriginalExtension(), 'public');
        return response()->json(['file' => $fileName]);
    }

    public function store(Request $request)
    {
        //return response()->json($request);
        $productData = $request->except('product_image', 'product_variant', 'product_variant_prices');
        $productId = Product::create($productData);

        $productImage['file_path'] = $request->get('product_image');
        $productImage['product_id'] = $productId->id;

        ProductImage::create($productImage);

        foreach($request->get('product_variant') as $productVariant) {
            $productVariantData['variant_id'] = $productVariant['option'];
            foreach($productVariant['tags'] as $tag) {
                $productVariantData['variant'] = $tag;
                $productVariantData['product_id'] = $productId->id;
                $productVariantId = ProductVariant::Create($productVariantData);
            }
        }

        foreach($request->get('product_variant_prices') as $productVariantPrice ) {
            $productVariantTitle = explode('/', $productVariantPrice['title']);
            $productVariantPriceData['product_variant_one'] = $productVariantTitle[0] === '' ? NULL : ProductVariant::where('variant', $productVariantTitle[0])->first()->id;
            $productVariantPriceData['product_variant_two'] = $productVariantTitle[1] === '' ? NULL : ProductVariant::where('variant', $productVariantTitle[1])->first()->id;
            $productVariantPriceData['product_variant_three'] = $productVariantTitle[2] === '' ? NULL : ProductVariant::where('variant', $productVariantTitle[2])->first()->id;
            $productVariantPriceData['price'] = $productVariantPrice['price'];
            $productVariantPriceData['stock'] = $productVariantPrice['stock'];
            $productVariantPriceData['product_id'] = $productId->id;
            ProductVariantPrice::create($productVariantPriceData);
        }
        return response()->json('Product Update Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
