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
        $params = $request->all();

        $users = Usuario::whereNotNull('mail')->get();
        $count_errors = 0;
        $exceptions = array();

        $data = [
            'asunto' => $params['asunto'],
            'titulo' => $params['titulo'],
            'subtitulo' => $params['subtitulo'] ?  $params['subtitulo'] : '',
            'mensaje' => $params['mensaje']
        ];


        foreach ($users as $key => $user) {
            $to = array($user->mail);

            if(AppHelper::instance()->validateEmail($to)) {
                Mail::send('emails.testEmail',$data, function($msj) use ($to, $data){
                    $msj->from(env('MAIL_USERNAME'), 'ASPATEM');
                    $msj->subject($data['asunto']);
                    $msj->to($to);
                });
            }
        }
    }
}
