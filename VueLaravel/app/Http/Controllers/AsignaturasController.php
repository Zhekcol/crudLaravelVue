<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class AsignaturasController extends Controller
{
    public function index()
    {
        $response = Http::get('http://127.0.0.1:8000/api/asignaturas/');
        $asignaturas = $response->json();
        return Inertia::render('Asignaturas/Index', compact('asignaturas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'creditos' => 'required',
            'areaConocimiento' => 'required',
            'seleccion' => 'required',
        ]);

        $response = Http::post('http://127.0.0.1:8000/api/asignaturas/', [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'creditos' => $request->creditos,
            'areaConocimiento' => $request->areaConocimiento,
            'seleccion' => $request->seleccion,
        ]);
        
        return redirect('asignaturas');
    }

    public function update(Request $request, $idAsignatura)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'creditos' => 'required',
            'areaConocimiento' => 'required',
            'seleccion' => 'required',
        ]);
        
        $response = Http::put('http://127.0.0.1:8000/api/asignaturas/'.$idAsignatura, [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'creditos' => $request->creditos,
            'areaConocimiento' => $request->areaConocimiento,
            'seleccion' => $request->seleccion,
        ]);
        
        return redirect('asignaturas');
    }

    public function destroy($idAsignatura)
    {
        $response = Http::delete('http://127.0.0.1:8000/api/asignaturas/' . $idAsignatura);

        return redirect('asignaturas');
    }
}
