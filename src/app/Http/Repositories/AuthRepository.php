<?php
namespace App\Http\Repository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository {
    public function login ($email , $password){
        $user = User::where('email', $email)->first();
        if($user && hash::check($password, $user->$password)){
            return $user ;
        }
    }
    public function register($Full_name, $City, $Email, $password){
        $user = new User();
        $user->fullname = $Full_name;
        $user->location = $City ;
        $user->email = $Email;
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }
}
