<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class EstudiantesController extends Controller
{
    public function index()
    {
        $url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::get($url.'/estudiantes');
        $estudiantes = $response->json();
        return Inertia::render('Estudiantes/Index', compact('estudiantes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::post($url.'/estudiantes', [
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'semestre' => $request->semestre,
            'profesores' => $request->profesores,
            'asignaturas' => $request->asignaturas,
        ]);
        $response->save();
        
        return redirect('estudiantes');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request)
    {
        $url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::put($url.'/estudiantes', [
            'documento' => $request->documento,
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'semestre' => $request->semestre,
            'profesores' => $request->profesores,
            'asignaturas' => $request->asignaturas,
        ]);
        $response->update();
        
        return redirect('estudiantes');
    }

    public function destroy($idEstudiante)
    {
        $url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::delete($url.'/estudiantes/' . $idEstudiante);
        return redirect('estudiantes');
    }

    public function graphica(){
        $url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::get($url.'/estudiantes');
        $data = $response->json();
        return Inertia::render('Estudiantes/Graphic', compact('data'));
    }

    public function reportes(){
        $url = env('URL_SERVER_API', 'http://127.0.0.1');
        $response = Http::get($url.'/estudiantes');
        $data = $response->json();
        return Inertia::render('Estudiantes/Reports', compact('data'));
    }
}
