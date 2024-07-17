<?php

namespace App\Http\Repository;

use Shopify\Clients\Rest;
use Illuminate\Support\Facades\Log;

class ShopifyProductRepository
{
    /**
     * Parses the link header and returns an array of links with their relation types as keys.
     *
     * @param string $linkHeader The link header string.
     * @return array An array of links with their relation types as keys.
     */
    public function parseLinkHeader($linkHeader)
    {
        $links = [];
        $parts = explode(',', $linkHeader);

        foreach ($parts as $part) {
            $section = explode(';', $part);
            $url = trim($section[0], ' <>');
            $rel = false;

            if (isset($section[1])) {
                $rel = trim(str_replace('rel=', '', $section[1]), ' "');
            }

            if ($rel) {
                $links[$rel] = $url;
            }
        }

        return $links;
    }


    /**
     * Checks if there is a next page available.
     *
     * @param string $link The link header string.
     * @return int Returns 1 if there is a next page, 0 otherwise.
     */
    public function hasNextPage($link)
    {
        $links = $this->parseLinkHeader($link);
        return isset($links['next']) ? 1 : 0;
    }


    /**
     * Checks if there is a previous page available.
     *
     * @param string $link The link header string.
     * @return int Returns 1 if there is a previous page, 0 otherwise.
     */
    public function hasPreviousPage($link)
    {
        $links = $this->parseLinkHeader($link);
        return isset($links['previous']) ? 1 : 0;
    }


    /**
     * Throttles the request to Shopify if we have reached the API call limit.
     *
     * @param Session $session The session object.
     * @return void
     */
    public function throttleRequestIfNeeded($session)
    {
        $header = $this->getShopifyAPICallLimit($session);

        if ($header) {
            list($callsMade, $limit) = explode('/', $header);
            Log::debug('Calls made: ' . $callsMade . ' Limit: ' . $limit);
            $remainingCalls = $limit - $callsMade;

            if ($remainingCalls <= 5) {
                sleep(5);
            }
        }
    }


    /**
     * Retrieves the Shopify API call limit for the current session.
     *
     * @param Session $session The session object.
     * @return string|null The API call limit as a string, or null if not found.
     */
    private function getShopifyAPICallLimit($session)
    {
        $client = new Rest($session->getShop(), $session->getAccessToken());
        $response = $client->get('products', [], ['limit' => 1]);
        $headers = $response->getHeaders();

        if (isset($headers['x-shopify-shop-api-call-limit'][0])) {
            return $headers['x-shopify-shop-api-call-limit'][0];
        }

        return null;
    }
}
