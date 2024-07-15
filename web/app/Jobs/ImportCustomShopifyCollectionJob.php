<?php

namespace App\Jobs;

use Shopify\Rest\Admin2024_07\CustomCollection;
use App\Models\Session;
use App\Models\ShopifyCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCustomShopifyCollectionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed $session
     */
    public $session;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $since = ShopifyCollection::where('type', 'custom')->latest()->first();

        $session = Session::where('session_id', $this->session->getId())->first();

        $customCollections = CustomCollection::all(
            $this->session, // Session
            [], // Url Ids
            array_filter([
                "since_id" => $since ? $since->shopify_collection_id : null
            ]),
        );

        foreach ($customCollections as $smartCollection) {
            ShopifyCollection::create([
                'shopify_collection_id' => $smartCollection->id,
                'handle' => $smartCollection->handle,
                'title' => $smartCollection->title,
                'type' => 'custom',
                'session_id' => $session->id
            ]);
        }
    }
}
