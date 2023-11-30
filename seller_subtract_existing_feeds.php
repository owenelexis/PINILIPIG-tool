<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['update_feeds'])) {

   $update_p_id = $_POST['update_p_id'];
   $name = $_POST['name'];
   $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
   $used = $_POST['used'];


   $new_quantity = $quantity - $used;

   mysqli_query($conn, "UPDATE `feed` SET quantity = '$new_quantity' WHERE feedID = '$update_p_id'") or die('query failed');

   $message[] = 'feed updated successfully!';
   header('location:seller_feeds.php');

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Subtract</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="update-product">

      <?php

      $update_id = $_GET['update'];
      $select_feeds = mysqli_query($conn, "SELECT * FROM `feed` WHERE seller='$seller_id' and feedID = '$update_id'") or die('query failed');
      if (mysqli_num_rows($select_feeds) > 0) {
         while ($fetch_feeds = mysqli_fetch_assoc($select_feeds)) {
            ?>

            <form action="" method="post" enctype="multipart/form-data">

               <input type="hidden" value="<?php echo $fetch_feeds['feedID']; ?>" name="update_p_id">
               <input type="hidden" value="<?php echo $fetch_feeds['name']; ?>" name="name">
               <input type="hidden" value="<?php echo $fetch_feeds['feed_image']; ?>" name="update_p_image">
               <input type="hidden" value="<?php echo $fetch_feeds['quantity']; ?>" name="quantity">

               <img src="uploaded_img/<?php echo $fetch_feeds['feed_image']; ?>" class="image" alt="">

               <h2>Feed name</h2>
               <input type="text" min="0" class="box" disabled required placeholder="Enter name" name="name"
                  value="<?php echo $fetch_feeds['name']; ?>">

               <h2>Used Quantity</h2>
               <input type="number" min="0" max="<?php echo $fetch_feeds['quantity']; ?>" class="box" required placeholder="Enter quantity" name="used" value="0">

               <!-- <input type="number" class="box" required pattern="^\d+(\.\d{1,2})?$" placeholder="Price" name="price"> -->
               <input type="submit" value="subtract" name="update_feeds" class="btn">
               <a href="seller_feeds.php" class="option-btn">go back</a>
            </form>

            <?php
         }
      } else {
         echo '<p class="empty">no update product select</p>';
      }
      ?>

   </section>


   <script src="js/admin_script.js"></script>

</body>

</html>