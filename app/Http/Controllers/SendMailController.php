<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    
    public function notify(Request $request)
    {
        $mailData = [
            "user"=> $request->user,
            "subject" => $request->subject,
            "content" => $request->message,
        ];

        try {
            $send = Mail::send('notificationmail', $mailData, function ($message) use ($mailData) {
                $message->to("franck.godson@yahoo.fr", "Notification");
                $message->cc("innov237@gmail.com","Notification");
                $message->subject($mailData['subject']);
            });
    
            return $this->reply(true, 'mail sent', $send);

        } catch (\Exception $e) {
            //return $e->getMessage();
        }

       
    }

    public function sendOtp(Request $request){

        $mailData = [
            "otp"=> $request->otp,
            "subject" => $request->subject,
            "content" => $request->message,
            "email"=>$request->email,
        ];

        try {
            $send = Mail::send('otpmail', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['email'],"Code de vérification");
                $message->subject($mailData['subject']);
            });
    
            return $this->reply(true, 'mail sent', $send);

        } catch (\Exception $e) {
            //return $e->getMessage();
        }
    }
}
