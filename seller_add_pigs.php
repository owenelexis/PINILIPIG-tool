<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['add_product'])) {

   $cycle = mysqli_real_escape_string($conn, $_POST['cycle']);
   $weight = mysqli_real_escape_string($conn, $_POST['weight']);
   $sex = mysqli_real_escape_string($conn, $_POST['sex']);
   $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
   $mother = mysqli_real_escape_string($conn, $_POST['mother']);
   $castration_date = mysqli_real_escape_string($conn, $_POST['castration_date']);
   $pig_status = mysqli_real_escape_string($conn, $_POST['status']);
   $vaccine_iron = mysqli_real_escape_string($conn, $_POST['vaccine_iron']);
   $vaccine_respisure = mysqli_real_escape_string($conn, $_POST['vaccine_respisure']);
   $vaccine_hogcholera = mysqli_real_escape_string($conn, $_POST['vaccine_hogcholera']);
   // $price = mysqli_real_escape_string($conn, $_POST['price']);
   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/' . $image;

   $insert_product = mysqli_query($conn, "INSERT INTO `pig`(owner, cycle, weight, sex, birthdate, mother, pig_status, castration_date, vaccine_iron, vaccine_respisure, vaccine_hogcholera, saleable, picture) 
   VALUES('$seller_id', '$cycle', '$weight', '$sex', '$birthdate', '$mother', '$pig_status', '$castration_date', '$vaccine_iron', '$vaccine_respisure', '$vaccine_hogcholera', 'No', '$image')") or die('query failed');

   if ($mother == 'None') {

   } else {
      $select_mama = mysqli_query($conn, "SELECT * FROM `pig` WHERE pigID = '$mother'") or die('query failed');
      $kid = mysqli_fetch_assoc($select_mama);
      $kid_number = $kid['kids'];
      $kid_number++;
      mysqli_query($conn, "UPDATE `pig` SET kids = '$kid_number' WHERE pigID = '$mother'") or die('query failed');
   }


   if ($insert_product) {
      if ($image_size > 3000000) {
         $message[] = 'image size is too large!';
      } else {
         move_uploaded_file($image_tmp_name, $image_folter);
         $message[] = 'product added successfully!';
      }
   }
}

if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete'];
   $select_delete_image = mysqli_query($conn, "SELECT picture FROM `pig` WHERE pigid = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
   // unlink('uploaded_img/' . $fetch_delete_image['picture']);
   mysqli_query($conn, "DELETE FROM `pig` WHERE pigid = '$delete_id'") or die('query failed');
   // mysqli_query($conn, "DELETE FROM `wishlist` WHERE pid = '$delete_id'") or die('query failed');
   // mysqli_query($conn, "DELETE FROM `cart` WHERE pid = '$delete_id'") or die('query failed');
   header('location:seller_products_all.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Add Pig</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="add-products">

      <form action="" method="POST" enctype="multipart/form-data">
         <h3>add new pig</h3>

         <?php
         $query = mysqli_query($conn, "SELECT * FROM `cycle` WHERE status = 'ongoing' AND sellerID = '$seller_id'");
         ?>
         <label for="cycle"><strong>Cycle:</strong></label>
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

         <label for="weight"><strong>Weight:</strong></label>
         <input type="number" min="0" class="box" required placeholder="Enter weight" name="weight">

         <label for="sex"><strong>Sex:</strong></label>
         <select class="box" required name="sex">
            <option value="" disabled selected>Select sex</option>
            <option value="male">male</option>
            <option value="female">female</option>
         </select>

         <label for="birthdate"><strong>Birthdate:</strong></label>
         <input type="date" class="box" required name="birthdate" id="birthdate">

         <label for="mother"><strong>Mother:</strong></label>
         <select class="box" required name="mother">
            <?php
            $motherResult = mysqli_query($conn, "SELECT pigID FROM pig WHERE owner = '$seller_id' and category='sow'") or die("query failed");
            if (mysqli_num_rows($motherResult) > 0) {

               while ($row = mysqli_fetch_assoc($motherResult)) {
                  echo '<option value="' . $row["pigID"] . '">' . $row["pigID"] . '</option>';
               }
            }
            ?>
            <option value="None">None</option>
         </select>

         <label for="status"><strong>Pig Status:</strong></label>
         <select class="box" required name="status">
            <option value="" disabled selected>Select pig status</option>
            <option value="Deceased">Deceased</option>
            <option value="Healthy">Healthy</option>
            <option value="Sick">Sick</option>
            <option value="Ordered">Ordered</option>
            <option value="Sold">Sold</option>
         </select>

         <label for="castration"><strong>Catration Date:</strong></label>
         <input type="date" class="box" name="castration_date" id="castration_date">

         <label for="vaccine"><strong>Vaccine:</strong></label>
         <select class="box" required name="vaccine_iron" id="vaccine_iron">
            <option value="" disabled selected>Vaccine: Iron</option>
            <option value="None">None</option>
            <option value="1st Shot">1st Shot</option>
            <option value="Completed">2nd Shot/Completed</option>
         </select>

         <select class="box" required name="vaccine_respisure" id="vaccine_respisure">
            <option value="" disabled selected>Vaccine: Respisure</option>
            <option value="None">None</option>
            <option value="1st Shot">1st Shot</option>
            <option value="Completed">2nd Shot/Completed</option>
         </select>

         <select class="box" required name="vaccine_hogcholera" id="vaccine_hogcholera">
            <option value="" disabled selected>Vaccine: Hogcholera</option>
            <option value="None">None</option>
            <option value="Completed">Completed</option>
         </select>

         <label for="picture"><strong>Insert Pig Picture:</strong></label>
         <input type="file" accept="image/jpg, image/jpeg, image/png" required class="box" name="image">
         <input type="submit" value="add pig" name="add_product" class="btn">
         <a href="seller_products_all.php" class="option-btn">Go Back</a>
      </form>

   </section>


   <script src="js/admin_script.js"></script>

</body>

</html>