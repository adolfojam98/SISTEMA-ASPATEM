<?php
    namespace App;

    class Constants
    {

        const CuotaDetalleTipos = [
            'Precio Base' => 'precio_base',
            'Relaciones' => 'relaciones',
            'Moroso Bajo' => 'moroso_bajo',
            'Moroso Medio' => 'moroso_medio',
            'Moroso Alto' => 'moroso_alto',
            'Ingreso' => 'ingreso'
        ];

        //estos serian los no visibles para el frontend
        const CUOTA_DETALLES_TIPOS_EXCEPTIONLS = [
            // 'Ingreso' => 'ingreso'
        ];

        //No cambiar el orden, es importante, se usa en el modelo de usuario y el codigo se forma por el nombre, que se usa en el front :D
        const CONFIGURACIONES_DEFAULT = [
            'Baja Socios Por Cuotas Adeudadas' => true,
            'Cantidad Cuotas Adeudadas Para Baja Socio' => 4
        ];

    }
?>