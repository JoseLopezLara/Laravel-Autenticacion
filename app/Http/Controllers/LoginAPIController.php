<?php

namespace App\Http\Controllers;

use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginAPIController extends Controller{
    public function login(Request $request){

        //TimeStamp UTC
        $timestamp = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s\Z');

        try{

            $validate = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if(Auth::attempt($validate)){
                //Ya se ha valido mi usuario
                $status = "success";
                $code = 202;
                $message = "Authorized";
                $bearerToke = $request->user()->createToken('Toke')->plainTextToken;

                $responseArray = [
                    "status" => $status,
                    "code" => $code,
                    "message" => $message,
                    "info" => [
                        [
                            "toke" => $bearerToke,
                        ],
                    ],
                    "timestamp" => $timestamp,
                ];

                // Convertir el array a JSON
                $jsonResponse = json_encode($responseArray, JSON_PRETTY_PRINT);

                return $jsonResponse;

            } else{
                //El usuario no es valido
                $status = "error";
                $code = 401;
                $message = "Invalid user credentials";
                $emailError = "The email address is incorrect or not registered.";
                $passwordError = "The password provided is incorrect.";

                $responseArray = [
                    "status" => $status,
                    "code" => $code,
                    "message" => $message,
                    "errors" => [
                        [
                            "field" => "email",
                            "message" => $emailError
                        ],
                        [
                            "field" => "password",
                            "message" => $passwordError
                        ]
                    ],
                    "timestamp" => $timestamp,
                ];

                // Convertir el array a JSON
                $jsonResponse = json_encode($responseArray, JSON_PRETTY_PRINT);

                return $jsonResponse;
            }
        }catch(Exception $exception){
            Log::error("ERROR: LoginAPIController: $exception ");

            //El usuario no es valido
            $status = "error";
            $code = 401;
            $message = "Invalid user credentials";
            $emailError = "The email address is incorrect or not registered.";
            $passwordError = "The password provided is incorrect.";

            $responseArray = [
                "status" => $status,
                "code" => $code,
                "message" => $message,
                "errors" => [
                    [
                        "field" => "email",
                        "message" => $emailError
                    ],
                    [
                        "field" => "password",
                        "message" => $passwordError
                    ]
                ],
                "timestamp" => $timestamp,
            ];

            // Convertir el array a JSON
            $jsonResponse = json_encode($responseArray, JSON_PRETTY_PRINT);

            return $jsonResponse;
        }

    }
}
