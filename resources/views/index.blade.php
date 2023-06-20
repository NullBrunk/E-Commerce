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


</head>

<body>

@include('layout/header')

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">

    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"  data-aos-delay="200">
          <h1>E-commerce</h1>
          <h2>An e-Business website with Laravel & MariaDB for the backend, and Bootstrap for the front-end</h2>
          
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
          <img src="assets/img/hero-img.png" class="img-fluid animated" style="margin-left: 20%; height: 60%; margin-top: 20%;" alt="">
        </div>
      </div>
    </div>

  </section><!-- End Hero -->


  <!-- 
            <div class="col-lg-12 d-flex justify-content-center">
            <ul id="portfolio-flters">
  
            </ul>
          </div>

        </div>
  -->

  <section id="portfolio" class="portfolio">
      <div class="container" data-aos="fade-up">


        <div class="row" data-aos="fade-up" data-aos-delay="100">
          <div class="col-lg-12 d-flex justify-content-center">
            <ul id="portfolio-flters">
            <li data-filter="*" class="filter-active">All</li>
              <li data-filter=".filter-laptop">Laptops & Tablets</li>
              <li data-filter=".filter-gaming">Gaming accessories</li>
              <li data-filter=".filter-health">Health & Personal Care</li>
              <li data-filter=".filter-dresses">Dresses</li>
              <li data-filter=".filter-beauty">Beauty picks</li>      
            </ul>
          </div>
        </div>


        <div class="row portfolio-container">
        @foreach($data as $d)


            <div class="col-md-3 portfolio-item {{ $d['class'] }}">
              <div class="portfolio-wrap" style="border-radius: 5px;">
              <a href="/details/{{ $d['id'] }}">
                <img src="/storage/product_img/{{ $d['image'] }}" class="img-fluid imgpres" alt="">
              </a>
              <div class="portfolio-info">
                
                <div class="portfolio-links">
                    <p class="portfolio-details-lightbox" data-glightbox="type: external" title="Portfolio Details"><strong>{{$d['price']}} $</strong></p>
                  </div>

                </div>
              </div>

            </div>

          @endforeach

          

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
    
</body>

</html>