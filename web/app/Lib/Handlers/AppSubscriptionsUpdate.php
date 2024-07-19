<?php

declare(strict_types=1);

namespace App\Lib\Handlers;

use Illuminate\Support\Facades\Bus;
use App\Jobs\ImportShopifyProductJob;
use App\Jobs\SyncShopifyCollectionsJob;
use Shopify\Webhooks\Handler;
use Shopify\Context;
use App\Models\Session;

class AppSubscriptionsUpdate implements Handler
{
    public function handle(string $topic, string $shop, array $body): void
    {
        $sessionID = Session::select('session_id')->where('shop', 'datingappscript.myshopify.com')->first();
        $session = Context::$SESSION_STORAGE->loadSession($sessionID->session_id);

        Bus::chain([
            new SyncShopifyCollectionsJob($session, "smart_collections"),
            new SyncShopifyCollectionsJob($session, "custom_collections"),
            new ImportShopifyProductJob($session),
        ])->dispatch();
    }
}
