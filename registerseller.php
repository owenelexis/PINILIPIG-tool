<?php

@include 'config.php';

if(isset($_POST['submit'])){

   $filter_firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
   $firstname = mysqli_real_escape_string($conn, $filter_firstname);
   $filter_lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
   $lastname = mysqli_real_escape_string($conn, $filter_lastname);
   $filter_farmname = filter_var($_POST['farmname'], FILTER_SANITIZE_STRING);
   $farmname = mysqli_real_escape_string($conn, $filter_farmname);
   $filter_address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
   $address = mysqli_real_escape_string($conn, $filter_address);
   $filter_contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
   $contact = mysqli_real_escape_string($conn, $filter_contact);
   $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $email = mysqli_real_escape_string($conn, $filter_email);
   $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
   $pass = mysqli_real_escape_string($conn, md5($filter_pass));
   $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
   $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));

   $select_users = mysqli_query($conn, "SELECT * FROM `seller` WHERE email = '$email'") or die('query failed');

   if(mysqli_num_rows($select_users) > 0){
      $message[] = 'User already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'Confirm password not matched!';
      }else{
         mysqli_query($conn, "INSERT INTO `seller`(`sellerID`, `firstname`, `lastname`, `farmname`, `address`, `contact_number`, `email`, `pass`, `date_added`) VALUES (NULL, '$firstname', '$lastname', '$farmname', '$address', '$contact', '$email', '$pass', current_timestamp())") or die('query failed');
         $message[] = 'registered successfully!';
         header('location:loginseller.php');
         exit();
      }
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
   <title>PINILI-PIG: Register Seller</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<section class="form-container">

   <form action="" method="post">
      <h3>register as seller</h3>
      <input type="text" name="firstname" class="box" placeholder="enter your first name" required>
      <input type="text" name="lastname" class="box" placeholder="enter your last name" required>
      <input type="text" name="farmname" class="box" placeholder="enter your farm name" required>
      <input type="text" name="address" class="box" placeholder="enter your farm address" required>
      <input type="text" name="contact" class="box" placeholder="enter your contact number" required>
      <input type="email" name="email" class="box" placeholder="enter your email" required>
      <input type="password" name="pass" class="box" placeholder="enter your password" required>
      <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
      <input type="submit" class="btn" name="submit" value="register now">
      <p>Already have a seller account? <a href="loginseller.php">Login now</a></p>
   </form>

</section>

</body>
</html>