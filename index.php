<?php

@include 'config.php';
@include 'decision_support.php';

?>

<!DOCTYPE html>
<html lang="en">
<!-- CODE WITH MANISH -->

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="images/icon.png" type="images/x-icon">
  <title>PINILI-PIG</title>
  <link rel="stylesheet" href="css/landing.css" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>

<body>
  <!-- ==== HEADER ==== -->
  <header class="container header">
    <!-- ==== NAVBAR ==== -->
    <nav class="nav">
      <div class="logo">
        <h2>PINILI-PIG</h2>
      </div>

      <div class="nav_menu" id="nav_menu">
        <button class="close_btn" id="close_btn">
          <i class="ri-close-fill"></i>
        </button>


      </div>

      <!-- <button class="toggle_btn" id="toggle_btn">
        <i class="ri-menu-line"></i>
      </button> -->
    </nav>
  </header>

  <section class="wrapper">
    <div class="container">
      <div class="grid-cols-2">
        <div class="grid-item-1">
          <h1 class="main-heading">
            OINKTASTIC EXPERIENCE HERE AT <span>PINILI-PIG!</span>
            <br />

          </h1>
          <p class="info-text">
            Indulge in the superior quality and taste of pork,
            from our farm to your family. Shop with us today!
          </p>

          <div class="btn_wrapper">
            <button class="btn view_more_btn">
              <a href="loginseller.php"> Login as Seller </a>
            </button>

            <button class="btn view_more_btn">
              <a href="loginbuyer.php"> Login as Buyer </a>
            </button>

          </div>
        </div>
        <div class="grid-item-2">
          <div class="team_img_wrapper">
            <img src="images/clip.png" alt="team-img"/>
          </div>
        </div>
      </div>
    </div>
  </section>


  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.8.0/gsap.min.js"></script>

</body>

</html>