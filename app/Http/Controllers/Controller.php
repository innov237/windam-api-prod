<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login','register','getServiceWithCategory']]);
    }

    public static function inputFormatter($data)
    {
        return htmlspecialchars($data);
    }

    public static function ApiResponse($data, int $code, string $message, bool $status)
    {

        return response($data, $code);
    }


    public function reply($success, $message = null, $data = null)
    {
        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $data
        ];

        return response()->json($response);
    }

    public function jwt($success, $message = null, $data = null)
    {
        $response = [
            "success" => $success,
            "message" => $message,
            "data" => $data
        ];

        return response()->json($response, 200);
    }
    /*  protected function ApiResponseWithToken($token)
    {
        return response([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ],201);
    } */
    protected function ApiResponseWithToken($token, $data, $message)
    {
        return response([
            'token' => $token,
            'data' => $data,
            'status' => true,
            'meassage' => $message
        ], 201);
    }


    public function sendNotif(Request $request, $deviceToken)
    {

        //    return $deviceToken;
        // FCM API Url
        $url = 'https://fcm.googleapis.com/fcm/send';

        // Put your Server Key here
        $apiKey = "AAAATGiDrnc:APA91bEro02XP3JZwshJCWqFYQg_-iDbGHBVvADChxE924AfKLRACWfc3_W2y5yEzsT_JEn93-W8k9xzE4By9wVfTMEeFDlBf2DJvtYSgIfImTiMezlqe9YopYun7CHgNMWHXkRSm1ri";

        // Compile headers in one variable
        $headers = array(
            'Authorization:key=' . $apiKey,
            'Content-Type:application/json'
        );

        // Add notification content to a variable for easy reference
        $notifData = [
            'title' => $request->title,
            'body' => $request->body,
            "image" => $request->input('img', ""), //Optional
            'click_action' => "activities.NotifHandlerActivity",
            "channel" => $request->channel, //Action/Activity - Optional
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
            "channel" => $request->channel,
            //'to' => '/topics/Tafadom'
            //'registration_ids' = ID ARRAY
            'to' => $deviceToken
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
}
