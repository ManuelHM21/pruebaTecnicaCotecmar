<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FormularioController;
use App\Models\Proyecto;
use App\Models\Bloque;  
use App\Models\Pieza;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('peso-real');
    })->name('dashboard');

    Route::get('/peso-real', [FormularioController::class, 'create'])->name('peso-real');
    Route::post('/peso-real', [FormularioController::class, 'store']);
    Route::get('/bloques/{proyecto_id}', [FormularioController::class, 'getBloques']);
    Route::get('/piezas/{bloque_id}', [FormularioController::class, 'getPiezas']);
    Route::get('/peso-teorico/{pieza_id}', [FormularioController::class, 'getPesoTeorico']);
    Route::get('/reporte/pendientes', [FormularioController::class, 'reportePendientes'])->name('reporte.pendientes');
    Route::get('/reporte/grafico', [FormularioController::class, 'reporteGrafico'])->name('reporte.grafico');

    Route::get('/crear-proyecto', function () {
        $proyectos = Proyecto::withCount(['bloques', 'piezas'])
                           ->with(['bloques' => function($query) {
                               $query->withCount('piezas');
                           }])
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return Inertia::render('CrearProyecto', [
            'proyectos' => $proyectos
        ]);
    })->name('crear-proyecto');
    Route::post('/proyectos', [FormularioController::class, 'storeProyecto']);
    Route::put('/proyectos/{id}', [FormularioController::class, 'updateProyecto']);
    Route::delete('/proyectos/{id}', [FormularioController::class, 'deleteProyecto']);

    Route::get('/crear-bloque', function () {
        return Inertia::render('CrearBloque', [
            'proyectos' => Proyecto::all(),
            'bloques' => Bloque::with('proyecto')->withCount('piezas')->orderBy('created_at', 'desc')->get()
        ]);
    })->name('crear-bloque');
    Route::post('/bloques', [FormularioController::class, 'storeBloque']);
    Route::put('/bloques/{id}', [FormularioController::class, 'updateBloque']);
    Route::delete('/bloques/{id}', [FormularioController::class, 'deleteBloque']);

    Route::get('/crear-pieza', function () {
        return Inertia::render('CrearPieza', [
            'bloques' => Bloque::with('proyecto')->get(),
            'piezas' => Pieza::with('bloque.proyecto')->orderBy('created_at', 'desc')->get()
        ]);
    })->name('crear-pieza');
    Route::post('/piezas', [FormularioController::class, 'storePieza']);
    Route::put('/piezas/{id}', [FormularioController::class, 'updatePieza']);
    Route::delete('/piezas/{id}', [FormularioController::class, 'deletePieza']);
});
