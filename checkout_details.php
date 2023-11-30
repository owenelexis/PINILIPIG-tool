<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:index.php');
}
;

if (isset($_POST['order'])) {

    $d_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);
    $p_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
    $additional = mysqli_real_escape_string($conn, $_POST['additional']);
    $full_address = $address . ', ' . $barangay;

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE userID = '$user_id'") or die('query failed');
    if (mysqli_num_rows($cart_query) > 0) {
        while ($cart_item = mysqli_fetch_assoc($cart_query)) {
            $sellerID = mysqli_real_escape_string($conn, $cart_item['sellerID']);
            $pigID = mysqli_real_escape_string($conn, $cart_item['pigID']);
            $price = mysqli_real_escape_string($conn, $cart_item['price']);

            $query = "INSERT INTO `transaction` (buyer, seller, delivery_address, pigs, total_amount, status, delivery_method, payment_method, additional, temp) 
                      VALUES ('$user_id', '$sellerID', '$full_address', '$pigID', '$price', 'Pending', '$d_method', '$p_method', '$additional', '0')";

            mysqli_query($conn, $query) or die('query failed');
            mysqli_query($conn,"UPDATE `pig` SET saleable = 'No', pig_status = 'Ordered' WHERE pigID ='$pigID'") or die('query failed');
        }
    }

    mysqli_query($conn, "DELETE FROM `cart` WHERE userID = '$user_id'") or die('query failed');
    $message[] = 'order placed successfully!';

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>checkout</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php @include 'header.php'; ?>

    <section class="heading">
        <h3>checkout order</h3>
        <p> <a href="home.php">home</a> / checkout </p>
    </section>

    <section class="display-order">
        <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE userID = '$user_id'") or die('query failed');
        if (mysqli_num_rows($select_cart) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                $total_price = ($fetch_cart['price']);
                $grand_total += $total_price;
                ?>
                <p> Pig number:
                    <?php echo $fetch_cart['pigID'] ?> <span>(
                        <?php echo '₱' . $fetch_cart['price'] ?>)
                    </span>
                </p>
                <?php
            }
        } else {
            echo '<p class="empty">your cart is empty</p>';
        }
        ?>
        <!-- <div class="grand-total">grand total : <span>₱<?php echo $grand_total; ?>.00</span></div> -->
    </section>

    <section class="checkout">

        <form action="" method="POST" id="orderForm">

            <h3>place your order</h3>

            <div class="flex">
                <div class="inputBox">
                    <span>Delivery Method :</span>
                    <select name="delivery_method">
                        <option value="self_pickup">self pickup</option>
                        <option value="deliver_to_address">deliver to address (₱100 fee/farm)</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Payment Method :</span>
                    <select name="payment_method">
                        <option value="COD">cash on delivery</option>
                        <option value="GCASH">gcash</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>House No./ Street / Block</span>
                    <input type="text" name="address">
                </div>
                <div class="inputBox">
                    <span>Barangay :</span>
                    <select name="barangay">
                        <option value="" disabled selected> Please Select your Barangay</option>
                        <option value="balite">Balite</option>
                        <option value="burgos">Burgos</option>
                        <option value="geronimo">Geronimo</option>
                        <option value="macabud">Macabud</option>
                        <option value="manggahan">Manggahan</option>
                        <option value="mascap">Mascap</option>
                        <option value="puray">Puray</option>
                        <option value="rosario">Rosario</option>
                        <option value="san_isidro">San Isidro</option>
                        <option value="san_jose">San Jose</option>
                        <option value="san_rafael">San Rafael</option>
                    </select>
                </div>
                <div class="inputBox">
                    <span>Additional Information: </span>
                    <input type="text" name="additional" cols="30" rows="10">
                </div>
            </div>

            <input type="submit" name="order" value="Confirm and order" class="btn">

        </form>

    </section>

    <?php @include 'footer.php'; ?>

    <script src="js/script.js"></script>


</body>

</html>