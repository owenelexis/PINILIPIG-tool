<?php


 // hogcholera vaccine
 $hogcholera_vaccine = mysqli_query($conn, "SELECT * FROM pig WHERE owner='$seller_id' AND age < 30 AND vaccine_hogcholera = 'None'") or die('query failed');
 $result_found_hogcholera_vaccine = false; // Variable to track if any result meets the condition
 $hogcholera_vaccine_results = array(); // Array to store results
 
 while ($fetch_hogcholera_vaccine = mysqli_fetch_assoc($hogcholera_vaccine)) {
     $pigID_hogcholera_vaccine = $fetch_hogcholera_vaccine['pigID'];
     $birthdate_hogcholera_vaccine = $fetch_hogcholera_vaccine['birthdate'];
 
     // Calculate the date 3 days after birthdate
     $vaccine_date_hogcholera_vaccine = date('Y-m-d', strtotime($birthdate_hogcholera_vaccine . ' + 3 days'));
 
     // Calculate how many days are left
     $current_date = date('Y-m-d');
     $days_left_hogcholera_vaccine = floor((strtotime($vaccine_date_hogcholera_vaccine) - strtotime($current_date)) / (60 * 60 * 24));
 
     if ($days_left_hogcholera_vaccine < 4) {
         $result_found_hogcholera_vaccine = true;
         // Store results in the array
         $hogcholera_vaccine_results[] = array('pigID' => $pigID_hogcholera_vaccine, 'vaccine_date' => $vaccine_date_hogcholera_vaccine, 'days_left' => $days_left_hogcholera_vaccine);
     }
 }


?>