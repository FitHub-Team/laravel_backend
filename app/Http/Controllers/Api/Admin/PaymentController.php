<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payment');
    }
}
