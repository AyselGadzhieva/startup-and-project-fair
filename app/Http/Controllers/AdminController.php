<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminController extends Controller
{
    public function users()
    {
        $users_count = User::all()->count();
        $users = User::orderBy('created_at')->get();

        $users = User::role('writer')->get(); // Returns only users with the role 'writer'
        return response()->json([
            'customers_count' => $users_count,
            'customers' => $users,
        ]);
    }
}
