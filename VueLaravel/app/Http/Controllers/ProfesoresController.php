<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ProfesoresController extends Controller
{
    public function index()
    {
        $response = Http::get('http://127.0.0.1:8000/api/profesores/');
        $profesores = $response->json();
        return Inertia::render('Profesores/Index', compact('profesores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required',
            'nombre' => 'required',
            'telefono' => 'required',
            'email' => 'required|email',
            'direccion' => 'required',
            'ciudad' => 'required',
        ]);

        $response = Http::post('http://127.0.0.1:8000/api/profesores/', [
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
        ]);
        
        return redirect('profesores');
    }

    public function update(Request $request, $idProfesor)
    {
        $request->validate([
            'documento' => 'required',
            'nombre' => 'required',
            'telefono' => 'required',
            'email' => 'required|email',
            'direccion' => 'required',
            'ciudad' => 'required',
        ]);

        $response = Http::put('http://127.0.0.1:8000/api/profesores/'.$idProfesor, [
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
        ]);
        
        return redirect('profesores');
    }

    public function destroy($idProfesor)
    {
        $response = Http::delete('http://127.0.0.1:8000/api/profesores/' . $idProfesor);

        return redirect('profesores');
    }
}
