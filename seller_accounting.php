<?php

@include 'config.php';

session_start();

$seller_id = $_SESSION['seller_id'];

if (!isset($seller_id)) {
    header('location:index.php');
    exit();
}


// Initialize start and end date variables
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/icon.png" type="images/x-icon">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <script defer src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script defer src="tablescript.js"></script> -->

    <title>PINILI-PIG: Accounting</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- custom admin css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

    <?php @include 'seller_header.php'; ?>

    <section class="show-products">
        <div class="box-container">
            <div class="box">
                <a class="option-btn" href="seller_accounting.php">All</a>
            </div>
            <div class="box">
                <a class="option-btn" href="seller_accounting_revenue.php">Revenue</a>
            </div>
            <div class="box">
                <a class="option-btn" href="seller_accounting_expense.php">Expense</a>
            </div>
        </div>
    </section>

    <section class ="filter">

        <!-- Add a form for date filtering -->
        <div>
            <form method="post" action="">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">

                <button type="submit">Filter</button>
            </form>
        </div>
        <table id="accounting" class="display">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Cycle</th>
                    <th>Type</th>
                    <th>Source</th>
                    <th>Details</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Modify your SQL query to include the date range filter
                $sql = "SELECT * FROM accounting LEFT JOIN cycle ON accounting.cycle=cycle.id WHERE seller = '$seller_id'";

                if (!empty($start_date) && !empty($end_date)) {
                    $sql .= " AND accounting_date BETWEEN '$start_date' AND '$end_date'";
                }

                $result = mysqli_query($conn, $sql);
                $total = 0; // Initialize total value
                
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                        <td>{$row['accounting_date']}</td>
                        <td>{$row['name']}</td>
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
                echo "<tr><td colspan='5'>Total</td>";
                echo "<td><span style=\"color: " . ($total >= 0 ? 'green' : 'red') . "\">₱" . number_format($total, 2) . "</span></td></tr></tr>";

                ?>
            </tbody>
        </table>

    </section>

    

    <script src="js/admin_script.js"></script>

</body>

</html>