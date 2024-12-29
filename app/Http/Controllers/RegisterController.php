<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function index(){
        return view('auth.register');
    }

    public function store(Request $request){
        //dd($request);
        //dd($request->get('username'));
        //Modificar el request
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validacion
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:40',
            'password' => 'required|confirmed|min:6'
        ]);
        //dd('Creando usuario');
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //Autenticar usuario
        // auth()->attempt([
        //     'email' => $request->email,
        //     'password' => $request->password
        // ]);

        //Otra forma para autenticar
        auth()->attempt($request->only('email', 'password'));

        //Redireccionar
        return redirect()->route('posts.index');
    }
}
