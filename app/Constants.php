<?php
    namespace App;

    class Constants
    {

        const CuotaDetalleTipos = [
            'Precio Base' => 'precio_base',
            'Relaciones' => 'relaciones',
            'Moroso Bajo' => 'moroso_bajo',
            'Moroso Medio' => 'moroso_medio',
            'Moroso Alto' => 'moroso_alto'
        ];

        //estos serian los no visibles para el frontend
        const CUOTA_DETALLES_TIPOS_EXCEPTIONLS = [
            'Ingreso' => 'ingreso'
        ];



    }
?>