<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['update_vaccines'])) {

   $update_p_id = $_POST['update_p_id'];
   $name = $_POST['name'];
   $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
   $add = $_POST['add'];
   $price = $_POST['price'];
   $cycle = $_POST['cycle'];

   $new_quantity = $quantity + $add;
   // $total_price = $add * $price;
   // $price = mysqli_real_escape_string($conn, $_POST['price']);

   mysqli_query($conn, "UPDATE `vaccine` SET quantity = '$new_quantity' WHERE vaccineID = '$update_p_id'") or die('query failed');
   mysqli_query($conn, "INSERT INTO `accounting` (seller, type, cycle, source, details, value) VALUE ('$seller_id', 'expense', '$cycle','vaccine', '$name', '$price')");

   $message[] = 'medication updated successfully!';
   header('location:seller_vaccines.php');

}



?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Add</title>

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
      $select_vaccines = mysqli_query($conn, "SELECT * FROM `vaccine` WHERE seller='$seller_id' and vaccineID = '$update_id'") or die('query failed');
      if (mysqli_num_rows($select_vaccines) > 0) {
         while ($fetch_vaccines = mysqli_fetch_assoc($select_vaccines)) {
            ?>

            <form action="" method="post" enctype="multipart/form-data">

               <input type="hidden" value="<?php echo $fetch_vaccines['vaccineID']; ?>" name="update_p_id">
               <input type="hidden" value="<?php echo $fetch_vaccines['name']; ?>" name="name">
               <input type="hidden" value="<?php echo $fetch_vaccines['vaccine_image']; ?>" name="update_p_image">
               <input type="hidden" value="<?php echo $fetch_vaccines['quantity']; ?>" name="quantity">

               <img src="uploaded_img/<?php echo $fetch_vaccines['vaccine_image']; ?>" class="image" alt="">

               <h2>Vaccine name</h2>
               <input type="text" min="0" class="box" disabled required placeholder="Enter name" name="name"
                  value="<?php echo $fetch_vaccines['name']; ?>">

               <h2>Add quantity</h2>
               <input type="number" min="0" class="box" required placeholder="Enter quantity" name="add" value="0">

               <h2>Cycle</h2>
               <?php
               $query = mysqli_query($conn, "SELECT * FROM `cycle` WHERE status = 'ongoing' AND sellerID = '$seller_id'");
               ?>
               <select class="box" name="cycle">
                  <option value="">None</option>
                  <?php
                  while ($row = mysqli_fetch_assoc($query)) {
                     $cycleID = $row['id'];
                     $cycleName = $row['name'];
                     $cyclestarted = $row['date_started']; // Added a semicolon here
            
                     ?>
                     <option value="<?php echo $cycleID; ?>">
                        <?php echo $cycleName . " - " . $cyclestarted; ?>
                     </option>
                     <?php
                  }
                  ?>
               </select>
               
               <h2>Price</h2>
               <input type="number" min="0" class="box" required step=".01" placeholder="Price" name="price">
               <!-- <input type="number" class="box" required pattern="^\d+(\.\d{1,2})?$" placeholder="Price" name="price"> -->
               <input type="submit" value="add" name="update_vaccines" class="btn">
               <a href="seller_vaccines.php" class="option-btn">go back</a>
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