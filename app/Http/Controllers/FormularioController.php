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

    public function updateProyecto(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($request->only('nombre'));

        return redirect()->route('crear-proyecto')->with('success', 'Proyecto actualizado exitosamente.');
    }

    public function deleteProyecto($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        if ($proyecto->bloques()->count() > 0) {
            return redirect()->route('crear-proyecto')->with('error', 'No se puede eliminar el proyecto porque tiene bloques asociados.');
        }

        $proyecto->delete();
        return redirect()->route('crear-proyecto')->with('success', 'Proyecto eliminado exitosamente.');
    }

    public function updateBloque(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'proyecto_id' => 'required|exists:proyectos,id',
        ]);

        $bloque = Bloque::findOrFail($id);
        $bloque->update($request->only('nombre', 'proyecto_id'));

        return redirect()->route('crear-bloque')->with('success', 'Bloque actualizado exitosamente.');
    }

    public function deleteBloque($id)
    {
        $bloque = Bloque::findOrFail($id);
        
        if ($bloque->piezas()->count() > 0) {
            return redirect()->route('crear-bloque')->with('error', 'No se puede eliminar el bloque porque tiene piezas asociadas.');
        }

        $bloque->delete();
        return redirect()->route('crear-bloque')->with('success', 'Bloque eliminado exitosamente.');
    }

    public function updatePieza(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'peso_teorico' => 'required|numeric|min:0',
            'bloque_id' => 'required|exists:bloques,id',
        ]);

        $pieza = Pieza::findOrFail($id);
        $pieza->update($request->only('nombre', 'peso_teorico', 'bloque_id'));

        return redirect()->route('crear-pieza')->with('success', 'Pieza actualizada exitosamente.');
    }

    public function deletePieza($id)
    {
        $pieza = Pieza::findOrFail($id);
        
        if ($pieza->estado === 'Fabricado') {
            return redirect()->route('crear-pieza')->with('error', 'No se puede eliminar una pieza que ya fue fabricada.');
        }

        $pieza->delete();
        return redirect()->route('crear-pieza')->with('success', 'Pieza eliminada exitosamente.');
    }
}
