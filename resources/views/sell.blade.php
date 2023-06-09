<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <title>Sell a product</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        {{-- Google font --}}
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        
        {{-- CSS --}}
        <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="../assets/vendor/aos/aos.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">
        
        {{-- JS --}}
        <script src="../assets/js/sweetalert2.js"></script>
        <script src="../assets/js/alert.js"></script>

    </head>

    <body>

        @include('../layout/header')
            <main id="main">

                <section id="breadcrumbs" class="breadcrumbs" style="padding-top: 86px; padding-bottom: 0px !important;">
                    <div class="container">
                        <ol></ol>
                        <h2></h2>
                    </div>
                </section>

                <section id="portfolio-details" class="portfolio-details">
                
                    <form method="post" action="{{ route("product.sell") }}" enctype="multipart/form-data">  
                        <div class="container">
                            <div class="row gy-4">
                                <div class="col-lg-8">
                                    <div class="portfolio-details-slider swiper">
                                        <div class="swiper-wrapper align-items-center">                
                                            <input type="file" name="product_img" {{ old("product_img") }}>       
                                            <br><br><br><br>
                                        </div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>

                            <div class="col-lg-4"  style="color: white; background-color: #324769 !important; border-radius: 12px;">
                                <div class="portfolio-info" style="padding-bottom: 10px;" >
                                    <h2>Product information</h2>
                                    <hr>
                                    <ul>

                                        <li><strong>Name    : <input placeholder="Brown Mushroom" class="input-beautify" type="text" name="name" value="{{old("name")}}" autofocus></strong></li>
                                        <li><strong>Price    : <input placeholder="From 0.00 to 999md " style="margin-left: 13% !important;" class="input-beautify" type="text" name="price" value="{{old("price")}}"></strong></li>
                                        <li>
                                            <strong>Category : 

                                                <select class="select-beautify" name="category">
                                                    <option value="filter-laptop" >Informatics</option>
                                                    <option value="filter-dresses">Dresses</option>
                                                    <option value="filter-gaming" >Gaming</option>
                                                    <option value="filter-food" >Food</option>
                                                    <option value="filter-other" >Other</option>
                                                </select>

                                            </strong>
                                        </li>                
                                        <br>

                                        <h3></h3>

                                    </ul>
                                </div>

                                <div class="portfolio-info">
                                    <p class="descr">
                                        <textarea placeholder="Description of the product" class="textarea-beautify" type="text" name="description">{{old("description")}}</textarea>      
                                    </p> 
                                </div>
                            
                                @csrf      
                                <input class="addtocart" style="margin-left: 33%; margin-top:0px; margin-bottom: 3%;" name="submit" type="submit" value="Sell !">
                            </div>
                        </form>
                    </div>
                </div>
            
            </section><!-- End Portfolio Details Section -->
            <hr>
        </main><!-- End #main -->


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

@if($errors -> has("product_img") or isset($_SESSION["error"]))
    <script>
        alert("Must be an image, and smaller than 2MO !")
    </script>

    <?php
        unset($_SESSION["error"]);
    ?>

@elseif(isset($_SESSION["done"]))

    <script>
        success("Succesfully added your product !", "Added")
    </script>

    <?php
        unset($_SESSION["done"]);
    ?>     
@endif               

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