<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:index.php');
};

if (isset($_POST['send'])) {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);

    if (!isset($_SESSION['antispam'])) {
        $_SESSION['antispam'] = 0;
    }

    if ($_SESSION['antispam'] >= 5) {
        $message[] = 'Too many messages sent!';
    } else {
        mysqli_query($conn, "INSERT INTO `message` (userID, subject, message) VALUES ('$user_id', '$subject', '$msg')") or die('Query failed');
        $message[] = 'Message sent successfully!';
        $_SESSION['antispam'] += 1;
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
   <title>PINILI-PIG: Contact Us</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>contact us</h3>
    <p> <a href="home.php">home</a> / contact </p>
</section>

<section class="contact">

    <form action="" method="POST">
        <h3>send us message!</h3>
        <input type="text" name="subject" placeholder="subject" class="box" required> 
        <textarea name="message" class="box" placeholder="enter your message" required cols="30" rows="10"></textarea>
        <input type="submit" value="send message" name="send" class="btn">
    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>