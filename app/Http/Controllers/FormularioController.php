<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;
use App\Models\Bloque;
use App\Models\Pieza;
use Inertia\Inertia;

class FormularioController extends Controller
{
    public function create(Request $request)
    {
        $piezas = Pieza::with(['bloque.proyecto'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('RegistrarFabricacion', [
            'piezas' => $piezas,
            'success' => session('success')
        ]);
    }

    public function getBloques($proyecto_id)
    {
        return Bloque::where('proyecto_id', $proyecto_id)->get();
    }

    public function getPiezas($bloque_id)
    {
        return Pieza::where('bloque_id', $bloque_id)
            ->where('estado', 'Pendiente')
            ->get();
    }

    public function getPesoTeorico($pieza_id)
    {
        $pieza = Pieza::find($pieza_id);
        return $pieza ? $pieza->peso_teorico : null;
    }

    public function store(Request $request)
    {
        $request->validate([
            'pieza_id' => 'required|exists:piezas,id',
            'peso_real' => 'required|numeric',
        ]);

        $pieza = Pieza::find($request->pieza_id);
        $pieza->update([
            'peso_real' => $request->peso_real,
            'estado' => 'Fabricado',
            'fecha_registro' => now(),
            'registrado_por' => auth()->user()->name,
        ]);

        return redirect()->back()->with('success', 'Registro guardado correctamente');
    }

    public function reportePendientes()
    {
        $proyectos = Proyecto::with(['bloques.piezas' => function($q) {
            $q->where('estado', 'Pendiente');
        }])->get();

        return Inertia::render('ReportePendientes', [
            'proyectos' => $proyectos
        ]);
    }

    public function reporteGrafico()
    {
        $proyectos = Proyecto::with(['bloques.piezas'])->get();
        return Inertia::render('ReporteGrafico', [
            'proyectos' => $proyectos
        ]);
    }

    public function storeProyecto(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:proyectos,id',
            'nombre' => 'required',
        ]);
        Proyecto::create($request->only('id', 'nombre'));
        return redirect()->back()->with('success', 'Proyecto creado correctamente');
    }

    public function storeBloque(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:bloques,id',
            'nombre' => 'required',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);
        Bloque::create($request->only('id', 'nombre', 'proyecto_id'));
        return redirect()->back()->with('success', 'Bloque creado correctamente');
    }

    public function storePieza(Request $request)
    {
        $request->validate([
            'id' => 'required|unique:piezas,id',
            'nombre' => 'required',
            'peso_teorico' => 'required|numeric',
            'bloque_id' => 'required|exists:bloques,id',
        ]);

        Pieza::create([
            'id' => $request->id,
            'nombre' => $request->nombre,
            'peso_teorico' => $request->peso_teorico,
            'bloque_id' => $request->bloque_id,
            'estado' => 'Pendiente',
            'fecha_registro' => now(),
            'registrado_por' => auth()->user()->name,
        ]);

        return redirect()->back()->with('success', 'Pieza creada correctamente');
    }
}
