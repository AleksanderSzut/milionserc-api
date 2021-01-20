<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param User $model
     * @return View
     */
    public function index(Order $model)
    {
        return view('orders.index', ['orders' => $model->paginate(15)]);
    }
}
