<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller // enhiretance gikan sa base Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Redirect para sa dashboard depende sa role

        // kani para sa admin
        if ($user->role === 'admin') {
            return redirect()->route('for_admin.dashboard');
        } 
        // kani para sa cashier
        elseif ($user->role === 'cashier') {
            return redirect()->route('for_cashier.dashboard');
        } 
        //kani para sa inventory staff
        elseif ($user->role === 'inventory_staff') {
            return redirect()->route('for_inventory_staff.dashboard');
        }
        
    }
}
