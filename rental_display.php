<?php # Script - rental_display.php
$page_title = 'Rental Details';
include('./includes/function.php');
include('./includes/header.html');


$cityCalculation = [
    'total' => 0,
    'weekdayFirst3HTotal' => 0,
    'weekdayAfter3HTotal' => 0,
    'weekendFirst3HTotal' => 0,
    'weekendAfter3HTotal' => 0,
];
$tandemCalculation = [
    'total' => 0,
    'weekdayFirst3HTotal' => 0,
    'weekdayAfter3HTotal' => 0,
    'weekendFirst3HTotal' => 0,
    'weekendAfter3HTotal' => 0,
];

// Check if the form has been submitted from rental.php
if (isset($_POST['submit_rental'])) {

    // Retrieve the submitted values
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
    $cityBike = isset($_POST['city']) ? $_POST['city'] : '';
    $tandemBike = isset($_POST['tandem']) ? $_POST['tandem'] : '';
    $rentalDate = isset($_POST['dateRental']) ? $_POST['dateRental'] : '';
    $days = isset($_POST['days']) ? $_POST['days'] : '';
    $hours = isset($_POST['hours']) ? $_POST['hours'] : '';

    $rentalDuration = ($days * 24) + $hours;
    list($fullDays, $remainingHours) = getFullDaysAndRemainingHours($rentalDuration);
    list($weekdayTotal, $weekendTotal) = $fullDays > 0
        ? countWeekdaysAndWeekends($rentalDate, $fullDays)
        : [0, 0];

    //insert selected bike to array bikes for display
    $bikes = array();
    !empty($cityBike) ? $bikes[] = 'City Bicycle' : '';
    !empty($tandemBike) ? $bikes[] = 'Tandem Bicycle' : '';

    //verify is 1 bike selected at least
    if (!$cityBike && !$tandemBike) {
        echo '<h3 style="text-align: center">Please select at least 1 bike.&emsp; ';
        echo '<button onclick="history.go(-1);">Back to main page</button></h3>';
        exit();
    }
    //verify is rental duration is not empty
    if ($rentalDuration <= 0) {
        echo '<h3 style="text-align: center">Please enter at least 1 hour or 1 day.&emsp; ';
        echo '<button onclick="history.go(-1);">Back to main page</button></h3>';
        exit();
    }
    echo '<div class="div-confirmation">';
    echo '<h1 id="mainhead">Rental Confirmation</h1>' .
        '<p>Thank you for your rental request. Here are the details you provided:</p>' .
        '<ul>' .
        '<li><strong>Name:</strong> ' . htmlspecialchars($name) . '</li>' .
        '<li><strong>Mobile Number:</strong> ' . htmlspecialchars($mobile) . '</li>' .
        '<li><strong>Selected Bicycles:</strong> ';
    echo implode(', ', $bikes);
    echo '</li>' .
        '<li><strong>Rental Date:</strong> ' . htmlspecialchars($rentalDate) . '</li>' .
        '<li><strong>Rental Duration:</strong> ';
    if (!empty($days) && $days > 0) {
        echo htmlspecialchars($days) . ' day(s)';
        if (!empty($hours) && $hours > 0) {
            echo ' and ' . htmlspecialchars($hours) . ' hour(s)';
        }
    } elseif (!empty($hours) && $hours > 0) {
        echo htmlspecialchars($hours) . ' hour(s)';
    } else {
        echo 'No duration specified.';
    }
    echo '<li><strong>Rental Amount:</strong> ';

    echo '<table border="1">';
    echo '<tr>
        <th>Bicycle Type</th>
        <th>Weekday Rental (RM)</th>
        <th>Weekend Rental (RM)</th>
        <th>Rental Duration</th>
        <th>Total Amount (RM)</th>
    </tr>';

    if ($cityBike) {
        $cityCalculation = calculateRentalAmountByDay(
            $weekdayTotal,
            $weekendTotal,
            $remainingHours,
            $cityRates['weekday'],
            $cityRates['weekend'],
            $rentalDate,
            $fullDays
        );
        displayRentalRow("City Bicycle", $rentalDuration, $cityCalculation);
    }

    if ($tandemBike) {
        $tandemCalculation = calculateRentalAmountByDay(
            $weekdayTotal,
            $weekendTotal,
            $remainingHours,
            $tandemRates['weekday'],
            $tandemRates['weekend'],
            $rentalDate,
            $fullDays
        );

        displayRentalRow("Tandem Bicycle", $rentalDuration, $tandemCalculation);
    }
    echo '<td colspan="4">Total Amount</td><td> RM ' . $cityCalculation['total'] + $tandemCalculation['total'] . '</td>';
    echo '</table>';
    echo '</li>';
    echo '</li>';
    echo '</ul>';
    echo '<p>We will contact you shortly to confirm your booking.</p>';
    echo '<button onclick="history.go(-1);">Rent More Bicycle</button></h3>';
    echo '</div>';
} else {
    // If the form was not submitted correctly from rental.php
    echo '<h1 id="mainhead">Error!</h1>';
    echo '<p class="error">This page must be accessed after submitting the rental form.</p>';
}

include('./includes/footer.html');
?>