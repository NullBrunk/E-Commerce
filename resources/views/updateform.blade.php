<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>{{$data["name"]}}</title>

        <meta content="" name="description">
        <meta content="" name="keywords">

        {{-- Google font --}}
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

        {{-- CSS --}}
        <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../../assets/css/style.css" rel="stylesheet">
        
        {{-- JS --}}
        <script src="../../assets/js/sweetalert2.js"></script>
        <script src="../../assets/js/alert.js"></script>

    </head>

    <body>

        @include('../layout/header', [ "dotdotslash" => "../"])

            <main id="main">

                {{-- On se positionne en bas de la navbar --}}
                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    <div class="container">
                        <ol></ol>
                    </div>
                </section>



                <section id="portfolio-details" class="portfolio-details">
                    
                    <form method="post" action="{{ route("product.update", $data['pid']) }}" enctype="multipart/form-data">  

                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-8 takefull" style="width: 50%; display: flex;" >
                                    <div class="portfolio-details-slider swiper">
                                        <div class="swiper-wrapper align-items-center">
                                            
                                            {{-- Pour l'instant l'image n'est pas modifiable --}}
                                            
                                            <img style="width: 90% !important;" src="../../storage/product_img/{{ $data["image"] }}" alt="">
                                            <br>
                                            <br>
                                                    
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>

                                <div class="col-lg-4 w-50"  style="color: white; background-color: #324769 !important; border-radius: 12px;">
                                    <div class="portfolio-info" style="padding-bottom: 10px;" >
                                        <h2>Product information</h2>
                                        <hr>
                                        <ul>
                            
                                            {{-- On préremplit tous les champs --}}

                                            <li>
                                                <strong>Name    : 
                                                    <input class="input-beautify" 
                                                    type="text" name="name" 
                                                    value="{{$data["name"]}}">
                                                </strong>
                                            </li>

                                            <li>
                                                <strong>Price    : 
                                                    <input style="margin-left: 12% !important;" 
                                                    class="input-beautify" type="text" 
                                                    name="price" value="{{$data["price"]}}">
                                                </strong>
                                            </li>
                                            
                                            <li>
                                                <strong>Category : 
                                                    <select class="select-beautify" id="select" name="category">
                                                        <option value="filter-laptop" >Informatics</option>
                                                        <option value="filter-dresses">Dresses</option>
                                                        <option value="filter-gaming" >Gaming</option>
                                                        <option value="filter-food" >Food</option>
                                                        <option value="filter-other" >Other</option>
                                                    </select>        
                                                </strong>

                                                <script>
                                                    // On préselectionne le bon <option>
                                                    document.getElementById("select").value = "{{ $data['class']}}" 
                                                </script>

                                            </li>

                                            <br>
                                            <h3></h3>
                                        
                                        </ul>
                                    </div>

                                    <div class="portfolio-info">
                                        <p class="descr">
                                            <textarea 
                                                placeholder="Description of the product" 
                                                id="textarea" class="textarea-beautify" 
                                                type="text" name="description"
                                            >
                                                {{$data["descr"]}}
                                            </textarea>      
                                        </p> 
                                    </div>
                                
                                    @csrf
                                    
                                    {{-- 
                                        Depuis ce formulaire on peut soit editer 
                                        le produit soit arreter de le vendre en le 
                                        supprimant
                                    --}}

                                    <button class="addtocart buttonupdate" name="submit" value="update">UPDATE<i class="bi bi-check"></i></button>
                                    <button class="deleteprod" style="margin-top:0px; margin-left: 3%; margin-bottom: 3%;"  name="submit" value="delete">DELETE <i class="bi bi-x"></i></button>

                                </div>
                            </div>
                        </div>
                    </form>
                    
                </section>
            <hr>
        </main>


        <div id="preloader"></div>
        <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


        <script src="../../assets/vendor/aos/aos.js"></script>
        <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="../../assets/vendor/waypoints/noframework.waypoints.js"></script>

        <script src="../../assets/js/main.js"></script>

    </body>

</html>

{{-- Gestion des erreurs --}}
@if($errors -> has("name"))
    <script>
        alert("The name is required and must be smaller than 45 characters !")
    </script>
@endif

@if($errors -> has("price"))
    <script>
        alert("The price is required and must be an integer !")
    </script>
@endif

@if($errors -> has("description"))
    <script>
        alert("A description is required !")
    </script>
@endif


{{-- Message de succès --}}

@if(isset($_SESSION["done"]))
    <script>
        success("Successfully added the product !", "Added !")
    </script>

    <?php
    unset($_SESSION["done"]);
    ?>     
@endif               
