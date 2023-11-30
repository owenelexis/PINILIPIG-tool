<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/icon.png" type="images/x-icon">
    <title>PINILI-PIG: About Us</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <h3>about us</h3>
        <p> <a href="home.php">home</a> / about </p>
    </section>

    <section class="about">

        <div class="flex">

            <div class="image">
                <img src="images/about-img-1.png" alt="">
            </div>

            <div class="content">
                <h3>why choose us?</h3>
                <p>In Pinili-pig, we stand as your premier destination for all things porcine, offering a unique and
                    seamless experience that sets us apart. With a commitment to quality and care, we provide a diverse
                    selection of healthy and happy live pigs, ensuring that every customer can embark on their own
                    journey of raising and enjoying fresh, farm-to-table pork.</p>
                <a href="shop.php" class="btn">shop now</a>
            </div>

        </div>

        <div class="flex">

            <div class="content">
                <h3>what we provide?</h3>
                <p>We provide a curated selection of healthy and high-quality live pigs, catering to enthusiasts,
                    farmers, and families looking to experience the joys of sustainable pig farming firsthand. Our
                    offerings include a variety of pig breeds, each raised with utmost care, ensuring that you receive a
                    thriving and delightful addition to your home or farm. With a commitment to transparency and
                    customer support, we offer not just pigs, but also a wealth of resources, guidance, and community
                    interaction to make your pig-raising journey a resounding success.</p>
                <a href="contact.php" class="btn">contact us</a>
            </div>

            <div class="image">
                <img src="images/about-img-3.jpg" alt="">

            </div>

        </div>

        <div class="flex">

            <div class="image">
                <img src="images/devs.png" alt="">
            </div>

            <div class="content">
                <h3>who we are?</h3>
                <p>Welcome to Pinili-pig, your premier online destination for all things pork and pig-related. As
                    passionate developers, we've cultivated a space where pork lovers and aspiring pig owners can
                    come together to explore, experience, and indulge in the world of these incredible animals. Our
                    commitment to quality, sustainability, and animal welfare drives us to provide a curated selection
                    of live pigs, premium pork products, and resources for those interested in pig ownership. </p>
            </div>

        </div>

    </section>


    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>