
<?php

use App\Categoria;
use App\Torneo;
use App\Usuario;
use Illuminate\Database\Seeder;

class TorneoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        crearTorneoSeed("Torneo1");
        crearTorneoSeed("Torneo2");
        crearTorneoSeed("Torneo3");
        crearTorneoSeed("Torneo4");
    }

    
}

function crearTorneoSeed($nombre)
{
    $torneo = new Torneo();
    $torneo->nombre = $nombre;
    $torneo->vigencia = true;
    $torneo->misma_categoria_mayor_nivel_ganador = rand(3, 10);
    $torneo->misma_categoria_menor_nivel_ganador = rand(3, 10);
    $torneo->misma_categoria_mayor_nivel_perdedor = rand(3, 10);
    $torneo->misma_categoria_menor_nivel_perdedor = rand(3, 10);

    $torneo->mayor_categoria_ganador = rand(3, 10);
    $torneo->menor_categoria_ganador = rand(3, 10);
    $torneo->mayor_categoria_perdedor = rand(3, 10);
    $torneo->menor_categoria_perdedor = rand(3, 10);
    $torneo->save();
    
    sumarCategoriasTorneo($torneo);
    sumarJugadoresTorneo($torneo);
    
}
    
    function sumarCategoriasTorneo($torneo){

       $maximo = rand(300,500);
       $puntosMinimos = 0;
       $nroCategoria = 1; 
       while($puntosMinimos <=$maximo){
           $categoria = new Categoria();
            $categoria->nombre = "Categoría"  .$nroCategoria . " " .$torneo->nombre ;
            $categoria->puntos_minimos = $puntosMinimos;
            $categoria->puntos_maximos = $puntosMinimos + (rand(5,15)*10)-1;
            $puntosMinimos = $categoria->puntos_maximos + 1;
           $categoria->torneo_id = $torneo->id;
           $categoria->save(); 
           $nroCategoria++;
            

       }
        


    }

    function sumarJugadoresTorneo($torneo)
    {
        $cantidadParticipantes = cantidadParticipantesTorneo();
       
        foreach ($cantidadParticipantes as $idJugador) {
            $torneo->jugadores()->attach($idJugador, ['puntos' => rand(0, 500)]);
        }

        
    }



    

    function cantidadParticipantesTorneo()
    {
        $JugadoresBase = Usuario::count();
        $cantidadParticipantes = rand(5, $JugadoresBase);
        $idJugadoresArray = array();
        for ($x=0;$x<$cantidadParticipantes;$x++) {
            $num_aleatorio = rand(1, $cantidadParticipantes);
            array_push($idJugadoresArray, $num_aleatorio);
        }

        return array_unique($idJugadoresArray);
    }
