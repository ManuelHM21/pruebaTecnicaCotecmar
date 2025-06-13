<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FormularioController;
use App\Models\Proyecto;
use App\Models\Bloque;  

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
});

Route::get('/peso-real', [FormularioController::class, 'create'])->name('peso-real');
Route::post('/peso-real', [FormularioController::class, 'store']);
Route::get('/bloques/{proyecto_id}', [FormularioController::class, 'getBloques']);
Route::get('/piezas/{bloque_id}', [FormularioController::class, 'getPiezas']);
Route::get('/peso-teorico/{pieza_id}', [FormularioController::class, 'getPesoTeorico']);
Route::get('/reporte/pendientes', [FormularioController::class, 'reportePendientes'])->name('reporte.pendientes');
Route::get('/reporte/grafico', [FormularioController::class, 'reporteGrafico'])->name('reporte.grafico');

Route::get('/crear-proyecto', fn() => Inertia::render('CrearProyecto'))->name('crear-proyecto');
Route::post('/proyectos', [FormularioController::class, 'storeProyecto']);

Route::get('/crear-bloque', function () {
    return Inertia::render('CrearBloque', [
        'proyectos' => Proyecto::all()
    ]);
})->name('crear-bloque');
Route::post('/bloques', [FormularioController::class, 'storeBloque']);

Route::get('/crear-pieza', function () {
    return Inertia::render('CrearPieza', [
        'bloques' => Bloque::all()
    ]);
})->name('crear-pieza');
Route::post('/piezas', [FormularioController::class, 'storePieza']);
