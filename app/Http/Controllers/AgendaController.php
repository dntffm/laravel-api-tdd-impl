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
        ->when($request->has('month'), function($query) use($request){
            $query->whereMonth('started_at', $request->month);
        })
        ->when($request->has('year'), function($query) use($request) {
            $query->whereYear('started_at', $request->year);
        })
        ->orderBy('started_at', 'desc')
        ->get();

        return $this->sendResponse('Fetched successfully!', $data);
    }
}
