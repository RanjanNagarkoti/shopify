<?php

namespace App\Http\Controllers\Subscription;

use Illuminate\Support\Facades\Bus;
use App\Jobs\ImportShopifyProductJob;
use App\Jobs\SyncShopifyCollectionsJob;
use App\Http\Resources\SubscribtionDetailResource;
use App\Lib\EnsureBilling;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetSubscriptionDetailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return SubscribtionDetailResource|bool
     */
    public function __invoke(Request $request): SubscribtionDetailResource | bool
    {
        $session = $request->get('shopifySession');

        $config = config('shopify.billing');

        $subscription = EnsureBilling::check($session, $config);

        if ($subscription[0] !== true) {
            return false;
        }

        Bus::chain([
            new SyncShopifyCollectionsJob($session, "smart_collections"),
            new SyncShopifyCollectionsJob($session, "custom_collections"),
            new ImportShopifyProductJob($session),
        ])->dispatch();

        return new SubscribtionDetailResource((object) $config);
    }
}
