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


<link rel="stylesheet" href="css/admin_style.css">

<header class="header">

   <div class="flex">

      <a href="seller_page.php" class="logo">Seller<span>Panel</span></a>

      <nav class="navbar">
         <ul>
            <li><a href="seller_products_all.php">Pigs</a>
               <ul>
                  <li><a href="seller_products_sow.php">Sow</a></li>
                  <li><a href="seller_products_piglet.php">Piglet</a></li>
                  <li><a href="seller_products_weaner.php">Weaner</a></li>
                  <li><a href="seller_products_grower.php">Grower</a></li>
                  <li><a href="seller_products_fattener.php">Fattener</a></li>
               </ul>
            </li>
            <li><a href="seller_feeds.php">Feeds</a></li>
            <li><a href="seller_vaccines.php">Medications</a></li>
            <li><a href="seller_others.php">Other expenses</a></li>
            <li>
               <a href="seller_orders.php">Orders</a>
               <ul>
                  <li><a href="seller_orders_cancelled.php">Cancelled</a></li>
                  <li><a href="seller_orders.php">Pending</a></li>
                  <li><a href="seller_orders_delivered.php">Delivered</a></li>
               </ul>
            </li>
            <li><a href="seller_accounting.php">Accounting</a>
               <ul>
                  <li><a href="seller_accounting_cycles.php">Cycles</a></li>
               </ul>
            </li>
            <!-- <li><a href="seller_contacts.php">Messages</a></li> -->
            <li><a href="seller_forecast.php">Price forecasting</a></li>
         </ul>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <!-- <div id="bell-btn" class="fas fa-bell"></div> -->
         <div id="user-btn" class="fas fa-user"></div>
      </div>


      <div class="account-box">
         <p>username : <span>
               <?php echo $_SESSION['seller_name']; ?>
            </span></p>
         <p>email : <span>
               <?php echo $_SESSION['seller_email']; ?>
            </span></p>
         <a href="logout.php" class="delete-btn">logout</a>
      </div>

   </div>

</header>