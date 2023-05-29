<?php

namespace App\Http\Controllers;
use App\Http\Requests\LoginReq;

session_start();


class Login extends Controller
{

    public function __invoke(LoginReq $request){
        
        if(isset($_SESSION['logged'])){
            return redirect('/users');
        }
    
        include_once __DIR__ . '/../Database/config.php';
        
        $user_info = $pdo -> prepare("SELECT * FROM `users` WHERE mail=:mail AND BINARY pass=:pass");
        $user_info -> execute(array(
            "mail" => $request["email"],
        	"pass" => $request["pass"]
        ));
        $data = $user_info -> fetch();

        if($data){
            $_SESSION['admin'] = $data['is_admin'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];
            $_SESSION['pass'] = $data['pass'];
            return redirect(route("root"));
        }
        else {
            return redirect(route("login") . "?f");
        }

    }
}
