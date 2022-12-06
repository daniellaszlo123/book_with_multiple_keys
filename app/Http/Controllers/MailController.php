<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;
use Illuminate\Http\Request;

class MailController extends Controller
{

public function index(){
    $mailData = [
        'title' => '?',
        'body' => 'hol vagy?'
    ];
    
    Mail::to('laravel.mikulas@gmail.com')
    ->send(new DemoMail($mailData));

    dd("Email is sent successfully.");

}

}
