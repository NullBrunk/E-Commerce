<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Tmp_images;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Product_images;
use App\Http\Requests\StoreReq;

use App\Http\Requests\UpdateProduct;
use Illuminate\Support\Facades\Storage;

class Products extends Controller
{ 

    /**
     * Get the details of a given product
     *
     * @param Product $product      The product through model binding 
     * 
     * @return view                 A view with all the details of
     *                              the product, including comments
     *                              technicals details, rating etc. 
     * 
     */

     public function get_details(Product $product){
                  
        include_once __DIR__ . "/../Utils/Style.php";

        $data = $product;
        $stylised_description = style($data -> descr);

        $images = $product -> product_images() -> orderBy("is_main", "desc") -> get() -> toArray();

        # Delete all notifications linked to it
        if(!isset($_SESSION))
            session_start();

        if(isset($_SESSION["logged"])){

            # If i m the seller of this product
            if($_SESSION["id"] === $data -> id_user){

                # Delete all notifs linked to it
                User::find($_SESSION["id"]) 
                    -> notifications() 
                    -> where("data", "like", "%" . "\"id_product\":\"" . $product -> id . "\"" . "%") 
                    -> delete();
            }
        }

        # Get all the comments of the product
        $comments = $product -> comments() -> desc() -> get();


        $rating = self::rating($product);


        return view("products.details", [
            "stylised_description" => $stylised_description, 
            "comments" => $comments, 
            "product" => $product, 
            "images" => $images, 
            "rating" => $rating
        ]);
    
    }



    /**
     * Search through all the product with a LIKE operator
     *
     * @param Request $request
     * @param string $category   The category of the product
     *  
     * @return view
     * 
     */

    
    public function search(Request $request, string $category)
    {
        $rating = [];

        if($request -> input("q")){
            $search = $request -> input("q");
        } else {
            return back();
        }

        if(!in_array($category, [ "all", "gaming", "informatics", "dresses", "food", "furnitures", "vehicles", "appliances" , "other"])){
            return abort(404);
        }
       
        
        if($category === "all"){
            $query = Product::select('products.id', 'products.id_user', 'products.name', 'products.price', 'products.descr', 'products.class', 'product_images.id as piid', 'product_images.img', 'product_images.is_main')

            -> join('product_images', 'product_images.id_product', '=', 'products.id') 
            -> where("is_main", "=", 1) 
            -> where("name", "like", "%" . $search . "%");

        } else {
            $query = Product::select('products.id', 'products.id_user', 'products.name', 'products.price', 'products.descr', 'products.class', 'product_images.id as piid', 'product_images.img', 'product_images.is_main')
            -> join('product_images', 'product_images.id_product', '=', 'products.id') 
            -> where("is_main", "=", 1) 
            -> where("class", "=", $category) 
            -> where("name", "like", "%" . $search . "%") ;
        }

        $max_price = $request -> input("mp");
        if($max_price){
            $query -> where("price", "<", (int)$max_price) -> desc();
        }

        $data = $query -> desc() -> paginate(4);
           
        
        foreach($data as $d){

            if(!empty(self::rating($d))){
                $value = self::rating($d)["icons"]; 
            } else {
                $value = "";
            }

            $rating[$d["id"]] = $value;
        }

         
        // If HTMX is doing the request, don't display the navbar
        if($request -> server("HTTP_HX_REQUEST") === "true" ){

            return view("products.pagination", 
                [
                    "products" => $data, 
                    "name" => $category, 
                    "rating" => $rating, 
                    "search" => $search,
                    "max_price" => $max_price
                ]);
        } else {
            return view("products.categories", 
                [
                    "products" => $data, 
                    "name" => $category, 
                    "rating" => $rating, 
                    "search" => $search,
                    "number" => $query -> count(),
                    "max_price" => $max_price
                ]);
        }


    }

    /**
     * Store a product from the /sell page.
     *
     * @param Request $request       The request with all the informations
     * @param Tmp_images $tmp_image   The temporary images model
     *  
     * @return view                  Return the view of /sell (will change)
     * 
     */

