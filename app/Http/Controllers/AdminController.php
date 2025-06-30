<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function customers()
    {
        // Return customers view or data
        return view('admin.customers');
    }

    public function transaction()
    {
        // Return transaction view or data
        return view('admin.transaction');
    }

    public function product()
    {
        // Return product view or data
        return view('admin.product');
    }

    public function orderData()
    {
        // Return order data view or data
        return view('admin.order_data');
    }

    public function orderHistory()
    {
        // Return order history view or data
        return view('admin.order_history');
    }
}
