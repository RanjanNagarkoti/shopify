<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Resources\ShopifyProductResource;
use App\Models\ShopifyProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopifyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $shopifyProduct = ShopifyProduct::with('shopifyProductVariations')->paginate(10);
        return ShopifyProductResource::collection($shopifyProduct);
    }
}