     public function store(StoreReq $request,Tmp_images $tmp_image){      

         
        $req = $request -> validated();

        # Check if te user category is a valid category 

        if(!in_array($req["category"], [ 
            "informatics", 
            "dresses",
            "gaming",
            "food",
            "other",
            "furnitures", 
            "vehicles", 
            "appliances",
            "other"
        ])){
            return abort(403);
        }


        # Store the product 

        $product = Product::create([
            "id_user" => $_SESSION["id"],
            "name" => $req["name"],
            "descr" => htmlspecialchars($req["description"]),
            "price" => $req["price"],
            "class" => $req["category"],
        ]);


        $checksum = md5($_SESSION["pass"] . $request["_token"]);

        $tmpimages = $tmp_image -> where("checksum", "=", $checksum) -> get();
        foreach($tmpimages as $img){

            $name = Str::random(40)  . "." . $img -> extension ;

            Storage::copy("tmp/" . $img -> folder . "/" . $img -> file, "product_img/" . $name, "public");

            Product_images::create([
                "id_product" => $product -> id,
                "img" => $name,
                "is_main" => $img -> is_main,
            ]);

            Storage::deleteDirectory("tmp/" . $img -> folder);

            $img -> delete();
        }
        return to_route("details", $product -> id) -> with("selled", "The product has been succesfully selled !");
    }



    /**
     * Delete a given product if the user is allowed to 
     *
     * @param int $id               The id of the product
     *  
     * @return redirect             Redirection to / if success, or to a 403
     *                              page if not.
     * 
     */

    public function delete($id){

        # Check if the product exists and is selled by the current user

        $data = Product::findOrFail($id) -> toArray();

        if($data["id_user"] !== $_SESSION["id"] or empty($data)){
            return abort(403);
        }
       
        $path_all_images = Product_images::where("id_product", "=", $data["id"]) -> get() -> toArray();
        foreach($path_all_images as $img)
        {
            Storage::delete("product_img/" . $img["img"]);
        }

        # Delete the product itself
        Product::where("id", "=", $id) -> delete();
    }


    /**
     * Show an edition form to update a product if the user is allowed to 
     * 
     * @param Product $product      Product through model binding
     *  
     * @return abort | view         a 403 page if he is not allowed
     *                              a view if he is.
     * 
     */

    public function edit_form(Product $product){
        
        if($_SESSION["id"] !== $product -> id_user){
            return abort(403);
        }

        return view("products.form_product", ["data" => $product, "images" => $product -> product_images() -> orderBy("is_main", "desc") -> get() -> toArray()]);
    }



    /**
     * Edit a product if the user is allowed to
     *
     * @param UpdateProduct $request     The informations of the new product 
     * @param Product $id                The product through model binding
     *  
     * @return redirect                  A 403 page if he is not allowed
     *                                   redirect to the page of the updated product
     *                                   if he is allowed to.
     * 
     */

    public function edit(UpdateProduct $req, Product $product){

        if($product -> id_user !== $_SESSION['id']){
            return abort(403);
        }

        # If the user clicked on the delete button 

        if($req["submit"] === "delete"){
            self::delete($product -> id);

            return to_route("root") -> with("deletedproduct", "The product has been deleted successfully.");
        }

        
        # Test if the given category is valid
        
        if(!in_array($req["category"], [ 
            "informatics", 
            "dresses",
            "gaming",
            "food",
            "other",
            "furnitures", 
            "vehicles", 
            "appliances",
            "other"
        ])){
            return abort(403);
        }

        $product 
        -> update([
            "name" => $req["name"],
            "price" => $req["price"],
            "descr" => htmlspecialchars($req["description"]),
            "class" => $req["category"],
        ]);

        $checksum = md5($_SESSION["pass"] . $req["_token"]);

        $tmpimages = Tmp_images::where("checksum", "=", $checksum) -> get();

        foreach($tmpimages as $img){

            $name = Str::random(40)  . "." . $img -> extension ;

            Storage::copy("tmp/" . $img -> folder . "/" . $img -> file, "product_img/" . $name, "public");

            Product_images::create([
                "id_product" => $product -> id,
                "img" => $name,
                "is_main" => $img -> is_main,
            ]);

            Storage::deleteDirectory("tmp/" . $img -> folder);

            $img -> delete();
        }

        return to_route("details", $product -> id) -> with("updated", "Product updated successfully.");
    }



    /**
     * Calculate the different rating (rounded, real, number of rates) 
     *
     * @param Product $product      A product through model binding
     *  
     * @return array | redirect     An array with all the valuable informations
     *                              A 404 page if no one rated,
     * 
     */
    
