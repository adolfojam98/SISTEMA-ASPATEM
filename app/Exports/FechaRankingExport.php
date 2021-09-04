<?php

namespace App\Exports;

use DB;
use App\Usuario;
use App\Fecha;
use App\Torneo;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FechaRankingExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $fecha_id;

    function __construct($fecha_id) {
            $this->fecha_id = $fecha_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $fecha = Fecha::whereId($this->fecha_id)->first();
        $ranking_hasta_esta_fecha = [];

        $torneo_usuarios = Torneo::where('torneos.id',$fecha->torneo_id)
            ->join('torneo_usuario','torneo_usuario.torneo_id','=','torneos.id')
            ->join('usuarios','usuarios.id','=','torneo_usuario.usuario_id')
            ->get();

            foreach ($torneo_usuarios as $key => $torneo_usuario) {
                $fechas_usuarios = Fecha::where('fechas.id',$fecha->torneo_id)
                ->where('fechas.created_at','=<',$fecha->created_at) //TODO voy a traer todas las fechas anteriores y esta
                ->where('fecha_usuario.usuario_id',$torneo_usuario->usuario_id)
                ->join('fecha_usuario','fecha_usuario.fecha_id','=','fechas.id')
                ->get();

                foreach ($fechas_usuarios as $key => $fecha_usuario) {
                    $torneo_usuario->puntos += $fecha_usuario->puntos;
                }

                $jugador = [
                "nombre" => $torneo_usuario->nombre,
                "apellido" => $torneo_usuario->apellido,
                "dni" => $torneo_usuario->dni,
                "puntos" => $torneo_usuario->puntos,
                ];
                
                array_push($ranking_hasta_esta_fecha,$jugador);
            }

        $models = Usuario::hydrate($ranking_hasta_esta_fecha);
            
        return $models;
    }

    public function headings() : array
    {
        return ["Apellido", "Nombre", "Dni", "Puntos"];
    }

    public function registerEvents() : array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {                
                $cellRange = 'A1:D1'; // All headers
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->getColor()
                //     ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                // $event->sheet->getDelegate()->getStyle($cellRange)->getFill()
                //     ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //     ->getStartColor()->setARGB('FF17a2b8');
                // $event->sheet->setAutoFilter($cellRange);
            },
        ];
    }

}
