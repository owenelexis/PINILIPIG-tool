<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;

if (isset($_POST['update_prices'])) {
   $price = mysqli_real_escape_string($conn, $_POST['price']);

   $pig_info = mysqli_query($conn, "SELECT * FROM `pig` WHERE owner = '$seller_id' AND category IN ('fattener','grower')");

   if (mysqli_num_rows($pig_info) > 0) {
      while ($fetch_pigs = mysqli_fetch_assoc($pig_info)) {
         $new_price = $fetch_pigs['weight'] * $price;
         $pigID = $fetch_pigs['pigID'];
         $update_price = mysqli_query($conn, "UPDATE `pig` SET price = '$new_price' WHERE pigID = '$pigID' and pig_status IN ('Healthy', 'Sick') AND category IN ('Grower', 'Fattener')") or die('query failed');
      }
   }
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
   <title>PINILI-PIG: Dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <h1 class="title">dashboard</h1>

   <section class="add-price">
      <form action="" method="POST" enctype="multipart/form-data">
         <h3>Update Pig Prices per Kilo</h3>
         <p>Only pigs in "Grower" and "Fattener" category will be affected</p>
         <p>Eligible pigs will also automatically be tagged as saleable once confirmed</p>

         <input type="number" class="box" required step="0.01" pattern="^\d+(\.\d{1,2})?$" placeholder="Price"
            name="price">
         <input type="submit" value="Confirm" name="update_prices" class="btn">
      </form>

      <form action="" method="POST" enctype="multipart/form-data">

         <?php
         $currentYearMonth = date('Ym');
         // Construct the query
         $query = "SELECT id, YearMonth, Forecast, yhat_lower, yhat_upper 
                      FROM pforecast 
                      WHERE YearMonth >= '$currentYearMonth'
                      ORDER BY YearMonth
                      LIMIT 2";
         $result = mysqli_query($conn, $query);

         // Fetch the first row
         $current_month = mysqli_fetch_assoc($result);

         // Fetch the second row
         $next_month = mysqli_fetch_assoc($result);

         // Ensure that the result set is freed
         mysqli_free_result($result);


         ?>
         <h3>Price forecast this month</h3>
         <h3><span style="color: red;">Upper:
               <?php echo "₱" . number_format($next_month['yhat_upper'], 2) ?>
            </span></h3>
         <h3><span style="color: orange;">Average:
               <?php echo "₱" . number_format($next_month['Forecast'], 2) ?>
            </span></h3>
         <h3><span style="color: green;">Lower:
               <?php echo "₱" . number_format($next_month['yhat_lower'], 2) ?>
            </span></h3>

      </form>

   </section>

   <section class="dashboard">



      <div class="box-container">

         <div class="box">
            <?php
            $select_pigs = mysqli_query($conn, "SELECT * FROM pig WHERE owner = '$seller_id' and pig_status IN ('Healthy', 'Sick', 'Ordered') and category = 'grower'") or die('query failed');
            $total_pigs = mysqli_num_rows($select_pigs);
            ?>
            <h3>
               <?php echo $total_pigs; ?>
            </h3>
            <p>Grower</p>
         </div>

         <div class="box">
            <?php
            $select_pigs = mysqli_query($conn, "SELECT * FROM pig WHERE owner = '$seller_id' and pig_status IN ('Healthy', 'Sick', 'Ordered') and category = 'fattener'") or die('query failed');
            $total_pigs = mysqli_num_rows($select_pigs);
            ?>
            <h3>
               <?php echo $total_pigs; ?>
            </h3>
            <p>Fattener</p>
         </div>



         <div class="box">
            <?php
            $select_pigs = mysqli_query($conn, "SELECT COUNT(pigID) FROM pig WHERE owner = '$seller_id' and saleable ='Yes' and pig_status='Healthy'") or die('query failed');
            $total_pigs_row = mysqli_fetch_assoc($select_pigs);
            $total_pigs = $total_pigs_row['COUNT(pigID)'];
            ?>
            <h3>
               <?php echo $total_pigs; ?>
            </h3>
            <p>Saleable Pigs</p>
         </div>

         <div class="box">
            <?php
            $select_pigs = mysqli_query($conn, "SELECT COUNT(pigID) FROM pig WHERE owner = '$seller_id' and pig_status ='Sick' and pig_status='Healthy'") or die('query failed');
            $total_pigs_row = mysqli_fetch_assoc($select_pigs);
            $total_pigs = $total_pigs_row['COUNT(pigID)'];
            ?>
            <h3>
               <?php echo $total_pigs; ?>
            </h3>
            <p>Sick Pigs</p>
         </div>

         <div class="box">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM transaction WHERE seller = '$seller_id' and status IN ('Pending', 'Preparing')") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
            ?>
            <h3>
               <?php echo $number_of_orders; ?>
            </h3>
            <p>Orders placed</p>
         </div>

         <div class="box">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM transaction WHERE seller = '$seller_id' and status = 'Delivered'") or die('query failed');
            $number_of_orders = mysqli_num_rows($select_orders);
            ?>
            <h3>
               <?php echo $number_of_orders; ?>
            </h3>
            <p>Orders fulfilled</p>
         </div>

         <div class="box">
            <?php
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM accounting WHERE seller = '$seller_id' and type = 'expense'") or die('query failed');
            while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
               $total_completes += $fetch_completes['value'];
            }
            ;
            ?>
            <h3>
               <?php echo "₱" . number_format($total_completes, 2); ?>
            </h3>
            <p>Expenses</p>
         </div>

         <div class="box">
            <?php
            $total_completes = 0;
            $select_completes = mysqli_query($conn, "SELECT * FROM accounting WHERE seller = '$seller_id' and type = 'revenue'") or die('query failed');
            while ($fetch_completes = mysqli_fetch_assoc($select_completes)) {
               $total_completes += $fetch_completes['value'];
            }
            ;
            ?>
            <h3>
               <?php echo "₱" . number_format($total_completes, 2); ?>
            </h3>
            <p>Sales</p>
         </div>

         
      </div>

   </section>

   <!-- green tab -->
   <section class="home-contact">
   </section>


   <h1 class="title">vaccine schedule</h1>

   <section class="add-price">

      <?php
      // iron vaccine 1
      $first_iron_vaccine = mysqli_query($conn, "SELECT * FROM pig WHERE owner='$seller_id' AND age < 3 AND vaccine_iron = 'None' AND pig_status IN ('Healthy', 'Sick')") or die('query failed');
      $result_found_iron_vaccine_one = false; // Variable to track if any result meets the condition
      $iron_vaccine_one_results = array(); // Array to store results
      
      while ($fetch_first_iron_vaccine = mysqli_fetch_assoc($first_iron_vaccine)) {
         $pigID_iron_vaccine_1 = $fetch_first_iron_vaccine['pigID'];
         $birthdate_iron_vaccine_1 = $fetch_first_iron_vaccine['birthdate'];

         // Calculate the date 3 days after birthdate
         $vaccine_date_iron_vaccine_1 = date('Y-m-d', strtotime($birthdate_iron_vaccine_1 . ' + 3 days'));

         // Calculate how many days are left
         $current_date = date('Y-m-d');
         $days_left_iron_vaccine_1 = floor((strtotime($vaccine_date_iron_vaccine_1) - strtotime($current_date)) / (60 * 60 * 24));
         $days_left_iron_vaccine_1--;

         if ($days_left_iron_vaccine_1 < 6) {
            $result_found_iron_vaccine_one = true;
            // Store results in the array
            $iron_vaccine_one_results[] = array('pigID' => $pigID_iron_vaccine_1, 'vaccine_date' => $vaccine_date_iron_vaccine_1, 'days_left' => $days_left_iron_vaccine_1);
         }
      }


      // iron vaccine 2
      $second_iron_vaccine = mysqli_query($conn, "SELECT * FROM pig WHERE owner='$seller_id' AND age < 10 AND vaccine_iron IN ('First Shot', 'None') AND pig_status IN ('Healthy', 'Sick')") or die('query failed');
      $result_found_iron_vaccine_two = false; // Variable to track if any result meets the condition
      $iron_vaccine_two_results = array(); // Array to store results
      
      while ($fetch_second_iron_vaccine = mysqli_fetch_assoc($second_iron_vaccine)) {
         $pigID_iron_vaccine_2 = $fetch_second_iron_vaccine['pigID'];
         $birthdate_iron_vaccine_2 = $fetch_second_iron_vaccine['birthdate'];

         // Calculate the date 3 days after birthdate
         $vaccine_date_iron_vaccine_2 = date('Y-m-d', strtotime($birthdate_iron_vaccine_2 . ' + 10 days'));

         // Calculate how many days are left
         $current_date = date('Y-m-d');
         $days_left_iron_vaccine_2 = floor((strtotime($vaccine_date_iron_vaccine_2) - strtotime($current_date)) / (60 * 60 * 24));
         $days_left_iron_vaccine_2--;

         if ($days_left_iron_vaccine_2 < 6) {
            $result_found_iron_vaccine_two = true;
            // Store results in the array
            $iron_vaccine_two_results[] = array('pigID' => $pigID_iron_vaccine_2, 'vaccine_date' => $vaccine_date_iron_vaccine_2, 'days_left' => $days_left_iron_vaccine_2);
         }
      }


      // respisure vaccine 1
      $first_respisure_vaccine = mysqli_query($conn, "SELECT * FROM pig WHERE owner='$seller_id' AND age < 21 AND vaccine_respisure = 'None' AND pig_status IN ('Healthy', 'Sick')") or die('query failed');
      $result_found_respisure_vaccine_one = false; // Variable to track if any result meets the condition
      $respisure_vaccine_one_results = array(); // Array to store results
      
      while ($fetch_first_respisure_vaccine = mysqli_fetch_assoc($first_respisure_vaccine)) {
         $pigID_first_respisure_vaccine = $fetch_first_respisure_vaccine['pigID'];
         $birthdate_first_respisure_vaccine = $fetch_first_respisure_vaccine['birthdate'];

         // Calculate the date 3 days after birthdate
         $vaccine_date_respisure_vaccine_1 = date('Y-m-d', strtotime($birthdate_first_respisure_vaccine . ' + 21 days'));

         // Calculate how many days are left
         $current_date = date('Y-m-d');
         $days_left_respisure_vaccine_1 = floor((strtotime($vaccine_date_respisure_vaccine_1) - strtotime($current_date)) / (60 * 60 * 24));
         $days_left_respisure_vaccine_1--;

         if ($days_left_respisure_vaccine_1 < 6) {
            $result_found_respisure_vaccine_one = true;
            // Store results in the array
            $respisure_vaccine_one_results[] = array('pigID' => $pigID_first_respisure_vaccine, 'vaccine_date' => $vaccine_date_respisure_vaccine_1, 'days_left' => $days_left_respisure_vaccine_1);
         }
      }

      // respisure vaccine 2
      $second_respisure_vaccine = mysqli_query($conn, "SELECT * FROM pig WHERE owner='$seller_id' AND age < 37 AND vaccine_respisure IN ('First Shot', 'None') AND pig_status IN ('Healthy', 'Sick')") or die('query failed');
      $result_found_respisure_vaccine_second = false; // Variable to track if any result meets the condition
      $respisure_vaccine_two_results = array(); // Array to store results
      
      while ($fetch_second_respisure_vaccine = mysqli_fetch_assoc($second_respisure_vaccine)) {
         $pigID_second_respisure_vaccine = $fetch_second_respisure_vaccine['pigID'];
         $birthdate_second_respisure_vaccine = $fetch_second_respisure_vaccine['birthdate'];

         // Calculate the date 3 days after birthdate
         $vaccine_date_respisure_vaccine_2 = date('Y-m-d', strtotime($birthdate_second_respisure_vaccine . ' + 37 days'));

         // Calculate how many days are left
         $current_date = date('Y-m-d');
         $days_left_respisure_vaccine_2 = floor((strtotime($vaccine_date_respisure_vaccine_2) - strtotime($current_date)) / (60 * 60 * 24));
         $days_left_respisure_vaccine_2--;

         if ($days_left_respisure_vaccine_2 < 6) {
            $result_found_respisure_vaccine_second = true;
            // Store results in the array
            $respisure_vaccine_two_results[] = array('pigID' => $pigID_second_respisure_vaccine, 'vaccine_date' => $vaccine_date_respisure_vaccine_2, 'days_left' => $days_left_respisure_vaccine_2);
         }
      }

      // hogcholera vaccine
      $hogcholera_vaccine = mysqli_query($conn, "SELECT * FROM pig WHERE owner='$seller_id' AND age < 30 AND vaccine_hogcholera = 'None' AND pig_status IN ('Healthy', 'Sick')") or die('query failed');
      $result_found_hogcholera_vaccine = false; // Variable to track if any result meets the condition
      $hogcholera_vaccine_results = array(); // Array to store results
      
      while ($fetch_hogcholera_vaccine = mysqli_fetch_assoc($hogcholera_vaccine)) {
         $pigID_hogcholera_vaccine = $fetch_hogcholera_vaccine['pigID'];
         $birthdate_hogcholera_vaccine = $fetch_hogcholera_vaccine['birthdate'];

         // Calculate the date 3 days after birthdate
         $vaccine_date_hogcholera_vaccine = date('Y-m-d', strtotime($birthdate_hogcholera_vaccine . ' + 30 days'));

         // Calculate how many days are left
         $current_date = date('Y-m-d');
         $days_left_hogcholera_vaccine = floor((strtotime($vaccine_date_hogcholera_vaccine) - strtotime($current_date)) / (60 * 60 * 24));
         $days_left_hogcholera_vaccine--;

         if ($days_left_hogcholera_vaccine < 6) {
            $result_found_hogcholera_vaccine = true;
            // Store results in the array
            $hogcholera_vaccine_results[] = array('pigID' => $pigID_hogcholera_vaccine, 'vaccine_date' => $vaccine_date_hogcholera_vaccine, 'days_left' => $days_left_hogcholera_vaccine);
         }
      }

      if (
         $result_found_iron_vaccine_one ||
         $result_found_iron_vaccine_two ||
         $result_found_respisure_vaccine_one ||
         $result_found_respisure_vaccine_second ||
         $result_found_hogcholera_vaccine
      ) {
         ?>
         <section class="add-vaccine">
            <?php
            // Display Iron Vaccine 1 results if available
            if ($result_found_iron_vaccine_one) {
               ?>
               <form>
                  <h3>Iron Vaccine 1</h3>
                  <?php
                  // Iterate over the array of results
                  foreach ($iron_vaccine_one_results as $result) {
                     ?>
                     <p>
                        <?php echo "Pig ID: {$result['pigID']} || ({$result['vaccine_date']}) || {$result['days_left']} days left"; ?>
                     </p>
                     <?php
                  }
                  ?>
               </form>
               <?php
            }

            // Display Iron Vaccine 2 results if available
            if ($result_found_iron_vaccine_two) {
               ?>
               <form>
                  <h3>Iron Vaccine 2</h3>
                  <?php
                  // Iterate over the array of results
                  foreach ($iron_vaccine_two_results as $result) {
                     ?>
                     <p>
                        <?php echo "Pig ID: {$result['pigID']} || ({$result['vaccine_date']}) || {$result['days_left']} days left"; ?>
                     </p>
                     <?php
                  }
                  ?>
               </form>
               <?php
            }

            // Display Respisure Vaccine 1 results if available
            if ($result_found_respisure_vaccine_one) {
               ?>
               <form>
                  <h3>Respisure Vaccine 1</h3>
                  <?php
                  // Iterate over the array of results
                  foreach ($respisure_vaccine_one_results as $result) {
                     ?>
                     <p>
                        <?php echo "Pig ID: {$result['pigID']} || ({$result['vaccine_date']}) || {$result['days_left']} days left"; ?>
                     </p>
                     <?php
                  }
                  ?>
               </form>
               <?php
            }

            // Display Respisure Vaccine 2 results if available
            if ($result_found_respisure_vaccine_second) {
               ?>
               <form>
                  <h3>Respisure Vaccine 2</h3>
                  <?php
                  // Iterate over the array of results
                  foreach ($respisure_vaccine_two_results as $result) {
                     ?>
                     <p>
                        <?php echo "Pig ID: {$result['pigID']} || ({$result['vaccine_date']}) || {$result['days_left']} days left"; ?>
                     </p>
                     <?php
                  }
                  ?>
               </form>
               <?php
            }

            // Display Hogcholera Vaccine results if available
            if ($result_found_hogcholera_vaccine) {
               ?>
               <form>
                  <h3>Hogcholera Vaccine</h3>
                  <?php
                  // Iterate over the array of results
                  foreach ($hogcholera_vaccine_results as $result) {
                     ?>
                     <p>
                        <?php echo "Pig ID: {$result['pigID']} || ({$result['vaccine_date']}) || {$result['days_left']} days left"; ?>
                     </p>
                     <?php
                  }
                  ?>
               </form>
               <?php
            }
            ?>
         </section>
         <?php
      } else {
         ?>
         <section class="add-price">
            <form>
               <h3>No pig is close due</h3>
            </form>
         </section>
         <?php
      }
      ?>
   </section>





   <!-- green tab -->
   <section class="home-contact">
   </section>



   <script src="js/admin_script.js"></script>

</body>

</html>