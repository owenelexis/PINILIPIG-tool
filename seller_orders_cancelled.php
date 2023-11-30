<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:index.php');
};

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_status = $_POST['update_status'];
   mysqli_query($conn, "UPDATE `transaction` SET status = '$update_status' WHERE transactionID = '$order_id'") or die('query failed');
   $message[] = 'Order status has been updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `transaction` WHERE transactionID = '$delete_id'") or die('query failed');
   header('location:seller_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Cancelled</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'seller_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">Cancelled orders</h1>

   <div class="box-container">

      <?php
      
      $select_transaction = mysqli_query($conn, "SELECT * FROM transaction T JOIN user U ON T.buyer=U.userID WHERE T.seller = '$seller_id' and T.status = 'Cancelled';") or die('query failed');
      if(mysqli_num_rows($select_transaction) > 0){
         while($fetch_transaction = mysqli_fetch_assoc($select_transaction)){
      ?>
      <div class="box">
         <p> Order Number : <span><?php echo $fetch_transaction['transactionID']; ?></span> </p>
         <p> Name : <span><?php echo $fetch_transaction['firstname']; ?> <?php echo $fetch_transaction['lastname']; ?></span> </p>
         <p> Contact Number : <span><?php echo $fetch_transaction['contact_number']; ?></span> </p>
         <p> Email : <span><?php echo $fetch_transaction['email']; ?></span> </p>
         <p> Delivery Address : <span><?php echo $fetch_transaction['delivery_address']; ?></span> </p>
         <p> Payment Method : <span><?php echo $fetch_transaction['payment_method']; ?></span> </p>
         <p> Pigs : <span><?php echo $fetch_transaction['pigs']; ?></span> </p>
         <p> Total price : <span>₱<?php echo $fetch_transaction['total_amount']; ?></span> </p>
         <p> Additional : <span><?php echo $fetch_transaction['additional']; ?></span> </p>
         <p> Date Cancelled : <span><?php echo $fetch_transaction['date_updated']; ?></span> </p>


         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_transaction['transactionID']; ?>">
            <select name="update_status" disabled>
               <option value="Cancelled" <?php if ($fetch_transaction['status'] == 'Cancelled') echo "selected"; ?>>Cancelled</option>
               <option value="Pending" <?php if ($fetch_transaction['status'] == 'Pending') echo "selected"; ?>>Pending</option>
               <option value="Preparing" <?php if ($fetch_transaction['status'] == 'Preparing') echo "selected"; ?>>Preparing</option>
               <option value="Ready" <?php if ($fetch_transaction['status'] == 'Ready') echo "selected"; ?>>Ready</option>
               <option value="Delivered" <?php if ($fetch_transaction['status'] == 'Delivered') echo "selected"; ?>>Delivered</option>
            </select>
            <a href="seller_orders.php?delete=<?php echo $fetch_transaction['transactionID']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">No orders cancelled yet!</p>';
      }
      ?>
   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>