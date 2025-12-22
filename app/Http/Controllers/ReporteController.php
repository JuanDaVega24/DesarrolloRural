<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function filtrar(Request $request)
    {
        $query = Encuesta::query();

        if ($request->filled('municipio')) {
            $query->where('municipio_nacimiento', $request->municipio);
        }

        if ($request->filled('genero')) {
            $query->where('genero', $request->genero);
        }

        $resultados = $query->get();
        return view('reportes.resultados', compact('resultados'));
    }

    public function exportarExcel()
    {
        // pendiente implementar con Laravel Excel
    }

    public function exportarPDF()
    {
        // pendiente con DOMPDF
    }
}
