<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:index.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `user` WHERE userID = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="images/icon.png" type="images/x-icon">
   <title>PINILI-PIG: Admin-Users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'admin_header.php'; ?>

<section class="users">

   <h1 class="title">users account</h1>

   <div class="box-container">
      <?php
         $select_users = mysqli_query($conn, "SELECT * FROM `user` where user_type = 'user'") or die('query failed');
         if(mysqli_num_rows($select_users) > 0){
            while($fetch_users = mysqli_fetch_assoc($select_users)){
      ?>
      <div class="box">
         <p>userID : <span><?php echo $fetch_users['userID']; ?></span></p>
         <p>firstname : <span><?php echo $fetch_users['firstname']; ?></span></p>
         <p>lastname : <span><?php echo $fetch_users['lastname']; ?></span></p>
         <p>email : <span><?php echo $fetch_users['email']; ?></span></p>
         <p>contact_number : <span><?php echo $fetch_users['contact_number']; ?></span></p>
         <p>user type : <span style="color:<?php if($fetch_users['user_type'] == 'admin'){ echo 'var(--orange)'; }; ?>"><?php echo $fetch_users['user_type']; ?></span></p>
         <p>date_added : <span><?php echo $fetch_users['date_added']; ?></span></p>
         <a href="admin_users.php?delete=<?php echo $fetch_users['userID']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
      </div>
      <?php
         }
      }
      ?>
   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>