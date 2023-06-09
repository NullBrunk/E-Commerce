<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{ $data["name"] }}</title>
        
        <meta content="" name="description">
        <meta content="" name="keywords">

        {{-- Google font --}}
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        {{-- CSS --}}
        <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">
        <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">
        
        {{-- JS --}}
        <script src="../assets/js/sweetalert2.js"></script>
        <script src="../assets/js/alert.js"></script>

    </head>

    <body>
        <script>

            // Afficher le menu ou le masquer lorsque l'on clique sur les 3 points
            function menu(id){
                const menu = document.getElementById(id);
                menu.classList.toggle("none")
            }

            // Afficher le foromulaire pour poster un commentaire
            // quand on clique sur "Click here to post a comment"
            function showcomm(){
                const form = document.getElementById("formcomm");
                const chevron = document.getElementById("chevron");
                const span = document.getElementById("commcontent");

                form.classList.toggle("none");

                // On passe d'un chevron vers la droite a un chevron vers le bas
                // et inversement 

                chevron.classList.toggle("bx-chevron-right");
                chevron.classList.toggle("bx-chevron-down");

                if(span.innerText==="Click here to close this menu"){
                    span.innerText = "Click here to post a comment "
                }
                else {
                    span.innerText = "Click here to close this menu"
                }
            }
        </script>

        @include('../layout/header')

    
        <main id="main" >

            {{-- On se positionne en dessous de la navbar --}}
            <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                <div class="container">
                    <ol></ol>
                </div>
            </section>


            {{-- 
                Affichage du produit à gauche
                et de sa description à droite
            --}}
            <section id="portfolio-details" class="portfolio-details" style="padding-bottom: 0px;">
                <div class="container">
                    <div class="row gy-4 whenigrowibecomeablock">
                        <div class="col-lg-8 takefull" style="width: 50%; display: flex;" >
                            <div style="margin: auto;">

                                {{-- Image du produit à gauche --}}
                                <img data-aos="fade-right" style="width: 85% !important;" src="../storage/product_img/{{ $data["image"] }}" alt="">
                            </div>
                        </div>

                        {{-- 
                            La description texuelle du produit 
                        --}}
                        <div data-aos="fade-left" class="col-lg-4 marginlr"  style="color: white; background-color: #324769 !important; border-radius: 12px; width: 50%; height: 75vh; display: flex; flex-direction: column; ">
                            <div class="portfolio-info container" style="padding-bottom: 10px; padding-top: 30px !important;" >
                                <h2>{{$data["name"]}}</h2>
                                <hr>
                            </div>

                            <div class="portfolio-info" style="position: relative;   padding-top: 0px !important;  height: 65%;">
                                <p class="descr">

                                    {{-- 
                                        Génerer des sortes de listes a puces.
                                        - a
                                        - b 

                                        Deviendra 
                                        · a
                                        · b 
                                    --}}
                                    <?php

                                        $text = str_replace(
                                            "\r\n-", 
                                            "\r\n<i class='bi bi-dot'></i>", 
                                            htmlspecialchars($data['descr'])
                                        );

                                        $text = nl2br($text);
                                    
                                    ?>
                                    
                                    {!! $text !!}

                                </p>
                            </div>
                        
                            {{-- 
                                Si c'est l'utilisateur courant qui vend le produit,
                                on affiche un bouton d'édition du produit 
                            
                                Sinon, on affiche un bouton permettant d'ajouter
                                ce produit au panier
                            --}}


                            {{-- Pas connecté OU le vendeur n'est pas nous --}}
                            @if(!isset($_SESSION["mail"]) or (isset($_SESSION["mail"]) && $data['mail'] !== $_SESSION["mail"]))

                                <form  class="navbar formshow" method="post" action="{{route("cart.add")}}">  
                                    @csrf   
                                    <button class="addtocart" type="submit">BUY NOW<i  style="font-weight: bold !important;" class="bi bi-cart-plus"></i></button>
                                    <input type="hidden"  name="id" value="{{$data['pid']}}">
                                </form>

                            {{-- Nous sommes le vendeur --}}
                            @else

                                <form class="navbar formshow" method="get" action="{{route("product.updateform", $data['pid'])}}" >  
                                    @csrf   
                                    <button  class="addtocart" type="submit">EDIT PRODUCT<i style="font-weight: bold !important;" class="bi bi-cart-check"></i></button>
                                </form>
                            @endif

                        </div>

                        <br>
                        <hr>

                        <div id="info" data-aos="fade-right">
                            {{-- Petit tableau descriptif du produit --}}
                            <h2>Product information</h2>

                            <table>
                                <tr>
                                    <th>Name</th>
                                    <td>{{$data["name"]}}</td>
                                </tr>

                                <tr>
                                    <th>Seller</th>
                                    <td>
                                        {{-- 
                                            Si nous ne sommes pas le vendeur, le mail de celui ci
                                            est afficher dans une balise a, nous permettant
                                            (en cliquant sur le lien), d'etre rediriger vers la page pour
                                            le contacter.
                                        --}}

                                        @if(!isset($_SESSION["mail"]) or (isset($_SESSION["mail"]) && $data['mail'] !== $_SESSION["mail"])) 
                                            <a href="{{route('contactuser', $data['mail'])}}">
                                                {{ $data['mail'] }}
                                            </a> 
                                        @else 
                                            {{ $data['mail'] }} 
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    {{-- 
                                        Au lieu d'afficher filter-laptop on affiche Laptop
                                    --}}

                                    <th>Category</th>
                                    <td>{{ ucfirst(explode('-', $data["class"])[1]) }}</td>
                                </tr>

                                <tr>
                                    <th>Price</th>
                                    <td>{{ $data['price'] }}$</td>
                                </tr>

                                @if($rating)
                                    <tr>
                                        <th>Reviews</th>
                                            <td>

                                                {{-- On affiche le vrai nombre d'étoiles arrondis au dixième --}}
                                                {{ $rating['real'] }} 

                                                {{-- 
                                                    On effectue une boucle for pour afficher 
                                                    le nombre d'étoiles en jaune
                                                --}}
                                                @for($i=0; $i<$rating['round']; $i++)
                                                    <i class="bi bi-star-fill" style="color: #de7921;"></i>
                                                @endfor

                                                {{-- 
                                                    On affiche éventuellement une demi étoile jaune
                                                    si le nombre des dixiemes du vrai rating est 
                                                    supérieur a .5,
                                                    si ce n'est pas le cas on affiche une étoile blanche
                                                --}}

                                                @if($rating["real"] >= $rating["round"] + 0.5)
                                                    <i style="color: #de7921;" class="bi bi-star-half"></i>
                                                @elseif($rating["real"] != 5.0)
                                                    <i class="bi bi-star" style="color: #de7921;"></i>
                                                @endif

                                                {{--
                                                    On affiche rating - 1 étoiles en blanche
                                                    (-1 car on a deja affiché soit une demi étoile soit une etoile blanche dans le if juste au dessus)    
                                                --}}

                                                @for($i = $rating['round'] + 1; $i < 5; $i++)
                                                    <i class="bi bi-star" style="color: #de7921;"></i>
                                                @endfor
                                            
                                                <a href="#comments"> {{$rating["rate"]}} ratings</a>
                                        </td>
                                    </tr>
                                @endif
                                
                            </table>

                            <p style="margin-top: 10vh;">
                            <hr>

                        </div>
                    </div>
                </div>
            </section>


            <section id="breadcrumbs" style="padding-top: 1%;" class="breadcrumbs">
                <div class="container" data-aos="fade-top-right">

                    <ol></ol>

                    
                    <h2>Comments</h2>

                    @if(!isset($_SESSION["logged"]))
                        <div class="alert alert-info">
                            Login to post a comment.
                        </div>
                    @else
                        <p class="commentlink" id="commenttext" onclick="showcomm()"><span class="amazonpolice" id="commcontent">Click here to post a comment</span> <i id="chevron" class="bx bx-chevron-right"></i></p>

                            <div id="formcomm" class="commentsbox none" >
                                
                                <form method="post" action="{{ route("comment.add", $data["id_user"]) }}" style="width:100%;">
                                    @csrf
                                    <div class="title" style="height: 13vh;;">
                                        Title of your comment <abbr>*</abbr>
                                        <input name="title" type="text" value="{{old("title")}}" placeholder="Example: Nice product !" class="titlebar" maxlength="45">
                                    </div>
                                    
                                    <div class="contentcomment title" style="margin-top: 10px; height: 23vh;">
                                        Your comment <abbr>*</abbr>
                                        <textarea placeholder="To help you write a useful comment for our CyberShop:

    - Explain to us why you chose this note?
    - What did you like best about this product?
    - Who would you recommend it to?"
                                        class="commentbar" name="comment" type="text">{{old("comment")}}</textarea>
                                    </div>

                                    <input name="id" type="hidden" value="{{$data['pid']}}">
                                    
                                    <br>
                                    <p class="title" style="margin-bottom: 0; margin-top: 10px; ">Rating <abbr>*</abbr></p>
                                    <div class="rating">
                                        <input type="radio" id="star5" name="rating" value="5">
                                        <label for="star5"></label>
                                        <input type="radio" id="star4" name="rating" value="4">
                                        <label for="star4"></label>
                                        <input type="radio" id="star3" name="rating" value="3">
                                        <label for="star3"></label>
                                        <input type="radio" id="star2" name="rating" value="2">
                                        <label for="star2"></label>
                                        <input type="radio" id="star1" name="rating" value="1">
                                        <label for="star1"></label>
                                    </div> 
                                    <br>
                                    <input class="commbutton" type="submit" value="Post comment">
                                    <br>

                                </form>
                                <p style="margin-bottom: 12vh;">
                            </div>
                        
                        @endif

                        <p style="margin-bottom: 7vh;">

                        <div id="comments">

                            {{-- On vérifie si l'api des commentaires a renvoyé quelque chose--}}
                            @if($comments)

                                @foreach(json_decode($comments, true) as $comm)
                                    <div id="{{ "div" . $comm["id"] }}" class="comments">          
                                        <div class="profile">
                                            
                                            <p class="profile">
                                                <i style="font-size:32px; color:#007185;" class="bi bi-person-circle"> </i>
                                                <p class="name">
                                                    {{--
                                                        Si nous ne sommes pas le commentateur, son nom est affiché dans un a.
                                                        On peut ainsi le contacter en un clic.
                                                    --}}
                                                    @if(isset($_SESSION['mail']) and ($_SESSION["mail"] === $comm["mail"]))
                                                        {{ $comm["mail"] }}
                                                    @else
                                                        <a style="color: #007185" href="{{ route("contactuser", $comm["mail"]) }}">{{$comm["mail"]}}</a>
                                                    @endif

                                                </p> 


                                                {{-- 
                                                    Si c'est nous qui avons posté le commentaire,
                                                    nous proposons un petit menu pour éditer ou supprimer
                                                    ce dernier.
                                                --}}

                                                @if(isset($_SESSION["mail"]) && $comm["mail"] === $_SESSION["mail"])           
                                                    <p class="trash"> 
                                                        <i id="" onclick='menu("{{$comm["id"]}}")' style="margin-top: 16px;" class="dots bx bx-dots-vertical-rounded"></i>     
                                                    </p>
                                                @endif 

                                            </div>
                                        </div>

                                        <div id="{{$comm['id']}}" class="none">
                                        
                                            {{-- 
                                                Fonction qui permet de demander confirmation a l'utilisateur
                                                quand il supprime un commentaire. 
                                            --}}

                                            <script>
                                                function deletecomm(commid){
                                                    Swal.fire({
                                                        title: 'Are you sure?',
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#293e61',
                                                        cancelButtonColor: '#af2024',
                                                        confirmButtonText: 'Yes, delete it!'
                                                    }).then((result) => {
                                                        // On redirige vers la page permettant de supprimer le commentaire
                                                        if (result.isConfirmed) {
                                                            window.location.href = "/comments/delete/{{ $data['pid'] }}/" + commid 
                                                        }
                                                    })
                                                }
                                            </script>

                                            {{--
                                                <a> stylisé comme un bouton qui redirige vers le 
                                                formulaire de modification de commentaires
                                            --}}
                                            <a href="{{route("comment.update_form", $comm["id"])}}" 
                                                id="{{$comm['id'] . 'updatebutton'}}" class="btn btn-primary menu update" style="width: 39px; margin-left: auto !important;">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            {{--
                                                Button qui appelle la fonction de confirmation de suppression
                                            --}}
                                            <button id="{{$comm['id'] . 'deletebutton'}}" onclick="deletecomm({{ $comm['id']}})" class="btn btn-primary menu" style="margin-top: 4px; width: 39px; margin-left: auto;">
                                                <i class="bi bi-trash2-fill"></i>
                                            </button>

                                        </div>

                                        <span class="titlecomm">{{ $comm["title"] }}</span>

                                        {{-- 
                                            Pour chaque commentaire on affiche le nombre d'étoile jaunbe et le nombre
                                            d'étoile blanche.    
                                        --}}
                                        <div class=stars>

                                            @for($i=0; $i<$comm["rating"]; $i++)
                                                <i class="bi bi-star-fill" style="color: #de7921;"></i>
                                            @endfor

                                            @for($i = $comm["rating"]; $i < 5; $i++)
                                                <i class="bi bi-star" style="color: #de7921;"></i>
                                            @endfor

                                            <span class="at">
                                                {{ $comm["writed_at"] }}
                                            </span>
                                        </div>

                                                        

                                        <div class="comment">
                                            {!! nl2br($comm["content"]) !!}
                                            <hr>
                                        </div>
                                                            
                                @endforeach
                            @endif

                    </div>
                </div>
            </section>
        </main>

        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <script src="../assets/vendor/aos/aos.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/vendor/glightbox/js/glightbox.js"></script>
        <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../assets/vendor/waypoints/noframework.waypoints.js"></script>

        <script src="../assets/js/main.js"></script>

    </body>
</html>



{{-- Gestion des erreurs --}}
@if($errors -> has('rating'))
    <script>
        alert_and_scroll("You need to give a rating !")
    </script>
@endif

@if($errors -> has('title'))
    <script>
        alert_and_scroll("Title is required !")
    </script>
@endif
                
@if($errors->has('comment') or $errors->has('id'))
    <script>
        alert_and_scroll("A comment is required !")
    </script>
@endif

{{-- Gestion des messages de succès --}}
@if(isset($_SESSION['done']) && ($_SESSION['done'] === "updated")  )
    <script>
        success("Product updated successfully.")
    </script>

    <?php
        unset($_SESSION['done'])
    ?>
@endif

@if(isset($_SESSION['done']) )
    <script>
        success("Your comment has been posted !", "Posted")
    </script>

    <?php
        unset($_SESSION['done'])
    ?> 
@endif

@if(isset($_SESSION['updated']) )
    <script>
        success("Your comment has been updated !")
    </script>

    <?php
        unset($_SESSION['updated'])
    ?> 
@endif