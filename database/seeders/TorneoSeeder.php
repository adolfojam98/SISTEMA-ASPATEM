<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

use App\Categoria;
use App\Torneo;
use App\Usuario;


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
        crearTorneoReal();
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
            $categoria->puntos_base = round($categoria->puntos_minimos + ($categoria->puntos_maximos - $categoria->puntos_minimos) / 2, 0); // Redondear a 2 decimales           $categoria->torneo_id = $torneo->id;
           $categoria->save(); 
           $nroCategoria++;
       }  
       $categoria = new Categoria();
       $categoria->nombre = "Categoría"  .$nroCategoria . " " .$torneo->nombre ;
       $categoria->puntos_minimos = $puntosMinimos;
       $categoria->puntos_maximos = 9999;
       $categoria->torneo_id = $torneo->id;
       $categoria->puntos_base = $categoria->puntos_minimos + 100;
    
      $categoria->torneo_id = $torneo->id;
      $categoria->save(); 
    }

    function crearTorneoReal(){
        $torneo = new Torneo();
        $torneo = new Torneo();
        $torneo->nombre = "Torneo Paranaense";
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
crearUsuarioSeedTorneoReal($torneo,"Gustavo", "Maili",615);
crearUsuarioSeedTorneoReal($torneo,"Aguirre", "German",530);
crearUsuarioSeedTorneoReal($torneo,"Nicolas", "Migoni",517);
crearUsuarioSeedTorneoReal($torneo,"Agustin", "Asmu",510);
crearUsuarioSeedTorneoReal($torneo,"Gaston", "Dupertuis",504);
crearUsuarioSeedTorneoReal($torneo,"Enrique", "Amore",500);
crearUsuarioSeedTorneoReal($torneo,"Elias", "Maili",500);
crearUsuarioSeedTorneoReal($torneo,"Geronimo", "Pradella",496);
crearUsuarioSeedTorneoReal($torneo,"Andres", "Nohara",481);
crearUsuarioSeedTorneoReal($torneo,"Luis", "Soto",468);
crearUsuarioSeedTorneoReal($torneo,"Carlos", "Fernandez",466);
crearUsuarioSeedTorneoReal($torneo,"Fernando", "Becker",440);
crearUsuarioSeedTorneoReal($torneo,"Axel", "Figueroa",438);
crearUsuarioSeedTorneoReal($torneo,"Yamil", "Asmu",435);
crearUsuarioSeedTorneoReal($torneo,"Fausto", "Fontana",426);
crearUsuarioSeedTorneoReal($torneo,"Raul", "Pressel",400);
crearUsuarioSeedTorneoReal($torneo,"Joel", "Chiara",400);
crearUsuarioSeedTorneoReal($torneo,"Franco", "Banfi",400);
crearUsuarioSeedTorneoReal($torneo,"Levin", "Raul",396);
crearUsuarioSeedTorneoReal($torneo,"Jose", "Aguer",396);
crearUsuarioSeedTorneoReal($torneo,"Joel", "Maili",391);
crearUsuarioSeedTorneoReal($torneo,"Carlos", "Gandolfo",385);
crearUsuarioSeedTorneoReal($torneo,"Pablo", "Welschen",381);
crearUsuarioSeedTorneoReal($torneo,"Gonzalo", "Ramos",371);
crearUsuarioSeedTorneoReal($torneo,"Nicolas", "Welschen",368);
crearUsuarioSeedTorneoReal($torneo,"Julio", "Galera",359);
crearUsuarioSeedTorneoReal($torneo,"Gustavo", "Guglia",351);
crearUsuarioSeedTorneoReal($torneo,"Javier", "Tenca",346);
crearUsuarioSeedTorneoReal($torneo,"Agustin", "Lanaro",340);
crearUsuarioSeedTorneoReal($torneo,"Juan", "CLerch",338); 
crearUsuarioSeedTorneoReal($torneo,"Jorge", "Larrosa",325);
crearUsuarioSeedTorneoReal($torneo,"Juan", "Carlos L,erch",300); 
crearUsuarioSeedTorneoReal($torneo,"Guillermo", "De Maria",284); 
crearUsuarioSeedTorneoReal($torneo,"Yuri", "Uziel",278);
crearUsuarioSeedTorneoReal($torneo,"Gabriel", "Aguirre",265);
crearUsuarioSeedTorneoReal($torneo,"Oscar", "Benicio",264);
crearUsuarioSeedTorneoReal($torneo,"Pablo", "Abraham",230);
crearUsuarioSeedTorneoReal($torneo,"Mario", "Brunengo",225);
crearUsuarioSeedTorneoReal($torneo,"Fernando", "Bosso",222);
crearUsuarioSeedTorneoReal($torneo,"Sandra", "Aguirre",222);
crearUsuarioSeedTorneoReal($torneo,"Palavecino", "Mario",218);
crearUsuarioSeedTorneoReal($torneo,"Fredy", "Rebosio",216);
crearUsuarioSeedTorneoReal($torneo,"Gabriel", "Gomes",215);
crearUsuarioSeedTorneoReal($torneo,"Graciela", "Werner",200);
crearUsuarioSeedTorneoReal($torneo,"Emiliano", "Rodriguez Alarcon",200); 
crearUsuarioSeedTorneoReal($torneo,"Juan", "Lerch",200);
crearUsuarioSeedTorneoReal($torneo,"Alfred", "Grass",200);
crearUsuarioSeedTorneoReal($torneo,"Javier", "comas",200);
crearUsuarioSeedTorneoReal($torneo,"Victor", "Chitero",200);
crearUsuarioSeedTorneoReal($torneo,"Fabian", "K",190);
crearUsuarioSeedTorneoReal($torneo,"German", "K",170);
crearUsuarioSeedTorneoReal($torneo,"Cristian", "Gabas",150);
crearUsuarioSeedTorneoReal($torneo,"Beatriz", "Taborda",132);
crearUsuarioSeedTorneoReal($torneo,"Benjamin", "Hutin",125);
crearUsuarioSeedTorneoReal($torneo,"Melania", "Almeira",100);
crearUsuarioSeedTorneoReal($torneo,"Sergio", "Gambino",100);
crearUsuarioSeedTorneoReal($torneo,"Santiago", "Atkinson",100);
crearUsuarioSeedTorneoReal($torneo,"Gabriela", "Pignoux",100);
crearUsuarioSeedTorneoReal($torneo,"Elida", "Benitez",100);
crearUsuarioSeedTorneoReal($torneo,"Diaz", "Claudino",100);
crearUsuarioSeedTorneoReal($torneo,"Mirta", "Smith",100);
crearUsuarioSeedTorneoReal($torneo,"Mari", "Monzon",100);
crearUsuarioSeedTorneoReal($torneo,"Claudino", "Diaz",100);
crearUsuarioSeedTorneoReal($torneo,"Sandra", "Aguirre",100);
crearUsuarioSeedTorneoReal($torneo,"Silvina", "Varisco",100);
crearUsuarioSeedTorneoReal($torneo,"Carlos", "Erbetta",100);
crearUsuarioSeedTorneoReal($torneo,"Monica", "Dome",100);
crearUsuarioSeedTorneoReal($torneo,"Claudia", "Lell",100);
crearUsuarioSeedTorneoReal($torneo,"Eliel", "Sigal",100);
crearUsuarioSeedTorneoReal($torneo,"Facundo", "Acevedo",100);

    }

    function sumarJugadoresTorneo($torneo)
    {
        $cantidadParticipantes = cantidadParticipantesTorneo();
       
        foreach ($cantidadParticipantes as $idJugador) {
            $torneo->jugadores()->attach($idJugador, ['puntos' => rand(0, 500)]);
        }

        
    }
    function crearUsuarioSeedTorneoReal($torneo,$nombre,$apellido,$puntos){
        
        
   
        $proveedorMail = ['yahoo','gmail', 'outlook', 'hotmail'];
        $proveedorElegido = $proveedorMail[array_rand($proveedorMail)];
        $usuario = new Usuario();
        $usuario->nombre = $nombre;
        $usuario->apellido = $apellido;
        $usuario->mail = strtolower($nombre) . '_' . strtolower($apellido) . '@' . $proveedorElegido . '.com'; 
        $usuario->telefono = rand(3434000000,3434999999);
       $usuario->dni = rand(30000000,45000000);
        $usuario->socio = rand(0,1);
        $usuario->save();
        $torneo->jugadores()->attach($usuario->id, ['puntos' => $puntos]);
        
        
    
        
    
    }



    

    function cantidadParticipantesTorneo()
    {
        $JugadoresBase = Usuario::count();
        $cantidadParticipantes = rand(10, $JugadoresBase);
        $idJugadoresArray = array();
        for ($x=0;$x<$cantidadParticipantes;$x++) {
            $num_aleatorio = rand(1, $cantidadParticipantes);
            array_push($idJugadoresArray, $num_aleatorio);
        }

        return array_unique($idJugadoresArray);
    }
