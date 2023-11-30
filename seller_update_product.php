<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['update_product'])) {

   $update_p_id = $_POST['update_p_id'];
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $cycle = mysqli_real_escape_string($conn, $_POST['cycle']);
   $weight = mysqli_real_escape_string($conn, $_POST['weight']);
   $sex = mysqli_real_escape_string($conn, $_POST['sex']);
   $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
   $castration_date = mysqli_real_escape_string($conn, $_POST['castration_date']);
   $pig_status = mysqli_real_escape_string($conn, $_POST['status']);
   $vaccine_iron = mysqli_real_escape_string($conn, $_POST['vaccine_iron']);
   $vaccine_respisure = mysqli_real_escape_string($conn, $_POST['vaccine_respisure']);
   $vaccine_hogcholera = mysqli_real_escape_string($conn, $_POST['vaccine_hogcholera']);
   // $price = mysqli_real_escape_string($conn, $_POST['price']);

   mysqli_query($conn, "UPDATE `pig` SET category = '$category', cycle = '$cycle', weight = '$weight', birthdate = '$birthdate', castration_date = '$castration_date', pig_status = '$pig_status', vaccine_iron = '$vaccine_iron', vaccine_respisure = '$vaccine_respisure', vaccine_hogcholera = '$vaccine_hogcholera' WHERE pigID = '$update_p_id'") or die('query failed');
   if ($pig_status == 'Sick' or 'Deceased') {
      $cancel = mysqli_query($conn, "SELECT * FROM `transaction` WHERE pigs = '$update_p_id'") or die('query failed');
      if (mysqli_num_rows($cancel) > 0) {
         mysqli_query($conn, "UPDATE `transaction` SET status = 'Cancelled' WHERE pigs = '$update_p_id'") or die('query failed');
      }
   }


   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/' . $image;
   $old_image = $_POST['update_p_image'];

   if (!empty($image)) {
      if ($image_size > 2000000) {
         $message[] = 'image file size is too large!';
      } else {
         mysqli_query($conn, "UPDATE `pig` SET picture = '$image' WHERE pigid = '$update_p_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folter);
         // unlink('uploaded_img/'.$old_image);
         $message[] = 'image updated successfully!';
      }
   }

   $message[] = 'product updated successfully!';

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
   <title>PINILI-PIG: Update Pig</title>

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
      $select_pigs = mysqli_query($conn, "SELECT * FROM `pig` WHERE owner='$seller_id' and pigid = '$update_id'") or die('query failed');
      if (mysqli_num_rows($select_pigs) > 0) {
         while ($fetch_pigs = mysqli_fetch_assoc($select_pigs)) {
            ?>

            <form action="" method="post" enctype="multipart/form-data">

               <input type="hidden" value="<?php echo $fetch_pigs['pigID']; ?>" name="update_p_id">
               <input type="hidden" value="<?php echo $fetch_pigs['picture']; ?>" name="update_p_image">

               <img src="uploaded_img/<?php echo $fetch_pigs['picture']; ?>" class="image" alt="">

               <br>
               <label for="category"><strong>Category:</strong></label>
               <select class="box" required name="category">
                  <option value="" disabled>category</option>
                  <option value="<?php echo $fetch_pigs['category']; ?>">
                     <?php echo $fetch_pigs['category']; ?>
                  </option>
                  <option value="sow" <?php if ($fetch_pigs['category'] == 'Sow')
                     echo "selected"; ?>>Sow</option>
               </select>

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
                     <option value="<?php echo $cycleID; ?>" <?php if ($fetch_pigs['cycle'] == $cycleID)
                           echo "selected"; ?>>
                        <?php echo $cycleName . " - " . $cyclestarted; ?>
                     </option>
                     <?php
                  }
                  ?>
               </select>

               <label for="weight"><strong>Weight:</strong></label>
               <input type="number" min="0" step="0.01" class="box" required placeholder="Enter weight" name="weight"
                  value="<?php echo $fetch_pigs['weight']; ?>">


               <label for="sex"><strong>Sex:</strong></label>
               <select class="box" required name="sex">
                  <option value="" disabled>Select sex</option>
                  <option value="male" <?php if ($fetch_pigs['sex'] == 'male')
                     echo "selected"; ?>>male</option>
                  <option value="female" <?php if ($fetch_pigs['sex'] == 'female')
                     echo "selected"; ?>>female</option>
               </select>

               <label for="birthdate"><strong>Birthdate:</strong></label>
               <input type="date" class="box" required name="birthdate" id="birthdate" value="<?php echo strftime(
                  '%Y-%m-%d',
                  strtotime($fetch_pigs['birthdate'])
               ); ?>">

               <label for="pigstatus"><strong>Pig Status:</strong></label>
               <select class="box" required name="status">
                  <option value="" disabled>Select pig status</option>
                  <option value="Deceased" <?php if ($fetch_pigs['pig_status'] == 'Deceased')
                     echo "selected"; ?>>Deceased
                  </option>
                  <option value="Healthy" <?php if ($fetch_pigs['pig_status'] == 'Healthy')
                     echo "selected"; ?>>Healthy</option>
                  <option value="Sick" <?php if ($fetch_pigs['pig_status'] == 'Sick')
                     echo "selected"; ?>>Sick</option>
                  <option value="Ordered" <?php if ($fetch_pigs['pig_status'] == 'Ordered')
                     echo "selected"; ?>>Ordered</option>
                  <option value="Sold" <?php if ($fetch_pigs['pig_status'] == 'Sold')
                     echo "selected"; ?>>Sold</option>
               </select>

               <label for="castration"><strong>Castration Date:</strong></label>
               <input type="date" class="box" name="castration_date" id="castration_date" value="<?php echo strftime(
                  '%Y-%m-%d',
                  strtotime($fetch_pigs['castration_date'])
               ); ?>">

               <label for="vaccine"><strong>Iron Vaccine:</strong></label>
               <select class="box" required name="vaccine_iron" id="vaccine_iron">
                  <option value="" disabled>Vaccine: Iron</option>
                  <option value="None" <?php if ($fetch_pigs['vaccine_iron'] == 'None')
                     echo "selected"; ?>>None</option>
                  <option value="1st Shot" <?php if ($fetch_pigs['vaccine_iron'] == 'First shot')
                     echo "selected"; ?>>1st Shot
                  </option>
                  <option value="Completed" <?php if ($fetch_pigs['vaccine_iron'] == 'Completed')
                     echo "selected"; ?>>2nd
                     Shot/Completed</option>
               </select>

               <label for="vaccine"><strong>Respisure Vaccine:</strong></label>
               <select class="box" required name="vaccine_respisure" id="vaccine_respisure">
                  <option value="" disabled>Vaccine: Respisure</option>
                  <option value="None" <?php if ($fetch_pigs['vaccine_respisure'] == 'None')
                     echo "selected"; ?>>None</option>
                  <option value="1st Shot" <?php if ($fetch_pigs['vaccine_respisure'] == 'First Shot')
                     echo "selected"; ?>>1st
                     Shot</option>
                  <option value="Completed" <?php if ($fetch_pigs['vaccine_respisure'] == 'Completed')
                     echo "selected"; ?>>2nd
                     Shot/Completed</option>
               </select>

               <label for="vaccine"><strong>Hogcholera Vaccine:</strong></label>
               <select class="box" required name="vaccine_hogcholera" id="vaccine_hogcholera">
                  <option value="" disabled>Vaccine: Hogcholera</option>
                  <option value="None" <?php if ($fetch_pigs['vaccine_respisure'] == 'None')
                     echo "selected"; ?>>None</option>
                  <option value="Completed" <?php if ($fetch_pigs['vaccine_respisure'] == 'Completed')
                     echo "selected"; ?>>
                     Completed</option>
               </select>

               <!-- <input type="number" class="box" required pattern="^\d+(\.\d{1,2})?$" placeholder="Price" name="price"> -->
               <label for="image"><strong>Insert Pig Picture:</strong></label>
               <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
               <input type="submit" value="update product" name="update_product" class="btn">
               <a href="seller_products_all.php" class="option-btn">go back</a>
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