<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Balance;
use App\Models\Operation;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function data()
    {
        $user = Auth::user();

        return response()->json([

            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],

            'balance' => $user->balance->amount ?? 0,
            'operations' => $user->operations()->latest()->take(5)->get()
        ]);
    }
}
