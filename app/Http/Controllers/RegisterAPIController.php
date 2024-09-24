<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterAPIController extends Controller
{

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Faltan datos',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json(['message' => 'Usuario creado con éxito', 'user' => $user], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Datos no válidos', 'error' => $th->getMessage()], 400);
        }
    }

}
