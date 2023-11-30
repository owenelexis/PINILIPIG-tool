<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:index.php');
};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT feed_image FROM `feed` WHERE feedID = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   // unlink('uploaded_img/'.$fetch_delete_image['picture']);
   mysqli_query($conn, "DELETE FROM `feed` WHERE feedID = '$delete_id'") or die('query failed');
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
   <title>PINILI-PIG: Feeds</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'seller_header.php'; ?>

<section class="show-products">
   <div class="box-container">
      <a class="btn" href="seller_products_addfeed.php">Add Feeds</a>
   </div>
</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_feeds = mysqli_query($conn, "SELECT * FROM `feed` WHERE seller='$seller_id'") or die('query failed');
         if(mysqli_num_rows($select_feeds) > 0){
            while($fetch_feeds = mysqli_fetch_assoc($select_feeds)){
      ?>
      <div class="box">
         
         <img class="image" src="uploaded_img/<?php echo $fetch_feeds['feed_image']; ?>" alt="">
         <div class="name">Name: <?php $pig=$fetch_feeds['name']; echo $pig; ?></div>
         <div class="details" style="<?php echo ($fetch_feeds['quantity'] < 4) ? 'color: red;' : ''; ?>">Quantity: <?php echo $fetch_feeds['quantity']; ?></div>
         <div class="details">Vendor: <?php echo $fetch_feeds['vendor']; ?></div>
         <div class="details">Last update: <?php echo $fetch_feeds['date_updated']; ?></div>

         <a href="seller_add_existing_feeds.php?update=<?php echo $fetch_feeds['feedID']; ?>" class="option-btn">Add </a>
         <a href="seller_subtract_existing_feeds.php?update=<?php echo $fetch_feeds['feedID']; ?>" class="option-btn">Subtract </a>
         <a href="seller_update_feeds.php?update=<?php echo $fetch_feeds['feedID']; ?>" class="option-btn">update</a>
         <a href="seller_feeds.php?delete=<?php echo $fetch_feeds['feedID']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no pigs added yet!</p>';
      }
      ?>
   </div>
   

</section>

<script src="js/admin_script.js"></script>

</body>
</html>