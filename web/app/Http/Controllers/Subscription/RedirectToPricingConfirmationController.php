<?php

namespace App\Http\Controllers\Subscription;

use Shopify\Context;
use Shopify\Rest\Admin2024_07\RecurringApplicationCharge;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RedirectToPricingConfirmationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $session = $request->get('shopifySession');
        $hostName = Context::$HOST_NAME;
        $shop = $session->getShop();
        $host = base64_encode("$shop/admin");
        $returnUrl = "https://$hostName?shop={$shop}&host=$host";

        $recurring_application_charge = new RecurringApplicationCharge($session);
        $recurring_application_charge->name = Config::get('shopify.billing.chargeName');
        $recurring_application_charge->price = Config::get('shopify.billing.amount');
        $recurring_application_charge->return_url = $returnUrl;
        $recurring_application_charge->trial_days = 0;
        $recurring_application_charge->save(
            true,
        );

        return response()->json([
            'confirmation_url' => $recurring_application_charge->confirmation_url,
        ], 200);
    }
}
