<?php

use Illuminate\Database\Seeder;
use App\Usuario;

class UsuariosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        crearUsuarioSeed('Adolfo','Martinez');
        crearUsuarioSeed('Aldana','Zualet');
        crearUsuarioSeed('Gonzalo','Ramos Muzio');
        crearUsuarioSeed('Alejandro','Stivanello');
        crearUsuarioSeed("Emiliano","Aressi");
        crearUsuarioSeed("Lucas","Campos");
        crearUsuarioSeed("Federico","Rulh");
        crearUsuarioSeed("Juan","Ghibaudo");
        crearUsuarioSeed("Franco","Terranova");
        crearUsuarioSeed("Flavia","Crolla");
        crearUsuarioSeed("Magali","Lauk");
        crearUsuarioSeed("Leonel","Gareis");
        crearUsuarioSeed("Adrian","Soliard");
        crearUsuarioSeed("Fernando","Sato");
        crearUsuarioSeed("Martin","Palermo");
        crearUsuarioSeed('Morena', 'Beltran');
        crearUsuarioSeed('Johana', 'Lauk');
        crearUsuarioSeed('Pablo', 'Beckam');
        crearUsuarioSeed('Lucas', 'Viatri');
        crearUsuarioSeed('Fort', 'Ricardo');
        // crearUsuarioSeed('Juan', 'Gerstner');
        // crearUsuarioSeed('Jose Maria', 'Listorti');
        // crearUsuarioSeed('Nahuel', 'Gonzales');
        // crearUsuarioSeed('Maximiliano', 'Diaz');
        // crearUsuarioSeed('Carlos', 'Tevez');
        // crearUsuarioSeed('Sebastian', 'Villa');
        // crearUsuarioSeed('Delfina', 'Pignattelo');
        // crearUsuarioSeed('Juan Domingo', 'Peron');
        // crearUsuarioSeed('Nahir', 'Galarza');
        // crearUsuarioSeed('Tomas', 'Chancalay');
        // crearUsuarioSeed('Erik', 'Lamela');
     
        // crearUsuariosFalsos(100);
       
    }
}

function crearUsuariosFalsos($cantidad){
    $faker = Faker\Factory::create();

    for ($i=0; $i < $cantidad; $i++) { 

        crearUsuarioSeed($faker->firstName, $faker->lastName);
    }
}
function crearUsuarioSeed($nombre,$apellido){
    
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


}
