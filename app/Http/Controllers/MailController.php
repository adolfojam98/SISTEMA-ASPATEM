<?php

namespace App\Http\Controllers;


use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use App\Usuario;
use App\Helpers\AppHelper;


use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendEmail(Request $request){

        $users = Usuario::whereNotNull('mail')->get();
        $count_errors = 0;
        $exceptions = array();

        $data = [
            'titulo' => $request->titulo ? $request->titulo : 'SYS-ASPATEM - AVISO',
            'mensaje' => $request->mensaje ? $request->mensaje : ''
        ];

        $asunto = $request->asunto ? $request->asunto : 'Nuevo Aviso';

        foreach ($users as $key => $user) {
            $to = array($user->mail);

            if(AppHelper::instance()->validateEmail($to)) {
                Mail::send('emails.testEmail',$data, function($msj) use ($to, $asunto){
                    $msj->from(env('MAIL_USERNAME'), 'ASPATEM');
                    $msj->subject($asunto);
                    $msj->to($to);
                });
            }
        }
    }
}
