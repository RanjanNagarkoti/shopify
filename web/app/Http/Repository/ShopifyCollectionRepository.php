<?php

namespace App\Http\Repository;

use App\Models\Session;
use App\Models\ShopifyCollection;

class ShopifyCollectionRepository
{
    public function updateOrSaveCollection(array $collection, $session, $collection_type)
    {
        $session = Session::where('session_id', $session->getId())->first();

        ShopifyCollection::updateOrCreate(
            ['shopify_collection_id' => $collection['id']],
            [
                'shopify_collection_id' => $collection['id'],
                'handle' => $collection['handle'],
                'title' => $collection['title'],
                'type' => $collection_type,
                'session_id' => $session->id
            ]
        );
    }
}
