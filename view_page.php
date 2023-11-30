<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
}
;

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
    <link rel="icon" href="images/icon.png" type="images/x-icon">
    <title>PINILI-PIG: View</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="quick-view">

        <h1 class="title">pig details</h1>

        <?php
        if (isset($_GET['pid'])) {
            $pid = $_GET['pid'];
            $select_pigs = mysqli_query($conn, "SELECT * FROM `pig` JOIN `seller` ON pig.owner=seller.sellerID WHERE pigID = '$pid'") or die('query failed');
            if (mysqli_num_rows($select_pigs) > 0) {
                while ($fetch_pigs = mysqli_fetch_assoc($select_pigs)) {
                    ?>
                    <form action="" method="POST">
                        <img src="uploaded_img/<?php echo $fetch_pigs['picture']; ?>" alt="" class="image">
                        <div class="name">Pig number:
                            <?php echo $fetch_pigs['pigID']; ?>
                        </div>
                        <div class="name">
                            <?php echo $fetch_pigs['farmname']; ?>
                        </div>
                        <div class="name">
                            <?php echo $fetch_pigs['address']; ?>
                        </div>
                        <div class="price">₱
                            <?php echo $fetch_pigs['price']; ?> :
                            <?php echo $fetch_pigs['weight']; ?> kg
                        </div>
                        <div class="details">Category:
                            <?php echo $fetch_pigs['category']; ?>
                        </div>
                        <div class="details">Sex:
                            <?php echo $fetch_pigs['sex']; ?>
                        </div>
                        <div class="details">Birthdate:
                            <?php echo $fetch_pigs['birthdate']; ?>
                        </div>
                        <div class="details">Age:
                            <?php echo $fetch_pigs['age']; ?> days
                        </div>
                        <div class="details">Castration date:
                            <?php echo $fetch_pigs['castration_date']; ?>
                        </div>
                        <div class="details">Iron Vaccine:
                            <?php echo $fetch_pigs['vaccine_iron']; ?>
                        </div>
                        <div class="details">Respisure vaccine:
                            <?php echo $fetch_pigs['vaccine_respisure']; ?>
                        </div>
                        <div class="details">Hogcholera vaccine:
                            <?php echo $fetch_pigs['vaccine_hogcholera']; ?>
                        </div>
                        <div class="details">Last updated:
                            <?php echo $fetch_pigs['last_update']; ?>
                        </div>
                        <input type="hidden" name="pigID" value="<?php echo $fetch_pigs['pigID']; ?>">
                        <input type="hidden" name="userID" value=$user_id>
                        <input type="hidden" name="sellerID" value="<?php echo $fetch_pigs['sellerID']; ?>">
                        <input type="hidden" name="price" value="<?php echo $fetch_pigs['price']; ?>">
                        <input type="hidden" name="picture" value="<?php echo $fetch_pigs['picture']; ?>">
                        <input type="submit" value="add to cart" name="add_to_cart" class="btn">

                        <div class="product__item">
                            <div class="product__banner">
                                <a href="details.html" class="product__images">

                                    <img src="img/product-14-2.jpg" alt="" class="product__img hover" />
                                </a>

                                <div class="product__actions">
                                    <a href="#" class="action__btn" aria-label="Quick View">
                                        <i class="fi fi-rs-eye"></i>
                                    </a>

                                    <a href="#" class="action__btn" aria-label="Add to Wishlist">
                                        <i class="fi fi-rs-heart"></i>

                                        <a href="#" class="action__btn" aria-label="Shuffle">
                                            <i class="fi fi-rs-shuffle"></i>
                                        </a>
                                </div>

                            </div>

                            <div class="product__content">
                                <a href="#" class="action__btn cart__btn" aria-label="Add to Cart">
                                    <i class="fi fi-rs-shopping-bag-add"></i>
                                </a>
                            </div>
                        </div>


                    </form>
                    <?php
                }
            } else {
                echo '<p class="empty">no products details available!</p>';
            }
        }
        ?>

        <div class="more-btn">
            <a href="home.php" class="option-btn">go to home page</a>
        </div>

    </section>

    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>