<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use Illuminate\Http\Request;

use FCM;

class FcmnotificationController extends Controller
{
    public function notify(Request $request)
    {
        /**
         * {
         *"user_id":8,
         *"demande_id":2, 
         *"title":"taafadom test notification", 
         *"body":"texst de notification de tafadom" , 
         * "channel":"ConfirmDemande"
         *}
         */

        $userDevices = Devices::where('user_id', $request->user_id)->first();

        if (!$userDevices) {
            return $this->reply(false, "token de l'utilisateur introuvable", null);
        }

        //    return $deviceToken;
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAASXQ1LDM:APA91bGV5ffRVStcmm-W5C6oNv6MpwMTE_VJlInQLVAbg9uu3AbAXzPV8NxnjWkO1BJVEOxNwPD3zF3ovM_H7qW_hHKEDuw7uXPKoW_J2CUAHkFgImm-KLDiagOMX5P3N62bKgX_SCQP";

        // Compile headers in one variable
        $headers = array(
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );

        $channel=$request->channel;
       // $channel="NewMessage";
        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => $request->title,
            'body' => $request->body,
            "image" => $request->input('img', ""), //Optional
            'click_action' => "activities.NotifHandlerActivity",
            "channel" => $channel, //Action/Activity - Optional
        ];

        $dataPayload = [
            'title' => $request->title,
            'body' => $request->body,
            'channel'=> $channel
        ];

        // Create the api body
        $apiBody = [
            'notification' => $notifData,
            'data' => $dataPayload, //Optional
            //'time_to_live' => 600, // optional - In Seconds
            "channel" => $channel,
            //'to' => '/topics/Tafadom'
            //'registration_ids' = ID ARRAY
            'to' => $userDevices->token
        ];

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

        // Execute call and save result
        $result = curl_exec($ch);
      //  print($result);
        // Close curl after call
        curl_close($ch);

        // return $result;
        return $this->reply(true, "Notification envoyer avec success");
    }

    public function sendnotiff(){
        
        //    return $deviceToken;
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAA68_kHWs:APA91bEXBGO2PLTAuGnykDq9l3sGuqcIqWlrZiPRj0NN1rzlV-Utcb2gpQ3P1fsON-0hT4dTODypPZAIWYw4rGbccGYMfGoGDu5EcAoPbh9l172fUmxk6ro8aSGiBZZ-scfdPwlDO3Xi";

        // Compile headers in one variable
        $headers = array(
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => "titre",
            'body' => "body",//Optional
            'click_action' => "activities.NotifHandlerActivity",
            "channel" => "chat", //Action/Activity - Optional
        ];

        $dataPayload = [
            'to' => 'Nouveau message',
            'points' => 80,
            'other_data' => 'This is extra payload'
        ];

        // Create the api body
        $apiBody = [
            'notification' => $notifData,
            'data' => $dataPayload, //Optional
            //'time_to_live' => 600, // optional - In Seconds
            "channel" => "chat",
            //'to' => '/topics/Tafadom'
            //'registration_ids' = ID ARRAY
            'to' => "c3G2E4F7Q9OZCtYnSn25U7:APA91bFNVF6TNo6aOI3dzgFhnMPKPSEX8-V093wDrILZj1QTVEzu36wpY0J5UChFfdf5zZ3FQj3G2SJJd5wZUPBs8AC2YUIRORQ0bcBZQXAbx-EQLA_oY8p1I0ODdjICNkePcEZU-TNn"
        ];

        // Initialize curl with the prepared headers and body
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));

        // Execute call and save result
        $result = curl_exec($ch);
        print($result);
        // Close curl after call
        curl_close($ch);

    }
}
