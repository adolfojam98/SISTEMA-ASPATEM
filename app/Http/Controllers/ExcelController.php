<?php

namespace App\Http\Controllers;
use App\Exports\FechaRankingExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Usuario;
use App\Fecha;
use App\Torneo;

class ExcelController extends Controller
{
    public function getFechaRankingExcel(Request $request)
    {   
        $fecha = Fecha::whereId($request->id)->first();
        $torneo = Torneo::whereId($fecha->torneo_id)->first();
        $type = 'xlsx';
        $excelName = $torneo->nombre."-".$fecha->nombre.".".$type;

        return Excel::download(new FechaRankingExport($request->id), $excelName);
    }
}