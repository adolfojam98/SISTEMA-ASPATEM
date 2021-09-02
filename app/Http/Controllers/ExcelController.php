<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class ExcelController extends Controller
{
    public function getFechaExcel(Request $request)
    {
        dd($request->ranking);
        return;
    }
}
