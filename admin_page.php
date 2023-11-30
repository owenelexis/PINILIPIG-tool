<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:index.php');
}
;

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Admin</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="title">dashboard</h1>

      <div class="box-container">

         <div class="box">
            <?php
            $total_seller = 0;
            $select_seller = mysqli_query($conn, "SELECT * FROM `seller`") or die('query failed');
            while ($fetch_seller = mysqli_fetch_assoc($select_seller)) {
               $total_seller += 1;
            }
            ;
            ?>
            <h3>
               <?php echo $total_seller; ?>
            </h3>
            <p>number of sellers</p>
         </div>

         <div class="box">
            <?php
            $total_user = 0;
            $select_user = mysqli_query($conn, "SELECT * FROM `user` where user_type = 'user'") or die('query failed');
            while ($fetch_user = mysqli_fetch_assoc($select_user)) {
               $total_user += 1;
            }
            ;
            ?>
            <h3>
               <?php echo $total_user; ?>
            </h3>
            <p>number of users</p>
         </div>

         <div class="box">
            <?php
            $total_delivered = 0;
            $select_delivered = mysqli_query($conn, "SELECT * FROM `transaction` WHERE status = 'Delivered'") or die('query failed');
            while ($fetch_delivered = mysqli_fetch_assoc($select_delivered)) {
               $total_delivered += 1;
            }
            ;
            ?>
            <h3>
               <?php echo $total_delivered; ?>
            </h3>
            <p>fulfilled orders</p>
         </div>

         <div class="box">
            <?php
            $select_messages = mysqli_query($conn, "SELECT * FROM `message` ") or die('query failed');
            $number_of_messages = mysqli_num_rows($select_messages);
            ?>
            <h3>
               <?php echo $number_of_messages; ?>
            </h3>
            <p>messages</p>
         </div>

      </div>



   </section>

   <section class="add-price">
      <form>
         <h3>Latest Sellers</h3>

         <?php
         $first_iron_vaccine = mysqli_query($conn, "SELECT * FROM seller ORDER BY sellerID DESC LIMIT 5") or die('query failed');
         while ($fetch_first_iron_vaccine = mysqli_fetch_assoc($first_iron_vaccine)) {
            $sellerID = $fetch_first_iron_vaccine['sellerID'];
            $firstname = $fetch_first_iron_vaccine['firstname'];
            $lastname = $fetch_first_iron_vaccine['lastname'];


            ?><input type="text" class="box"
               value=" Seller ID: <?php echo "{$sellerID} || Name: {$firstname} {$lastname}"; ?>" disabled>
            <?php
         }
         ?>

      </form>
      <form>
         <h3>Latest Buyers</h3>

         <?php
         $first_iron_vaccine = mysqli_query($conn, "SELECT * FROM user ORDER BY userID DESC LIMIT 5") or die('query failed');
         while ($fetch_first_iron_vaccine = mysqli_fetch_assoc($first_iron_vaccine)) {
            $userID = $fetch_first_iron_vaccine['userID'];
            $firstname = $fetch_first_iron_vaccine['firstname'];
            $lastname = $fetch_first_iron_vaccine['lastname'];


            ?><input type="text" class="box"
               value=" Buyer ID: <?php echo "{$userID} || Name: {$firstname} {$lastname}"; ?>" disabled>
            <?php
         }
         ?>

      </form>

   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>