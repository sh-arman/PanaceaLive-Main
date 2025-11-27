<?php

namespace Panacea\Http\Controllers;

use Illuminate\Http\Request;
use Panacea\Order;

class OrderController extends Controller
{
    public function index()
    {
        $data = [];
        $data['order'] = Order::orderBy('created_at', 'desc')->simplePaginate(15);
        return view('admin.order.index', $data);
    }
}
