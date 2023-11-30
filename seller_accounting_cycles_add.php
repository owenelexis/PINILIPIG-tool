<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['add_cycle'])) {

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $insert_cycle = mysqli_query($conn, "INSERT INTO `cycle`(name, sellerID) VALUES ('$name', '$seller_id')") or die('query failed');
   $message[] = 'cycle added successfully!';

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Add Cycle</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="add-products">

      <form action="" method="POST" enctype="multipart/form-data">
         <h3>add new cycle</h3>

         <input type="text" class="box" required placeholder="Cycle name" name="name">

         <input type="submit" value="add cycle" name="add_cycle" class="btn">
         <a href="seller_accounting_cycles.php" class="option-btn">Go Back</a>
      </form>

   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>