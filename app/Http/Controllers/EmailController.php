<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function testMail()
    {
        $user = User::find(2);
        //Mail::to('mailguntestlaravel12@gmail.com')->queue(new RegisterMail($user));
        Mail::to('mailguntestlaravel1@gmail.com')->queue(new RegisterMail($user, 'string1'));
        dd('ok');
    }
}