<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100','min:3'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' =>['required'],  ],
            [
                'name.required'=>'Поле имя является обязательным',
                'name.max'=>'Максимальная длина поля 100 символов',
                'name.min'=>'Минимальная длина поля 3 символа',
                'email.required'=>'Поле является обязательным для ввода',
                'email.email'=>'Поле должно содержать @mail',
                'email.unique'=>'Данный @mail уже зарегистрирован',
                'password.required'=>'Поле является обязательным для ввода',
                'password.min'=>'Пароль должен быть не менее 8 символов',
                'password.confirmed'=>'Введенные пароли не совпадают',
                'password_confirmation.required'=>'Поле является обязательным для ввода'

            ]



      );
        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]


        );
        return response()->json(['msg'=>'request successssss']);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ],
        [

                'email.required'=>'Поле является обязательным',
                'password.required'=>'Поле является обязательным для ввода',


        ]

        );

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;


    }

    public function logout(Request $request)
    {


        $request->user()->currentAccessToken()->delete();
        return response()->json(['msq'=>'Logout success']);
    }

}
