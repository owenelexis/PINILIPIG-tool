<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if(!isset($seller_id)){
   header('location:index.php');
};

if(isset($_POST['update_vaccines'])){

   $update_p_id = $_POST['update_p_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $vendor = mysqli_real_escape_string($conn, $_POST['vendor']);

   mysqli_query($conn, "UPDATE `vaccine` SET name = '$name', vendor = '$vendor' WHERE vaccineID = '$update_p_id'") or die('query failed');

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folter = 'uploaded_img/'.$image;
   $old_image = $_POST['update_p_image'];
   
   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image file size is too large!';
      }else{
         mysqli_query($conn, "UPDATE `vaccine` SET vaccine_image = '$image' WHERE vaccineID = '$update_p_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folter);
         // unlink('uploaded_img/'.$old_image);
         $message[] = 'image updated successfully!';
      }
   }

   $message[] = 'medication updated successfully!';

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
   <title>PINILI-PIG: Update Medication</title>

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
      if(mysqli_num_rows($select_vaccines) > 0){
         while($fetch_vaccines = mysqli_fetch_assoc($select_vaccines)){
   ?>

   <form action="" method="post" enctype="multipart/form-data">

      <input type="hidden" value="<?php echo $fetch_vaccines['vaccineID']; ?>" name="update_p_id">
      <input type="hidden" value="<?php echo $fetch_vaccines['vaccine_image']; ?>" name="update_p_image">
      
      <img src="uploaded_img/<?php echo $fetch_vaccines['vaccine_image']; ?>" class="image"  alt="">

      <label for="vaccine"><strong>Vaccine Name:</strong></label>
      <input type="text" min="0" class="box" required placeholder="Enter name" name="name" value="<?php echo $fetch_vaccines['name']; ?>">

      <input type="text" class="box" required placeholder="Vendor" name="vendor" value="<?php echo $fetch_vaccines['vendor']; ?>">
      <label for="image"><strong>Image:</strong></label>
      <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
      <input type="submit" value="update vaccines" name="update_vaccines" class="btn">
      <a href="seller_vaccines.php" class="option-btn">go back</a>
   </form>

<?php
      }
   }else{
      echo '<p class="empty">no update product select</p>';
   }
?>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>