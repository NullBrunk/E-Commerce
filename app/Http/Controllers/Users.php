<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupReq;
use App\Http\Requests\LoginReq;
use Exception;


session_start();

class Users extends Controller
{
    
    /* 
    Check if a user is in the database, 
    
    if he is 
        Put his informations in the SESSION
    else
        Display an error
    */
    
    public function show(LoginReq $request){
        
        include_once __DIR__ . '/../Database/config.php';
       
        if(isset($_SESSION['logged'])){
            return redirect('/');
        }
    
        
        $user_info = $pdo -> prepare("SELECT * FROM `users` WHERE mail=:mail AND BINARY pass=:pass");
        $user_info -> execute(array(
            "mail" => $request["email"],
        	"pass" => $request["pass"]
        ));
        $data = $user_info -> fetch();

        if($data){
            $_SESSION['id'] = $data['id'];
            $_SESSION['admin'] = $data['is_admin'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];
            $_SESSION['pass'] = $data['pass'];
            $_SESSION['cart'] = [];

            return redirect(route("root"));
        }
        else {

            return redirect(route("login") . "?f");
        }

    }

    /* 
    Add a user in the database and redirect to the login page 
    
    if he is already in the DB
        Display an error
    */
    public function store(SignupReq $request){

        include_once __DIR__ . '/../Database/config.php';
        
        $create_user = $pdo -> prepare("INSERT INTO `users`(mail, pass) VALUES (:mail, :pass)");
        try {
            $create_user -> execute(array(
                "mail" => $request["email"],
                "pass" => $request["pass"]
            ));
        }

        catch (Exception $e) {
            return view("login.signup", ["error" => true]);
        }

        return redirect("/login");
    }
}