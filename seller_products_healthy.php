<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT picture FROM `pig` WHERE pigID = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   // unlink('uploaded_img/'.$fetch_delete_image['picture']);
   mysqli_query($conn, "DELETE FROM `pig` WHERE pigID = '$delete_id'") or die('query failed');
   mysqli_query($conn, "DELETE FROM `cart` WHERE pigID = '$delete_id'") or die('query failed');
   header('location:seller_products_all.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Healthy</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="show-products">
      <div class="box-container">
         <a class="btn" href="seller_add_pigs.php">Add Pig</a>
      </div>
   </section>

   <section class="show-products">
      <div class="box-container">
         <div class="box">
            <a class="option-btn" href="seller_products_healthy.php">Healthy</a>
            <a class="option-btn" href="seller_products_sick.php">Sick</a>
            <a class="option-btn" href="seller_products_deceased.php">Deceased</a>
         </div>
         <div class="box">
            <a class="option-btn" href="seller_products_piglet.php">Piglet</a>
            <a class="option-btn" href="seller_products_weaner.php">Weaner</a>
            <a class="option-btn" href="seller_products_grower.php">Grower</a>
            <a class="option-btn" href="seller_products_sow.php">Sow</a>
            <a class="option-btn" href="seller_products_fattener.php">Fattener</a>
         </div>
         <div class="box">
            <a class="option-btn" href="seller_products_saleable.php">Saleable</a>
            <a class="option-btn" href="seller_products_ordered.php">Ordered</a>
            <a class="option-btn" href="seller_products_sold.php">Sold</a>
         </div>
      </div>
   </section>

   <section class="show-products">

      <div class="box-container">

         <?php
         $select_pigs = mysqli_query($conn, "SELECT * FROM `pig` LEFT JOIN `cycle` ON pig.cycle=cycle.id WHERE owner='$seller_id' AND pig_status = 'healthy'") or die('query failed');
         if (mysqli_num_rows($select_pigs) > 0) {
            while ($fetch_pigs = mysqli_fetch_assoc($select_pigs)) {
               ?>
               <div class="box">
                  <div class="price">₱
                     <?php echo $fetch_pigs['price']; ?>
                  </div>
                  <img class="image" src="uploaded_img/<?php echo $fetch_pigs['picture']; ?>" alt="">
                  <div class="name">ID:
                     <?php $pig = $fetch_pigs['pigID'];
                     echo $pig; ?>
                  </div>
                  <div class="details">Category:
                     <?php echo $fetch_pigs['category']; ?>
                  </div>
                  <div class="details">Weight:
                     <?php echo $fetch_pigs['weight']; ?>
                  </div>
                  <div class="details">Sex:
                     <?php echo $fetch_pigs['sex']; ?>
                  </div>
                  <div class="details">Birthdate:
                     <?php echo $fetch_pigs['birthdate']; ?>
                  </div>
                  <div class="details">Age:
                     <?php echo $fetch_pigs['age']; ?> days
                  </div>
                  <div class="details">Cycle:
                     <?php echo $fetch_pigs['name']; ?>
                  </div>
                  <div class="details">Mother: Pig #
                     <?php echo $fetch_pigs['mother']; ?>
                  </div>
                  <div class="details">Number of kids:
                     <?php echo $fetch_pigs['kids']; ?>
                  </div>
                  <div class="details">Status:
                     <?php echo $fetch_pigs['pig_status']; ?>
                  </div>
                  <div class="details">Castration date:
                     <?php echo $fetch_pigs['castration_date']; ?>
                  </div>
                  <div class="details">Vaccine-iron:
                     <?php echo $fetch_pigs['vaccine_iron']; ?>
                  </div>
                  <div class="details">Vaccine-respisure:
                     <?php echo $fetch_pigs['vaccine_respisure']; ?>
                  </div>
                  <div class="details">Vaccine-hogcholera:
                     <?php echo $fetch_pigs['vaccine_hogcholera']; ?>
                  </div>
                  <div class="details">Saleable:
                     <?php echo $fetch_pigs['saleable']; ?>
                  </div>
                  <div class="details">Last updated:
                     <?php echo $fetch_pigs['last_update']; ?>
                  </div>
                  <a href="seller_update_product.php?update=<?php echo $fetch_pigs['pigID']; ?>" class="option-btn">update</a>
                  <a href="seller_products_all.php?delete=<?php echo $fetch_pigs['pigID']; ?>" class="delete-btn"
                     onclick="return confirm('delete this product?');">delete</a>
               </div>
               <?php
            }
         } else {
            echo '<p class="empty">no pigs added yet!</p>';
         }
         ?>
      </div>


   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>