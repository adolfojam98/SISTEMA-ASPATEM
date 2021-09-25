<?php

namespace App\Http\Controllers;


use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;


use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendEmail(){
        $details =  [
            'title' => 'Chupa pija',
            'body' => 'blablabla'
        ];

        Mail::to('gonzalo363rm@gmail.com') ->send(new TestMail($details));

    }
}
