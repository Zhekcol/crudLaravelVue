<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class EstudiantesController extends Controller
{
    public function index()
    {
        //$url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::get('http://127.0.0.1:8000/api/estudiantes/');
        $estudiantes = $response->json();

        return Inertia::render('Estudiantes/Index', compact('estudiantes'));
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
            'semestre' => 'required'
        ]);
        
        $response = Http::post('http://127.0.0.1:8000/api/estudiantes/', [
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'semestre' => $request->semestre
        ]);
        
        return redirect('estudiantes');
    }

    public function update(Request $request, $idEstudiante)
    {
        $request->validate([
            'documento' => 'required',
            'nombre' => 'required',
            'telefono' => 'required',
            'email' => 'required|email',
            'direccion' => 'required',
            'ciudad' => 'required',
            'semestre' => 'required'
        ]);

        $response = Http::put('http://127.0.0.1:8000/api/estudiantes/'.$idEstudiante, [
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'semestre' => $request->semestre,
        ]);

        return redirect('estudiantes');
    }

    public function destroy($idEstudiante)
    {
        $response = Http::delete('http://127.0.0.1:8000/api/estudiantes/' . $idEstudiante);

        return redirect('estudiantes');
    }

    public function graphica(){
        $response = Http::get('http://127.0.0.1:8000/api/estudiantes/');
        $data = $response->json();
        return Inertia::render('Estudiantes/Graphic', compact('data'));
    }

    public function reportes(){
        $response = Http::get('http://127.0.0.1:8000/api/estudiantes/');
        $estudiantes = $response->json();
        $respuesta = Http::get('http://127.0.0.1:8000/api/profesores/');
        $profesores = $respuesta->json();
        return Inertia::render('Estudiantes/Reports', compact('estudiantes', 'profesores'));
    }
}
