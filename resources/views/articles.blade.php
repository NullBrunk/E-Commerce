<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>E-Commerce</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/searchbar.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha
  * Updated: Mar 10 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

</head>

<body>

@include('layout/header')

  <!-- ======= Search Bar ======= -->
  <section class="margintop d-flex align-items-center">
    <form>
      <input id="input" type="text" placeholder="Search something ...">
    </form>

  </section>

  <section id="portfolio" class="portfolio">
    <div class="container">
      <div class="row"  id="container">
    
      </div>
  </div>
</section>

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    
    <!-- Vendor JS Files -->
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
    
    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
  

<script>

const input = document.getElementById("input");
const container = document.getElementById("container");

function addproduct(id, image, price, classe){
  container.innerHTML += `         

  <div class="col-md-3 portfolio-item ${classe}">
              <div class="portfolio-wrap" style="border-radius: 5px;">
              <a href="/details/${id}">
                <img src="/storage/product_img/${image}.png" class="img-fluid imgpres" alt="">
              </a>
              <div class="portfolio-info">
                
                <div class="portfolio-links">
                    <p class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><strong>${price} $</strong></p>
                  </div>

                </div>
              </div>

            </div>`
}

input.addEventListener('input', 

async function(event) {
    let content = event.target.value;
    let resp = await fetch(window.location.href+"/../api/products/"+content);
    
    if(resp.status !==  404){
        const data = await resp.json();
        
        container.innerHTML = ""
        for(let i=0; i<data.length; i++){
          elem = data[i];
          console.log(elem)
          addproduct(elem.id, elem.image, elem.price, elem.class)
        }
    }
});
  
  </script>
      
</body>

</html>