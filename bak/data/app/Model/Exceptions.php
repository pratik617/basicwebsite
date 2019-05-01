<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Mail;

class Exceptions extends Model
{
    public $table = "exceptions";
    public $primaryKey = "id";

    public function sendException($e)
    {
    	$exception = New Exceptions;
    	$exception->exception = $e;
    	$exception->save();

    	// $chk = Mail::send(['html' => 'mail.error.error'],['text' => $e],function($message) {
	    //     $message->to('shineinfosoft27@gmail.com', 'RideApp Developer')->subject
	    //         ('RideApp Error');
	    //     $message->from('rideapp@gmail.com','RideApp');
	    // });
    }
}
