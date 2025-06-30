<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    // Example fixed rates for demonstration
    private $baseRate = 50; // base shipping cost
    private $perItemRate = 20; // cost per item

    public function cost($origin, $destination, $quantity, $courier)
    {
        // Simple shipping cost calculation logic
        // You can enhance this with real distance or zone based logic

        $quantity = (int)$quantity;
        if ($quantity <= 0) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        // For demo, ignore origin, destination, courier and calculate cost as base + per item * quantity
        $shippingCost = $this->baseRate + ($this->perItemRate * $quantity);

        return response()->json(['shipping_cost' => $shippingCost]);
    }
}
