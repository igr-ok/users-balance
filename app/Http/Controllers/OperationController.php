<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OperationController extends Controller
{

    public function index()
    {
        return view('history');
    }

    public function data(Request $request)
    {
        $user = $request->user();

        $query = $user->operations();
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            // Для Postgres используем ilike для нечувствительности к регистру
            $query->where('description', 'ilike', '%' . $search . '%');
        }
        
        $sort = $request->input('sort', 'desc');
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'desc';
        }

        $query->orderBy('created_at', $sort);
        
        $operations = $query->paginate(10);

        return response()->json($operations);
    }
}
