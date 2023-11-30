<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['add_expense'])) {

   $cycle = mysqli_real_escape_string($conn, $_POST['cycle']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);

   $insert_query = "INSERT INTO accounting (seller, cycle, type, source, details, value) VALUES ('$seller_id', '$cycle', 'expense', 'others', '$details', '$price')";

   if (mysqli_query($conn, $insert_query)) {
       $message[] = 'Expense added successfully!';
   } else {
       $message[] = 'Error adding expense: ' . mysqli_error($conn);
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
   <title>PINILI-PIG: Other expenses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="add-products">

      <form action="" method="POST" enctype="multipart/form-data">
         <h3>add other expenses</h3>

         <?php
         $query = mysqli_query($conn, "SELECT * FROM `cycle` WHERE status = 'ongoing' AND sellerID = '$seller_id'");
         ?>
         <!-- <label for="cycle"><strong>Cycle:</strong></label> -->
         <h1>Cycle</h1>
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

         <input type="text" class="box" required placeholder="Details" name="details">
         <input type="number" class="box" required placeholder="Price" name="price" step="0.01">

         <input type="submit" value="Add" name="add_expense" class="btn">
         <a href="seller_others.php" class="option-btn">Go Back</a>
      </form>

   </section>

   <script src="js/admin_script.js"></script>

</body>

</html>