<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\App;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppController extends Controller
{
    public function edit(Request $request, App $app)
    {
        $validatedRequest = $request->validate([
            'id' => 'required|string|unique:app,id',
            'title' => 'required|string|unique:app,id'
        ]);
    }
}