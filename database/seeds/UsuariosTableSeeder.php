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
        
        $usuario = new Usuario();
        $usuario->nombre = "Adolfo";
        $usuario->apellido = "Martinez";
        $usuario->mail = "adolfo@mail";
        $usuario->telefono = "5432543543";
        $usuario->socio = 1;
        $usuario->save();

        $usuario = new Usuario();
        $usuario->nombre = "Aldana";
        $usuario->apellido = "Zualet";
        $usuario->mail = "z@mail";
        $usuario->telefono = "5432543543";
        $usuario->socio = 1;
        $usuario->save();
        
        $usuario = new Usuario();
        $usuario->nombre = "Gonzalo";
        $usuario->apellido = "Ramos Muzio";
        $usuario->mail = "gonza@mail";
        $usuario->telefono = "5456543543";
        $usuario->socio = 1;
        $usuario->save();

        $usuario = new Usuario();
        $usuario->nombre = "Alejandro";
        $usuario->apellido = "Stivanello";
        $usuario->mail = "ale@mail";
        $usuario->telefono = "7654345674";
        $usuario->socio = 1;
        $usuario->save();

        $usuario = new Usuario();
        $usuario->nombre = "Analuz";
        $usuario->apellido = "Noro";
        $usuario->mail = "ana@mail";
        $usuario->telefono = "5345643234";
        $usuario->socio = 1;
        $usuario->save();


        $usuario = new Usuario();
        $usuario->nombre = "Emiliano";
        $usuario->apellido = "Aressi";
        $usuario->mail = "emi@mail";
        $usuario->telefono = "3456743223";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Lucas";
        $usuario->apellido = "Campos";
        $usuario->mail = "lucas@mail";
        $usuario->telefono = "3456743243";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Federico";
        $usuario->apellido = "Rulh";
        $usuario->mail = "fede@mail";
        $usuario->telefono = "456789765";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Juan";
        $usuario->apellido = "Ghibaudo";
        $usuario->mail = "juan@mail";
        $usuario->telefono = "5678765434";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Franco";
        $usuario->apellido = "Terranova";
        $usuario->mail = "franco@mail";
        $usuario->telefono = "4567876543";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Flavia";
        $usuario->apellido = "Crolla";
        $usuario->mail = "flavia@mail";
        $usuario->telefono = "5678654456";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Magali";
        $usuario->apellido = "Lauk";
        $usuario->mail = "maga@mail";
        $usuario->telefono = "4567876543";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Leonel";
        $usuario->apellido = "Gareis";
        $usuario->mail = "leo@mail";
        $usuario->telefono = "456543543";
        $usuario->socio = 1;
        $usuario->save();

        
        $usuario = new Usuario();
        $usuario->nombre = "Adrian";
        $usuario->apellido = "Soliard";
        $usuario->mail = "chino@mail";
        $usuario->telefono = "3456787654";
        $usuario->socio = 1;
        $usuario->save();

        
    }
}