    public function rating(Product $product){

        $numbers_of_rate = 0;
        $total_rating = 0;
        $toshow = "";

        foreach($product -> comments as $rat){
            $numbers_of_rate++;
            $total_rating += (int)$rat -> rating;
        }

        if($numbers_of_rate === 0){
            return [];
        }

        $round = intdiv($total_rating, $numbers_of_rate);
        $real = round($total_rating / $numbers_of_rate, 1);
       

        # On effectue une boucle for pour afficher 
        # le nombre d'étoiles en jaune
        
        for($i=0; $i<$round; $i++){
            $toshow .= '<i class="bi bi-star-fill" style="color: #de7921;"></i>';
        }
        
        # On affiche éventuellement une demi étoile jaune
        # si le nombre des dixiemes est supérieur à .5,
        # Si ce n'est pas le cas on affiche une étoile blanche

        if($real >= $round + 0.5){
            $toshow .= '<i style="color: #de7921;" class="bi bi-star-half"></i>';
        }
        elseif($real != 5.0){
            $toshow .= '<i class="bi bi-star" style="color: #de7921;"></i>';
        }

        
        # On affiche rating - 1 étoiles en blanche
        # (-1 car on a deja affiché soit une demi étoile soit une etoile blanche dans le if juste au dessus)    

        for($i = $round + 1; $i < 5; $i++){
            $toshow .= '<i class="bi bi-star" style="color: #de7921;"></i>';
        }

        return [
            "icons" => $toshow,
            "round" => $round,
            "rate" => $numbers_of_rate,
            "real" => $real,
        ];
    }



    /** 
     * Show products of a given category (in a little card)
     * 
     * @param string $slug             The category name
     * 
     * @return view
    */

    public function show(Request $request, $slug){

        if(!in_array($slug, [ "all", "gaming", "informatics", "dresses", "food", "other", "furnitures",  "other", "vehicles", "appliances" ])){
            return abort(404);
        }
       
        // If HTMX is doing the request, don't display the navbar
        if($request -> server("HTTP_HX_REQUEST") === "true" ){
            $view = "products.pagination";
        } else {
            $view = "products.categories";
        }

        
        if($slug === "all"){

            $data = Product::select('products.id', 'products.id_user', 'products.name', 'products.price', 'products.descr', 'products.class', 'product_images.id as piid', 'product_images.img', 'product_images.is_main')
            -> join('product_images', 'product_images.id_product', '=', 'products.id') 
            -> where("is_main", "=", 1) 
            -> desc()  
            -> paginate(4); 

        } else {

            $data = Product::select('products.id', 'products.id_user', 'products.name', 'products.price', 'products.descr', 'products.class', 'product_images.id as piid', 'product_images.img', 'product_images.is_main')
            -> join('product_images', 'product_images.id_product', '=', 'products.id') 
            -> where("is_main", "=", 1) 
            -> desc() 
            -> where("class", "=",  $slug)
            -> paginate(4); 
        }

            
        return view($view, ["products" => $data, "name" => $slug]);

    }



    /**
     * Pass all the product details in JSON
     *
     * @param Product $product         The product through model binding
     *
     * @return array
     */
    public function api_details(Product $product){

        $img_url = $product -> product_images() -> where("is_main", "=", true) -> first() -> img;
        $data = $product -> toArray();

        # Don't leak the id of the seller
        unset($data["id_user"]);

        # Add the main image url of the product
        $data["img"] = $img_url;

        return $data;
    }



    /**  
     *  Remove an image of a product
     *
     *  @param Product_images $id       the image through model binding
     * 
     * @return      401 : Image is the main image, you are unauthorized
     *              403 : Image is not an image of a product that you sell , 403
     *              
     *              200 : Removed images succesfully
    */
    
    public function remove_image(Product_images $image){
        
        if($image -> is_main) {
            return abort(401);
        }
        
        if($image -> product -> id_user !== $_SESSION["id"]){
            return abort(403);
        }

        Storage::delete("product_img/" . $image -> img);
        $image -> delete();

        return response('Ok', 200);
    }



    /**
     * Fonction to change the main img of a product
     * 
     * @param Product_images $image      The image through model binding
     * 
     */

    public function change_main(Product_images $image) {
        
        if($image -> product -> id_user !== $_SESSION["id"]) {
            return abort(403);
        }

        Product_images::where("id_product", $image -> id_product) 
            -> where("is_main", true) 
            -> update([
                "is_main" => false
            ]);

        $image -> is_main = true;
        $image -> save();
    }
}
