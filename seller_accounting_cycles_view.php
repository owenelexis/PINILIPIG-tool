<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
   header('location:index.php');
}
;


@include 'decision_support.php';
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
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php @include 'seller_header.php'; ?>

   <section class="placed-orders">
      <?php
      $view_id = $_GET['view'];
      $select_cycles = mysqli_query($conn, "SELECT * FROM `cycle` WHERE id = '$view_id'") or die('query failed');
      while ($fetch_cycles = mysqli_fetch_assoc($select_cycles)) {
         echo "<h1 class='title'>" . $fetch_cycles['name'] . "</h1>";
         echo "<h2 class='title'>Date Started: " . $fetch_cycles['date_started'] . "</h1>";
         if ($fetch_cycles['status'] == 'ended') {
            echo "<h2 class='title'>Date Ended: " . $fetch_cycles['date_ended'] . "</h2>";
         }
      }
      ?>
   </section>

   <section class="dashboard">
      <div class="box-container">

         <div class="box">
            <?php
            $total_pigs = 0;
            $select_pigs = mysqli_query($conn, "SELECT * FROM pig WHERE owner = '$seller_id' AND cycle = '$view_id'") or die('query failed');
            $total_pigs = mysqli_num_rows($select_pigs);
            ?>
            <h3>
               <?php echo $total_pigs; ?>
            </h3>
            <p>Pigs</p>
         </div>

         <div class="box">
            <?php
            $sold_pigs = 0;
            $select_pigs = mysqli_query($conn, "SELECT * FROM pig WHERE owner = '$seller_id' AND cycle = '$view_id' AND pig_status = 'Sold'") or die('query failed');
            $sold_pigs = mysqli_num_rows($select_pigs);
            ?>
            <h3>
               <?php echo $sold_pigs; ?>
            </h3>
            <p>Sold Pigs</p>
         </div>

         <div class="box">
            <?php
            $total_expenses = 0;
            $select_expenses = mysqli_query($conn, "SELECT * FROM accounting WHERE seller = '$seller_id' and type = 'expense' AND cycle = '$view_id'") or die('query failed');
            while ($fetch_expenses = mysqli_fetch_assoc($select_expenses)) {
               $total_expenses += $fetch_expenses['value'];
            }
            ;
            ?>
            <h3>
               <?php echo "₱" . number_format($total_expenses, 2); ?>
            </h3>
            <p>Expenses</p>
         </div>

         <div class="box">
            <?php
            $total_sales = 0;
            $select_sales = mysqli_query($conn, "SELECT * FROM accounting WHERE seller = '$seller_id' and type = 'revenue' AND cycle = '$view_id'") or die('query failed');
            while ($fetch_sales = mysqli_fetch_assoc($select_sales)) {
               $total_sales += $fetch_sales['value'];
            }
            ;
            ?>
            <h3>
               <?php echo "₱" . number_format($total_sales, 2); ?>
            </h3>
            <p>Sales</p>
         </div>

      </div>
   </section>

   <section class="dashboard">
      <div class="box-container">

         <div class="box">
            <?php
            $profit = 0;
            $profit = $total_sales - $total_expenses;

            // Check if $total_pigs is not zero before performing the division
            $profit_per_pig = $total_pigs != 0 ? $profit / $total_pigs : 0;
            ?>
            <h3>
               <?php echo "₱" . number_format($profit_per_pig, 2); ?>
            </h3>
            <p>Approximate profit per pig</p>
         </div>

      </div>
   </section>


   <section class="filter">

      <table id="accounting" class="display">
         <thead>
            <tr>
               <th>Date</th>
               <th>Type</th>
               <th>Source</th>
               <th>Details</th>
               <th>Value</th>
            </tr>
         </thead>
         <tbody>
            <?php
            // Modify your SQL query to include the date range filter
            $view_id = $_GET['view'];
            $sql = "SELECT * FROM cycle C LEFT JOIN accounting A ON A.cycle=C.id WHERE seller = '$seller_id' AND C.id = '$view_id'";

            if (!empty($start_date) && !empty($end_date)) {
               $sql .= " AND accounting_date BETWEEN '$start_date' AND '$end_date'";
            }

            $result = mysqli_query($conn, $sql);
            $total = 0; // Initialize total value
            
            if (mysqli_num_rows($result) > 0) {
               while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr>
                        <td>{$row['accounting_date']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['source']}</td>
                        <td>{$row['details']}</td>
                        <td>";

                  if ($row['type'] == 'expense') {
                     // Subtract expenses
                     echo '<span style="color: red;">-₱' . number_format($row['value'], 2) . '</span>';
                     $total -= $row['value'];
                  } elseif ($row['type'] == 'revenue') {
                     // Add revenue
                     echo '<span style="color: green;">₱' . number_format($row['value'], 2) . '</span>';
                     $total += $row['value'];
                  }

                  echo "</td>
                        </tr>";
               }
            } else {
               echo "<tr><td colspan='5'>No data available</td></tr>";
            }
            echo "<tr><td colspan='4'>Total</td>";
            echo "<td><span style=\"color: " . ($total >= 0 ? 'green' : 'red') . "\">₱" . number_format($total, 2) . "</span></td></tr></tr>";

            ?>
         </tbody>
      </table>
   </section>



   <script src="js/admin_script.js"></script>

</body>

</html>