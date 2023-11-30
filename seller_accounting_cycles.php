<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:index.php');
};


if(isset($_GET['end'])){
   $end_id = $_GET['end'];
      // Assuming your table name is 'cycle' and the column for the unique identifier is 'id'
      $update_query = "UPDATE `cycle` SET date_ended = NOW(), status = 'ended' WHERE id = '$end_id'";
   
      mysqli_query($conn, $update_query) or die('query failed');
      header('location:seller_accounting_cycles.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Assuming your table name is 'cycle' and the column for the unique identifier is 'id'
   $delete_query = "DELETE FROM `cycle` WHERE id = '$delete_id'";
   $update_query = "UPDATE `pig` SET cycle = NULL WHERE pigID = '$delete_id'";

   mysqli_query($conn, $delete_query) or die('Delete query failed: ' . mysqli_error($conn));
   mysqli_query($conn, $update_query) or die('Update query failed: ' . mysqli_error($conn));

   header('location:seller_accounting_cycles.php');
}

@include 'decision_support.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Cycle</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'seller_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Cycles</h1>

   <div class="box-container">

      <?php
      
      $select_cycles = mysqli_query($conn, "SELECT * FROM cycle WHERE sellerID = '$seller_id'") or die('query failed');
      if(mysqli_num_rows($select_cycles) > 0){
         while($fetch_cycles = mysqli_fetch_assoc($select_cycles)){
      ?>
      <div class="box">
         <p> Name : <span><?php echo $fetch_cycles['name']; ?></span> </p>
         <p> Start Date : <span><?php echo $fetch_cycles['date_started']; ?></span> </p>
         <p> End Date : <span><?php echo $fetch_cycles['date_ended']; ?></span> </p>
         <p> Status : <span><?php echo $fetch_cycles['status']; ?></span> </p>


         <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $fetch_cycles['id']; ?>">
            <a href="seller_accounting_cycles_view.php?view=<?php echo $fetch_cycles['id']; ?>" class="option-btn">view</a>
            <a href="seller_accounting_cycles.php?end=<?php echo $fetch_cycles['id']; ?>" class="delete-btn" onclick="return confirm('end this cycle?');">End</a>
            <a href="seller_accounting_cycles.php?delete=<?php echo $fetch_cycles['id']; ?>" class="delete-btn" onclick="return confirm('delete this cycle?');">Delete</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>

<section class="show-products">
   <div class="box-container">
      <a class="btn" href="seller_accounting_cycles_add.php">Add another cycle</a>
   </div>
</section>

<script src="js/admin_script.js"></script>

</body>
</html>