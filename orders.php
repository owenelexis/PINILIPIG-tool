<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
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
   <title>PINILI-PIG: Orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>your orders</h3>
    <p> <a href="home.php">home</a> / order </p>
</section>

<section class="placed-orders">

    <h1 class="title">placed orders</h1>

    <div class="box-container">

    <?php
        $select_orders = mysqli_query($conn, "SELECT * FROM `transaction` WHERE buyer = '$user_id' ORDER BY transactionID DESC;") or die('query failed');
        if(mysqli_num_rows($select_orders) > 0){
            while($fetch_orders = mysqli_fetch_assoc($select_orders)){
    ?>
    <div class="box">
        <p> placed on : <span><?php echo $fetch_orders['date_created']; ?></span> </p>
        <p> updated on : <span><?php echo $fetch_orders['date_updated']; ?></span> </p>
        <p> order number : <span><?php echo $fetch_orders['transactionID']; ?></span> </p>
        <p> address : <span><?php echo $fetch_orders['delivery_address']; ?></span> </p>
        <p> delivery method : <span><?php echo $fetch_orders['delivery_method']; ?></span> </p>
        <p> payment method : <span><?php echo $fetch_orders['payment_method']; ?></span> </p>
        <p> pig number : <span><?php echo $fetch_orders['pigs']; ?></span> </p>
        <p> price : <span>₱<?php echo $fetch_orders['total_amount']; ?></span> </p>
        <p> order status : <span style="color:<?php if($fetch_orders['status'] == 'Delivered'){echo 'green'; }else{echo 'tomato';} ?>"><?php echo $fetch_orders['status']; ?></span> </p>
        <?php 
            if ($fetch_orders['status'] == 'Delivered') {
                echo "<p>date delivered: <span>{$fetch_orders['date_updated']}</span></p>";
            } elseif ($fetch_orders['status'] == 'Cancelled') {
                echo "<p>date cancelled: <span>{$fetch_orders['date_updated']}</span></p>";
            } else {
                echo "<p>waiting for the seller to update the order...</span></p>";
            }
        ?>
    </div>
    <?php
        }
    }else{
        echo '<p class="empty">no orders placed yet!</p>';
    }
    ?>
    </div>

</section>







<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>