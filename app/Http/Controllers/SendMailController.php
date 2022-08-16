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
                $message->from("support@seremo.co");
                $message->to("innov237@gmail.com", "Notification")->subject($mailData['subject']);
            });
    
    
            return $this->reply(true, 'mail sent', $send);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

       
    }
}
