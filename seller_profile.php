<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
}

if (isset($_POST['add_to_cart'])) {

    $pigID = $_POST['pigID'];
    $sellerID = $_POST['sellerID'];
    $price = $_POST['price'];
    $pig_image = $_POST['picture'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE userID = '$user_id' and pigID='$pigID'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(userid, pigID, sellerID, price, picture) VALUES('$user_id', '$pigID', '$sellerID', '$price', '$pig_image')") or die('query failed');
        $message[] = 'product added to cart';
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/profile.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="checkout">

        <h1 class="title">Seller Profile</h1>

        <?php
        if (isset($_GET['sid'])) {
            $sid = $_GET['sid'];
            $select_seller = mysqli_query($conn, "SELECT * FROM `seller` WHERE sellerID = '$sid'") or die('query failed');
            $seller_orders = mysqli_query($conn, "SELECT COUNT(transactionID) as count FROM `transaction` WHERE seller = '$sid' and status = 'Delivered'") or die('query failed');
            $fetch_orders = mysqli_fetch_assoc($seller_orders);
            if (mysqli_num_rows($select_seller) > 0) {
                while ($fetch_seller = mysqli_fetch_assoc($select_seller)) {
                    ?>

                    <form action="" method="POST" id="orderForm">

                        <h3>
                            <?php echo $fetch_seller['farmname']; ?>
                        </h3>

                        <div class="flex">
                            <div class="inputBox">
                                <span>Owner: :</span>
                                <input type="text" name="first_name" value="<?php echo $fetch_seller['firstname']; echo ' ' ; echo $fetch_seller['lastname']; ?>" readonly>
                            </div>

                            <div class="inputBox">
                                <span>Address :</span>
                                <input type="text" name="address" value="<?php echo $fetch_seller['address']; ?>" readonly>
                            </div>

                            <div class="inputBox">
                                <span>Contact Number :</span>
                                <input type="text" name="contact_number" value="<?php echo $fetch_seller['contact_number']; ?>"
                                    readonly>
                            </div>

                            <div class="inputBox">
                                <span>E-mail Address :</span>
                                <input type="text" name="email" value="<?php echo $fetch_seller['email']; ?>" readonly>
                            </div>

                            <div class="inputBox">
                                <span>Completed Orders :</span>
                                <input type="text" name="email" value="<?php echo $fetch_orders['count']; ?>" readonly>
                            </div>
                        </div>
                    </form>

                    <?php
                } // end while
            } // end if
        } // end if
        ?>
    </section>


    <section class="products">

        <h1 class="title">Saleable Pigs</h1>
        <div class="box-container">

            <?php
            $select_pigs = mysqli_query($conn, "SELECT * FROM `pig` JOIN `seller` ON pig.owner=seller.sellerID  WHERE sellerID = '$sid' and saleable = 'Yes' and pig_status = 'Healthy'") or die('query failed');
            if (mysqli_num_rows($select_pigs) > 0) {
                while ($fetch_pigs = mysqli_fetch_assoc($select_pigs)) {
                    ?>
                    <form action="" method="POST" class="box">
                        <a href="view_page.php?pid=<?php echo $fetch_pigs['pigID']; ?>" class="fas fa-eye"></a>
                        <div class="price">₱
                            <?php echo $fetch_pigs['price']; ?>
                        </div>
                        <img src="uploaded_img/<?php echo $fetch_pigs['picture']; ?>" alt="" class="image">
                        <div class="name">
                            <?php echo $fetch_pigs['pigID']; ?>
                        </div>
                        <div class="name">
                            <?php echo $fetch_pigs['weight']; ?> kg
                        </div>
                        <div class="name">
                            <?php echo $fetch_pigs['farmname']; ?>
                        </div>
                        <div class="name">
                            <?php echo $fetch_pigs['address']; ?>
                        </div>


                        <input type="hidden" name="pigID" value="<?php echo $fetch_pigs['pigID']; ?>">
                        <input type="hidden" name="userID" value=$user_id>
                        <input type="hidden" name="sellerID" value="<?php echo $fetch_pigs['sellerID']; ?>">
                        <input type="hidden" name="price" value="<?php echo $fetch_pigs['price']; ?>">
                        <input type="hidden" name="picture" value="<?php echo $fetch_pigs['picture']; ?>">
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">
                    </form>
                    <?php
                }
            } else {
                echo '<p class="empty">no saleable pigs yet!</p>';
            }
            ?>
        </div>

    </section>


    <!-- <section class="home-contact">

        <div class="content">
            <h3>have any questions?</h3>
            <p>We welcome your questions and feedback! Feel free to ask us anything you'd like or provide any feedback
                you
                have about our services. Your input helps us improve and provide better assistance. Thank you for being
                an
                essential part of our journey!</p>
            <a href="contact.php" class="btn">contact us</a>
        </div> -->

    </section>




    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>