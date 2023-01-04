<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        $data = $user->agendas()
        ->whereMonth('started_at', $request->month)
        ->whereYear('started_at', $request->year)
        ->orderBy('started_at', 'desc')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
