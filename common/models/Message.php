<?php

namespace common\models;

use Yii;
use DateTime;
use DateTimeZone;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property int $kimdan
 * @property int $kimga
 * @property string $title
 * @property int $status
 * @property int $date
 */

class Message extends \yii\db\ActiveRecord
{
    public static function sendSmsNew($phone, $text)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $text = "SARBON UNIVERSITETI qabul saytiga ro'yxatdan o'tish uchun tasdiqlash kodi: ". $text;
        $data = '{
                "messages":
                    [
                        {
                        "recipient":'.$phone.',
                        "message-id":"abc000000001",
                            "sms":{
                                "originator": "3700",
                                "content": {
                                    "text": "'.$text.'"
                                }
                            }
                        }
                    ]
                }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://send.smsxabar.uz/broker-api/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".base64_encode("sarbonuniversiteti:3z3Q|X|w!a&^"),
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return [
            'is_ok' => true,
        ];


//        $phone = preg_replace("/[^0-9]/", "", $phone);
//        $email = 'norbobo1996@gmail.com';
//        $password = 'UknMFbL9gen9oy8JKbibVpBNfrMP1VY6lq0sFuyj';
//        $url = 'http://notify.eskiz.uz/api/auth/login';
//        $client = new Client();
//        try {
//            $response = $client->createRequest()
//                ->setFormat(Client::FORMAT_JSON)
//                ->setMethod("POST")
//                ->setUrl($url)
//                ->setData([
//                    'email' => $email,
//                    'password' => $password
//                ])
//                ->send();
//        } catch (\Exception $e) {
//            return [
//                'is_ok' => false,
//                'errors' => ['SMS yuborishda xatolik: ' . $e->getMessage()],
//            ];
//        }
//
//        $data = (json_decode($response->content))->data;
//        $token = $data->token;
//        $from = "4546";
//        $url = 'http://notify.eskiz.uz/api/message/sms/send';
//        $textNew = "SARBON UNIVERSITETI qabul saytiga ro'yxatdan o'tish uchun tasdiqlash kodi: " . $text;
//
//        try {
//            $response = $client->createRequest()
//                ->setFormat(Client::FORMAT_JSON)
//                ->setMethod("POST")
//                ->setUrl($url)
//                ->addHeaders(['Authorization' => 'Bearer ' . $token])
//                ->setData([
//                    'message' => $textNew,
//                    'mobile_phone' => $phone,
//                    'from' => $from
//                ])
//                ->send();
//        } catch (\Exception $e) {
//            return [
//                'is_ok' => false,
//                'errors' => ['SMS yuborishda xatolik: ' . $e->getMessage()],
//            ];
//        }
//        return [
//            'is_ok' => true,
//        ];
    }

    public static function sendSms($phone, $text)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $text = "SARBON UNIVERSITETI qabul saytiga ro'yxatdan o'tish uchun tasdiqlash kodi: ". $text;
        $data = '{
                "messages":
                    [
                        {
                        "recipient":'.$phone.',
                        "message-id":"abc000000001",
                            "sms":{
                                "originator": "3700",
                                "content": {
                                    "text": "'.$text.'"
                                }
                            }
                        }
                    ]
                }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://send.smsxabar.uz/broker-api/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".base64_encode("sarbonuniversiteti:3z3Q|X|w!a&^"),
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $phone." --- ".$response;

//        $phone = preg_replace("/[^0-9]/", "", $phone);
//        $email = 'norbobo1996@gmail.com';
//        $password = 'UknMFbL9gen9oy8JKbibVpBNfrMP1VY6lq0sFuyj';
//        $url = 'http://notify.eskiz.uz/api/auth/login';
//        $client = new Client();
//
//        $response = $client->createRequest()
//            ->setFormat(Client::FORMAT_JSON)
//            ->setMethod("POST")
//            ->setUrl($url)
//            ->setData([
//                'email' => $email,
//                'password' => $password
//            ])
//            ->send();
//
//        $data = (json_decode($response->content))->data;
//        $token = $data->token;
//        $from = "4546";
//        $url = 'http://notify.eskiz.uz/api/message/sms/send';
//        $textNew = "SARBON UNIVERSITETI qabul saytiga ro'yxatdan o'tish uchun tasdiqlash kodi: " . $text;
//        $response = $client->createRequest()
//            ->setFormat(Client::FORMAT_JSON)
//            ->setMethod("POST")
//            ->setUrl($url)
//            ->addHeaders(['Authorization' => 'Bearer ' . $token])
//            ->setData([
//                'message' => $textNew,
//                'mobile_phone' => $phone,
//                'from' => $from
//            ])
//            ->send();
//        return ($response->statusCode);
    }



    public static function sendedSms($phone, $text)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        $data = '{
                "messages":
                    [
                        {
                        "recipient":' . $phone . ',
                        "message-id":"abc000000001",
                            "sms":{
                                "originator": "3700",
                                "content": {
                                    "text": "' . $text . '"
                                }
                            }
                        }
                    ]
                }';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://send.smsxabar.uz/broker-api/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".base64_encode("sarbonuniversiteti:3z3Q|X|w!a&^"),
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $phone . " --- " . $response;
    }
}
