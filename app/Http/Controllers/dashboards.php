<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class dashboards extends Controller
{
    public function admin_dashboard()
    {
        return view('for_admin.dashboard');
    }

     public function cashier_dashboard()
    {
        return view('for_cashier.dashboard');
    }

     public function inventory_staff_dashboard()
    {
        return view('for_inventory_staff.dashboard');
    }
}