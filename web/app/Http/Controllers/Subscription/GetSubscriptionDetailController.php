<?php

namespace App\Http\Controllers\Subscription;

use App\Jobs\ImportSmartShopifyCollectionJob;
use App\Jobs\ImportCustomShopifyCollectionJob;
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

        ImportCustomShopifyCollectionJob::dispatch($session);
        ImportSmartShopifyCollectionJob::dispatch($session);

        return new SubscribtionDetailResource((Object) $config);
    }
}
