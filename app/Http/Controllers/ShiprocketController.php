<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShiprocketController extends Controller
{
    private $apiBaseUrl = 'https://apiv2.shiprocket.in/v1/external';
    private $apiKey;
    private $apiSecret;
    private $token;

    public function __construct()
    {
        $this->apiKey = env('SHIPROCKET_API_KEY');
        $this->apiSecret = env('SHIPROCKET_API_SECRET');
        $this->token = $this->getAuthToken();
    }

    private function getAuthToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl . '/auth/login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'email' => $this->apiKey,
                'password' => $this->apiSecret,
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        } else {
            $data = json_decode($response, true);
            return $data['token'] ?? null;
        }
    }

    private function apiRequest($endpoint, $method = 'GET', $data = null)
    {
        $curl = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiBaseUrl . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ));

        if ($method === 'POST' && $data !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return response()->json(['error' => $err], 500);
        } else {
            return json_decode($response, true);
        }
    }

    public function province()
    {
        // Shiprocket API does not provide provinces endpoint directly,
        // so we can return a static list or fetch states from India data.
        // For demo, returning a static list of states (simplified).
        $states = [
            ['state_id' => 1, 'state_name' => 'Delhi'],
            ['state_id' => 2, 'state_name' => 'Maharashtra'],
            ['state_id' => 3, 'state_name' => 'Karnataka'],
            // Add more states as needed
        ];
        return response()->json($states);
    }

    public function city($state_id)
    {
        // Shiprocket API provides cities endpoint filtered by state_id
        $endpoint = '/settings/cities';
        $cities = $this->apiRequest($endpoint);

        if (isset($cities['data'])) {
            $filteredCities = array_filter($cities['data'], function ($city) use ($state_id) {
                return $city['state_id'] == $state_id;
            });
            return response()->json(array_values($filteredCities));
        } else {
            return response()->json(['error' => 'Unable to fetch cities'], 500);
        }
    }

    public function cost($origin, $destination, $quantity, $courier)
    {
        // Shiprocket API requires weight in grams and courier code
        // Assuming quantity is number of items, each 300 grams as before
        $weight = (int)$quantity * 300;

        $endpoint = '/courier/serviceability/';
        $data = [
            'pickup_postcode' => $origin,
            'delivery_postcode' => $destination,
            'weight' => $weight,
            'cod' => false,
            'courier_code' => $courier,
        ];

        // Shiprocket API does not have a direct cost endpoint like RajaOngkir,
        // but serviceability endpoint returns available couriers and rates.
        // We will call serviceability and filter by courier_code.

        $serviceability = $this->apiRequest($endpoint, 'POST', $data);

        if (isset($serviceability['data'])) {
            $services = array_filter($serviceability['data'], function ($service) use ($courier) {
                return strtolower($service['courier_code']) == strtolower($courier);
            });
            return response()->json(array_values($services));
        } else {
            return response()->json(['error' => 'Unable to fetch shipping cost'], 500);
        }
    }
}
