<?php
if (isset($message)) {
    foreach ($message as $message) {
        echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
}
?>
<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
    header('location:loginseller.php');
}
;

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
    header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/icon.png" type="images/x-icon">
    <title>PINILI-PIG: Forecast</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
        .image-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .arrow-button {
            font-size: 15px;
        }
    </style>

</head>

<body>

    <?php @include 'seller_header.php'; ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha384-GLhlTQ8iGGn538ui4PrOZlTjkPteRfe/G9fA9SNADOlAd9iAI2B+qTkPxPr4bR" crossorigin="anonymous">



        <link rel="stylesheet" href="css/admin_style.css">

        <script defer src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script defer src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script defer src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
        <script defer src="tablescript.js"></script>
        <title>Document</title>
    </head>

    <body>
        <div class="card card-outline rounded-0 card-green">
            <div class="card-header">
                <!-- <h3 class="card-title">Forecast</h3> -->
                <h1 class="title">forecast</h1>
            </div>
            <section class="add-price">
                <form action="" method="POST" enctype="multipart/form-data">

                    <?php
                    $currentYearMonth = date('Ym');
                    // Construct the query
                    $query = "SELECT id, YearMonth, Forecast, yhat_lower, yhat_upper 
                            FROM pforecast 
                            WHERE YearMonth >= '$currentYearMonth'
                            ORDER BY YearMonth
                            LIMIT 3";
                    $result = mysqli_query($conn, $query);

                    // Fetch the first row
                    $current_month = mysqli_fetch_assoc($result);

                    // Fetch the second row
                    $next_month = mysqli_fetch_assoc($result);

                    // Fetch the third row
                    $far_month = mysqli_fetch_assoc($result);

                    // Ensure that the result set is freed
                    mysqli_free_result($result);

                    // Calculate the percentage change between $next_month and $far_month
                    $highpercentage_change = (($far_month['yhat_upper'] - $next_month['yhat_upper']) / $next_month['yhat_upper']) * 100;
                    $averagepercentage_change = (($far_month['Forecast'] - $next_month['Forecast']) / $next_month['Forecast']) * 100;
                    $lowpercentage_change = (($far_month['yhat_lower'] - $next_month['yhat_lower']) / $next_month['yhat_lower']) * 100;

                    // Round off to the nearest whole number
                    $highrounded_percentage = round($highpercentage_change);
                    $averagerounded_percentage = round($averagepercentage_change);
                    $lowrounded_percentage = round($lowpercentage_change);

                    ?>

                    <h3>Price forecast next month</h3>
                    <?php
                    // Your existing code for fetching data and calculating percentages
                    
                    // Display messages based on the percentage changes
                    if ($averagerounded_percentage < 0) {
                        echo "<h4>" . "The price of live pigs per kilo will reduce by " . abs($averagerounded_percentage) . "%." . "</h4>";
                    } elseif ($averagerounded_percentage > 0) {
                        echo "<h4>" . "The price of live pigs per kilo will increase by " . abs($averagerounded_percentage) . "%." . "</h4>";
                    } else {
                        echo "<h4>" . "The price of live pigs per kilo will remain unchanged." . "</h4>";
                    }
                    ?>
                </form>
            </section>
            <section class="graph">
                <form>

                    <?php
                    $currentYear = date('Y');

                    // Construct the query to get the image for the current year
                    $imageQuery = "SELECT image FROM forecast_pictures WHERE year = '$currentYear'";
                    $imageResult = mysqli_query($conn, $imageQuery);

                    // Check if there is a result
                    if ($imageResult) {
                        $fetch_forecast = mysqli_fetch_assoc($imageResult);

                        // Check if an image is found for the current year
                        if ($fetch_forecast) {
                            echo '<h3>Graph for ' . $currentYear . '</h3>';
                            echo '<img class="image" src="forecast/' . $fetch_forecast['image'] . '" alt="">';
                        } else {
                            echo '<p>No image found for the current year.</p>';
                        }

                        // Free the result set
                        mysqli_free_result($imageResult);
                    } else {
                        // Handle the query error if needed
                        echo '<p>Error retrieving image data.</p>';
                    }
                    ?>
                    <h1 class="forecast">© Raw data from PSA OpenSTAT Price prediction using FBProphet</h1>
                </form>
            </section>
            <div class="card-body">
                <div class="container-fluid">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Year & Month</th>
                                <th>Forecast</th>
                                <th>Lower bound</th>
                                <th>Upper bound</th>
                                <th>Percentage Change</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $currentYearMonth = date('Ym');

                            // Construct the query to get forecasts from the current month onward
                            $query = "SELECT id, YearMonth, Forecast, yhat_lower, yhat_upper 
                                        FROM pforecast 
                                        WHERE YearMonth >= '$currentYearMonth'
                                        ORDER BY YearMonth";
                            $result = mysqli_query($conn, $query);

                            $prevForecast = null;
                            $previousPrice = 170.00;
                            $number = 1;

                            while ($row = mysqli_fetch_array($result)) {
                                $currentForecast = $row['Forecast'];

                                // Calculate percentage change
                                if ($prevForecast !== null) {
                                    $percentageChange = (($currentForecast - $prevForecast) / abs($prevForecast)) * 100;
                                } else {
                                    // Set the initial value of $prevForecast for the first row
                                    $percentageChange = (($currentForecast - $previousPrice) / abs($previousPrice)) * 100;
                                }

                                // Determine arrow and color based on percentage change
                                $arrowIcon = ($percentageChange >= 0) ? '<i class="fas fa-arrow-up" style="color: red;"></i>' : '<i class="fas fa-arrow-down" style="color: green;"></i>';

                                // Format numbers with peso sign and two decimal places
                                $formattedForecast = "₱" . number_format($currentForecast, 2);
                                $formattedLower = "₱" . number_format($row['yhat_lower'], 2);
                                $formattedUpper = "₱" . number_format($row['yhat_upper'], 2);

                                // Display the table row
                                echo "<tr>
                                    <td>" . strtoupper($number) . "</td>
                                    <td>" . strtoupper(date('Y F', strtotime($row['YearMonth']))) . "</td>
                                    <td>" . strtoupper($formattedForecast) . "</td>
                                    <td>" . strtoupper($formattedLower) . "</td>
                                    <td>" . strtoupper($formattedUpper) . "</td>
                                    <td>$arrowIcon" . number_format($percentageChange, 2) . "%</td>
                                </tr>";

                                // Update the previous forecast for the next iteration
                                $prevForecast = $currentForecast;
                                $number += 1;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="js/admin_script.js"></script>
    </body>

    </html>