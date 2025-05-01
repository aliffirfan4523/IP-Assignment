<?php # Script - rental_display.php
$page_title = 'Rental Details';
include('./includes/function.php');
include('./includes/header.html');

$cityCalculation = [
    'total' => 0,
    'first3HTotal' => 0,
    'after3HTotal' => 0,
];

$tandemCalculation = [
    'total' => 0,
    'first3HTotal' => 0,
    'after3HTotal' => 0,
];

// Check if the form has been submitted from rental.php
if (isset($_POST['submit_rental'])) {

    // Retrieve the submitted values
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : '';
    $cityBike = isset($_POST['city']) ? $_POST['city'] : '';
    $tandemBike = isset($_POST['tandem']) ? $_POST['tandem'] : '';
    $rentalDate = isset($_POST['dateRental']) ? $_POST['dateRental'] : '';
    $hours = isset($_POST['hours']) ? $_POST['hours'] : '';

    $separatedDays = explode('|', $_POST['days']);
    $isWeekday = isWeekday($rentalDate);

    //insert selected bike to array bikes for display
    $bikes = array();
    !empty($cityBike) ? $bikes[] = 'City Bicycle' : '';
    !empty($tandemBike) ? $bikes[] = 'Tandem Bicycle' : '';

    //verify is 1 bike selected at least
    if (!$cityBike && !$tandemBike) {
        displayMessage("Please select at least 1 bike.", "Back to main page");
    }

    //verify is selected day is same as rental date
    if (!isRentDateSameAsSelectedDay($separatedDays, $rentalDate)) {
        displayMessage("The rental date is different from the selected rental day.", "Back to main page");
    }

    //verify is rental duration is not empty
    if ($hours <= 0) {
        displayMessage("Please enter at least 1 hour minimum.", "Back to main page");
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
        '<li><strong>Rental Day:</strong> ' . htmlspecialchars($separatedDays[1]) . '</li>' .
        '<li><strong>Rental Duration:</strong> ';
    echo htmlspecialchars($hours) . ' hour(s)';
    echo '<li><strong>Rental Amount:</strong> ';

    echo '<table border="1">';
    echo '<tr> <th>Bicycle Type</th>';
    if ($isWeekday) {
        echo '<th>Weekday Rental (RM)</th>';
    } else {
        echo '<th>Weekend Rental (RM)</th>';
    }
    echo '<th>Rental Duration</th>
        <th>Total Amount (RM)</th> </tr>';

    if ($cityBike) {
        $cityCalculation = calculateRental($hours, $cityRates['weekday'], $cityRates['weekend'], $isWeekday);
        displayRentalRow("City Bicycle", $hours, $cityCalculation);
    }

    if ($tandemBike) {
        $tandemCalculation = calculateRental($hours, $tandemRates['weekday'], $tandemRates['weekend'], $isWeekday);
        displayRentalRow("Tandem Bicycle", $hours, $tandemCalculation);
    }
    echo '<td colspan="3">Total Amount</td><td> RM ' . $cityCalculation['total'] + $tandemCalculation['total'] . '</td>';
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