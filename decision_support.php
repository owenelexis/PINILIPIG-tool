<?php

@include 'config.php';

session_start();

// automatic age change
$pig_info = mysqli_query($conn, "SELECT * FROM `pig`");
while ($pig = mysqli_fetch_assoc($pig_info)) {
    $pigID = $pig["pigID"];
    $birthdate = $pig['birthdate'];
    $current_date = date('Y-m-d'); // Current date in the format YYYY-MM-DD

    // Calculate the age in days
    $age_in_days = floor((strtotime($current_date) - strtotime($birthdate)) / (60 * 60 * 24));
    $age_in_days++;
    mysqli_query($conn, "UPDATE `pig` SET age = '$age_in_days' WHERE pigID = '$pigID'");
}

// automatic category change
$pig_info = mysqli_query($conn, "SELECT * FROM `pig` WHERE category != 'sow'");
while ($pig = mysqli_fetch_assoc($pig_info)) {
    $pigID = $pig["pigID"];
    $age = $pig["age"];
    
    if ($age <= 44) {
        mysqli_query($conn, "UPDATE `pig` SET category = 'Piglet' WHERE pigID = '$pigID'");
    } elseif ($age >= 45 && $age <= 119) {
        mysqli_query($conn, "UPDATE `pig` SET category = 'Weaners' WHERE pigID = '$pigID'");
    } elseif ($age >= 120 && $age <= 179) {
        mysqli_query($conn, "UPDATE `pig` SET category = 'Grower' WHERE pigID = '$pigID'");
    } elseif ($age >= 180) {
        mysqli_query($conn, "UPDATE `pig` SET category = 'Fattener' WHERE pigID = '$pigID'");
    }
}

// decision support main
$pig_info = mysqli_query($conn, "SELECT * FROM `pig` WHERE category IN ('grower','fattener')");
if (mysqli_num_rows($pig_info) > 0) {
    while ($fetch_pigs = mysqli_fetch_assoc($pig_info)) {
        $pigID = $fetch_pigs['pigID'];
        $age = $fetch_pigs['age'];
        $pig_status = $fetch_pigs['pig_status'];
        $vaccine_iron = $fetch_pigs['vaccine_iron'];
        $vaccine_respisure = $fetch_pigs['vaccine_respisure'];
        $vaccine_hogcholera = $fetch_pigs['vaccine_hogcholera'];
        $price = $fetch_pigs['price'];

        if ($age > 120 && $pig_status == 'Healthy' && $vaccine_iron == 'Completed' && $vaccine_respisure == 'Completed' && $vaccine_hogcholera == 'Completed') {
            $tag_saleable = mysqli_query($conn, "UPDATE `pig` SET saleable = 'Yes' WHERE pigID = '$pigID'") or die('query failed');
        }
    }
}

// decision support sick, deceased, ordered, and sold
$pig_info = mysqli_query($conn, "SELECT * FROM `pig`");
if (mysqli_num_rows($pig_info) > 0) {
    while ($fetch_pigs = mysqli_fetch_assoc($pig_info)) {
        $pigID = $fetch_pigs['pigID'];
        $age = $fetch_pigs['age'];
        $pig_status = $fetch_pigs['pig_status'];
        $vaccine_iron = $fetch_pigs['vaccine_iron'];
        $vaccine_respisure = $fetch_pigs['vaccine_respisure'];
        $vaccine_hogcholera = $fetch_pigs['vaccine_hogcholera'];
        $price = $fetch_pigs['price'];

        if ($pig_status == 'Sold' or $pig_status == 'Sick' or $pig_status == 'Deceased' or $pig_status == 'Ordered' or $price == NULL) {
            $tag_saleable = mysqli_query($conn, "UPDATE `pig` SET saleable = 'No' WHERE pigID = '$pigID'") or die('query failed');
            $remove_cart - mysqli_query($conn, "DELETE FROM `cart` WHERE pigID = '$pigID'");
        }
    }
}

// decision support piglet maliit and sow
$pig_info = mysqli_query($conn, "SELECT * FROM `pig` WHERE category IN ('piglet','sow')");
if (mysqli_num_rows($pig_info) > 0) {
    while ($fetch_pigs = mysqli_fetch_assoc($pig_info)) {
        $pigID = $fetch_pigs['pigID'];
        $category = $fetch_pigs['category'];
        $age = $fetch_pigs['age'];
        $pig_status = $fetch_pigs['pig_status'];
        $vaccine_iron = $fetch_pigs['vaccine_iron'];
        $vaccine_respisure = $fetch_pigs['vaccine_respisure'];
        $vaccine_hogcholera = $fetch_pigs['vaccine_hogcholera'];
        $price = $fetch_pigs['price'];

        $tag_saleable = mysqli_query($conn, "UPDATE `pig` SET saleable = 'No' WHERE pigID = '$pigID'") or die('query failed');
    }
}


?>