<?php

namespace App\Http\Controllers;

use App\Events\SignupEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Http\Requests\UpdateProfile;
use App\Http\Requests\Signup;
use App\Http\Requests\Login;

use App\Models\MailValidation;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Notif;
use App\Models\User;
use App\Models\Cart;
use Exception;

session_start();

class Users extends Controller {


    /**
     * Log the user if he gave the good username and password.
     *
     * @param Login $request     The request with the username & password.
     *  
     * @return redirect          redirect to / if he is logged 
     *                           redirect to /login if he isn't.
     * 
     */
    
    public function login(Login $request){
               
        $req = $request -> validated();

        # If the user is already logged 

        if(isset($_SESSION['logged'])){
            return redirect('/');
        }

        $data = User::where([
            [ "mail", "=",  $req["email"]],
            [ "pass", "=", hash("sha512", hash("sha512", $req["pass"])) ],
        ]) -> get() -> toArray();

        if(!empty($data)){

            $data = $data[0];

            if(!$data["verified"] ){
                return to_route("auth.login") -> withErrors([
                    "verify" => "You need to verify your mail address"
                ]) -> onlyInput("email");
            }

            $_SESSION['id'] = $data['id'];
            $_SESSION['logged'] = true;
            $_SESSION['mail'] = $data['mail'];
            $_SESSION['pass'] = $data['pass'];
            $_SESSION["closed"] = [];


            return to_route("cart.initialize");
        }

        else {

            return to_route("auth.login") -> withErrors([
                "invalid" => "Wrong mail or password !"
            ]) -> onlyInput("email");
        }
    }



    /**
     * Signup a user
     *
     * @param Signup $request     The informations to store a new user in the database
     * 
     * @return redirect           Redirect / when he signed up
     * 
     */
    
    public function store(Signup $request, User $user){

        $req = $request -> validated();
        $checksum = Str::uuid();
        
        // Send verification mail

        $user = User::create([
            "mail" => $req["mail"],
            "pass" => hash("sha512", hash("sha512", $req["pass"])),
        ]);

        MailValidation::create([
            "id_user" => $user -> id,
            "checksum" => $checksum,
        ]);
      

        // Dispatch a SignupEvent so that the SignupListenner catch it
        SignupEvent::dispatch($user -> mail, $checksum);

        return to_route("auth.login") -> with("success", "A confirmation mail have been sent to " . $user -> mail);
    }


    
    /** 
     * Confirm the email of a given user
     * 
     * @param string $checksum         The random string sended to the user
     * 
     */

    public function confirm_mail(string $checksum){

        $data = MailValidation::where("checksum", "=", $checksum) -> get();

        if($data -> first()){

            $info = $data[0];

            User::where("id", "=", $info -> id_user) -> update([
                "verified" => 1
            ]);

            $info -> delete();

            return to_route("auth.login") -> with("success", "Your mail have been confirmed, you can log-in now.", "Verified !");
        }

        return abort(403);
    }



    /**
     * Update the settings of a given user if he is allowed to 
     *
     * @param UpdateProfile $request     The request with all the valuable informations 
     * 
     * @return redirect                  Redirect to the settings page in all the cases
     * 
     */
    
    public function settings(UpdateProfile $request){

        $req = $request -> validated();

        # Check if the hashed given password is the good one

        $verify_user = User::where([
            [ "mail", "=",  $_SESSION['mail']],
            [ "pass", "=", hash("sha512", hash("sha512", $req['oldpass'])) ],
        ]) -> get() -> toArray();


        # The user is trying to change his profile information
        # with wrong credentials, abort

        if(empty($verify_user)){
            return to_route("profile.settings") 
                -> withErrors(["wrong_password" => "The entered password does not match your actual password."]);
        }

        # The user is authorized     
       
        try {
            
            User::where("mail", "=", $_SESSION['mail'])
                  -> update([ 
                    "mail" => $req['email'],
                    "pass" => hash("sha512", hash("sha512", $req['newpass'])), 
                ]);

        }
        catch (Exception $e){
            return to_route("profile.settings") 
                -> withErrors(["alreadytaken" => "Mail is already taken."]);            
        }


        $_SESSION['mail'] = $req['email'];

        return to_route("profile.settings") 
            -> with("done", "Your information has been updated.");
    }



    /**
     * Show the settings page of the current user with
     * all the products that he is selling
     *      *
     * @return view             The profile page
     * 
     */
    
    public function show_settings(){

        $data = Product::select('products.id', 'products.id_user', 'products.name', 'products.price', 'products.descr', 'products.class', 'product_images.id as piid', 'product_images.img as image', 'product_images.is_main')
            -> where("id_user", "=", $_SESSION["id"]) 
            -> join('product_images', 'product_images.id_product', '=', 'products.id') 
            -> where("is_main", "=", 1) 
            -> get() 
            -> toArray();

        return view("user.settings", [ "data" => $data ]);
    }



    /**
     * Delete a user if he is allowed to
     * 
     *
     * @return redirect              Redirect to the / page
     * 
     */

    public function delete(){

        # TODO :
        #
        # Delete images of selled product / images on 
        # contact messages
        
        User::find($_SESSION["id"]) -> delete();

        session(["deletedaccount" => true]);
    }



    /**
     * Get date of creation of an account
     * 
     * @param User $user          User threw model binding
     * 
     * @return array       Result
     */

    public function creation(User $user) {
        return [
            "date" => "25/10/2008",
        ];
    }
}
