<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Contact;
use Illuminate\Http\Request;

use App\Http\Requests\ContactReq;
use App\Events\NotificationReceived;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ReceivedMessageNotification;



class Chatbox extends Controller {

    /**
     * Mark a contact message as readed
     *
     * @param Contact $contact      The contact model
     * @param string $id            The id of the message to mark
     * 
     * @return void   
     * 
     */

    public function mark_readed(int $id){

        Contact::where("id_contacted", "=", $_SESSION["id"])
        -> where("id_contactor", "=", $id)
        -> where("readed", "=", 0)

        -> update([ 
          "readed" => 1,
        ]);
    }



    /**
     * Get all the messages of the user and show them
     *
     * @param string $slug     A mail to show, or nothing
     * 
     * @return view            A view with all the messages sended and received
     * 
     */

    public function show($slug = false){
        
        include_once __DIR__ . "/../Utils/Style.php";

        $array_contacts = [];

        $all_msgs = Contact::where("id_contacted", $_SESSION["id"]) 
            -> orWhere("id_contactor", $_SESSION["id"])
            -> get(); 

        foreach($all_msgs -> reverse() as $msg) {

            $id = $msg -> id_contacted === $_SESSION["id"] ? $msg -> id_contactor : $msg -> id_contacted;
            
            if(!isset($array_contacts[$id])){
                $array_contacts[$id] = $msg -> toArray();
                $array_contacts[$id]["user"] = User::select("id", "mail", "avatar") -> where("id", $id) -> first() -> toArray();
            } 
        }

        if($slug) {

            # If the user is trying to contact himself
            if($slug === $_SESSION["mail"]){
                return to_route("contact.show") 
                    -> withErrors(["contact_yourself" => "You cant contact yourself"]);  
            }
            
            # If the user is closed 
            unset($_SESSION["closed"][$slug]);

            session(["id_slug" => User::where("mail", $slug) -> first() -> id]);

            # Mark all the messages as readed
            self::mark_readed(session("id_slug"));

            # Mark all notifs as readed
            User::find($_SESSION["id"]) -> notifications() -> where("data", "like", "%" . $slug . "%") -> delete();

            $messages = $all_msgs -> reject( function ($item) {
                return !($item -> id_contacted === session("id_slug") or $item -> id_contactor === session("id_slug"));
            });

            return view("users.chatbox", [ 
                "array_contacts" => $array_contacts, "user" => $slug, "messages" => $messages, "avatar" => $array_contacts[session("id_slug")]["user"]["avatar"]  
            ]);
        }

        return view("users.chatbox", [ 
            "array_contacts" => $array_contacts 
        ]);
    }



    /**
     * "Close" a conversation by hidding the user with who you are tchatting
     * in the contact sidebar 
     * 
     * @param string $user
     * 
     * @return redirect
     */

     public function close(string $user){

        $_SESSION["closed"][$user] = true;
     
        return to_route("contact.show");
    }


    /**
     * Store a message sended to a specific user
     *
     * @param Request $request      The request with all the informations
     * 
     * @return redirect                Redirect to the contact page with the right slug   
     * 
     */

    public function store(Request $request){

        # Get the slug
        $url = explode("/chatbox/", url() -> previous());
        

        # If there is no slug, the user is trying to send a message to no one
        if(!isset($url[1])){
            return abort(403);
        } else { 
            $mail = $url[1];
        }
        

        # Test if the user exists
        $id = User::where("mail", "=", $mail) -> get() -> toArray();

        
        if(empty($id)){
            return to_route("contact.show") 
                    -> withErrors(["contact_no_one" => "You cant contact this user !"]);
        } else {
            $id = $id[0]["id"];
        }

        # The users want to send an image 
        if(isset($request["img"])){
            $req = $request -> validate([
                "img" => "required|image|max:2000"
            ]);
            
            $img = $req["img"];
            
            if($img !== null && !$img -> getError()){
                
                # Store the image 
                $img_path = $req["img"] -> store("contact_img", "public");            
                
                
                # Add the img to the contact messages
                Contact::create([
                    "id_contactor" => $_SESSION["id"],
                    "id_contacted" => $id,
                    "content" => $img_path,
                    "type" => "img",
                    "time" => date('Y-m-d H:i:s'),
                    "readed" => false,
                ]);

            }
        } else {
            
            # Else try to store basic text content

            $req = $request -> validate([
                'content' => 'required',
            ]);
    
            # Add the message
    
            Contact::create([
                "id_contactor" => $_SESSION["id"],
                "id_contacted" => $id,
                "content" => htmlspecialchars($req["content"]),
                "time" => date('Y-m-d H:i:s'),
                "readed" => false,
            ]);

        }

        
        # Send a notification to the concerned user

        User::find($id) -> notify(new ReceivedMessageNotification([
            "content" => "From " . $_SESSION["mail"] . ".",
            "link" => "/chatbox/" . $_SESSION["mail"],
        ]));
    
        # Generate an event
        NotificationReceived::dispatch($id);

        return to_route("contact.user", $mail);
    }



    /**
     * Delete a message from a conversation if the user is allowed to
     *
     * @param Contact $contact     The message to remove through model binding
     * 
     * @return redirect            Redirect to the previous url   
     * 
     */

    public function delete(Contact $contact){
        
        $data = $contact -> toArray();
        
        if($data["id_contactor"] === $_SESSION["id"]){
            
            if($data["type"] === "img"){
                Storage::disk("public") -> delete($data['content']);
            }

            $contact -> delete();
        } else {
            return abort(403);
        }
    }



    /**
     * Show the view of the contact form to update a message or a 403 page if user
     * is not allowed to edit this contact message.
     *
     * @param Request $request
     * @param Contact $contact     The message through model binding
     * 
     * @return view | redirect           
     * 
     */
    
    public function show_form(Request $request, Contact $contact){

        $contact_message = $contact -> toArray();

        if($contact_message["id_contactor"] === $_SESSION["id"] and $contact_message["type"] === "text" and $request -> server("HTTP_HX_REQUEST") === "true"){
            return view("users.form_contact", [ "message" => $contact_message]);
        } else {
            return abort(403);
        }
    }



    /**
     * Update the message if the user is allowed to, else return 403 
     *
     * @param ContactReq $request       The request with all the valuable information
     * @param Contact $contact          The message to update through model binding
     * 
     * @return view | redirect           
     * 
     */

    public function edit(ContactReq $request, Contact $contact){
        
        if($contact["id_contactor"] === $_SESSION["id"] and $contact["type"] === "text"){

            $contact -> content = htmlspecialchars($request["content"]);
            $contact -> save();

            return redirect(url() -> previous() . "#msg" . $contact["id"]);
        } else {
            return abort(403);
        }
    }
}
