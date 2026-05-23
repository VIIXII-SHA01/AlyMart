<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Show the account deactivated page.
     */
    public function deactivated()
    {
        return view('account.deactivated');
    }

    /**
     * Show the contact support page.
     */
    public function contactSupport()
    {
        return view('account.contact-support');
    }
}
