<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShopifyCollectionResource;
use App\Models\ShopifyCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopifyCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $shopifyCollection = ShopifyCollection::paginate(10);

        return ShopifyCollectionResource::collection($shopifyCollection);
    }

    public function store()
    {

    }
}
