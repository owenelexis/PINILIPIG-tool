<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['add_product'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $vendor = mysqli_real_escape_string($conn, $_POST['vendor']);

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/' . $image;


   $select_product_name = mysqli_query($conn, "SELECT name FROM `vaccine` WHERE name = '$name' and seller='$seller_id'") or die('query failed');

   if (mysqli_num_rows($select_product_name) > 0) {
      $message[] = 'Medication already exist!';

   } else {
      $insert_product = mysqli_query($conn, "INSERT INTO `vaccine`(seller, name, vendor, vaccine_image) VALUES ('$seller_id', '$name', '$vendor', '$image')") or die('query failed');

      if ($insert_product) {
         if ($image_size > 3000000) {
            $message[] = 'image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folter);
            $message[] = 'product added successfully!';
         }
      }
   }

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Add Medication</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="add-products">

      <form action="" method="POST" enctype="multipart/form-data">
         <h3>add new medication</h3>

         <input type="text" class="box" required placeholder="Medication Name" name="name">
         <input type="text" class="box" required placeholder="Vendor" name="vendor">

         <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
         <input type="submit" value="add vaccine" name="add_product" class="btn">
         <a href="seller_vaccines.php" class="option-btn">Go Back</a>
      </form>

   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>