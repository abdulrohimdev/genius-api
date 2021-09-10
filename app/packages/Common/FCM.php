<?php
namespace Package\Common;

class FCM{

    function sendPushNotification($fcm_token, $title, $message, $data = null) {

        $url = "https://fcm.googleapis.com/fcm/send";
        $header = [
            'authorization:key=AAAAjZUM7n8:APA91bEChuwK0IsiP3pm8QqFK--so7e1BvqYCrhGRAfrKpWFgwwt05Eh9xu3B5e8WV0KP7afpGWIpC996zk_QG-zQr3odh2fvpnjwSA3aUIb7b45gNAV6hf6qfRDKyl4sQXRk8ra5mIA',
            'content-type: application/json'
        ];

        $notification = [
            'title' =>$title,
            'body' => $message
        ];
        // $extraNotificationData = ["message" => $notification,'action'=>$action,"id" =>$id];
        $fcmNotification = [
            'to'        => $fcm_token,
            'notification' => $notification,
            'data' => $data,
            'priority' => "high",
            'android' => [
                'ttl' => '3600s',
                'priority' => 'high',
                'notification' => [
                    'notification_priority' => 'PRIORITY_MAX',
                    'visibility' => 'PUBLIC',
                    'title' => $title,
                    'body' => $message,
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
