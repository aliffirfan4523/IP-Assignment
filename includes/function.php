<?php


$cityRates = [
    'weekday' => [15, 2.5],
    'weekend' => [30, 7.5]
];

$tandemRates = [
    'weekday' => [25, 3.5],
    'weekend' => [35, 8.5]
];

function isWeekday($date)
{
    $dayOfWeek = date('N', strtotime($date));
    // 'N' returns 1 (for Monday) through 7 (for Sunday)
    return ($dayOfWeek >= 1 && $dayOfWeek <= 5);
}

function isRentDateSameAsSelectedDay($days, $rentalDate)
{
    $dayOfWeek = date('N', timestamp: strtotime($rentalDate));
    return $days[0] == $dayOfWeek;
}


function calculateRental($hours, $weekdayRate, $weekendRate, $isWeekday)
{
    $first3HTotal = 0;
    $after3HTotal = 0;

    if ($isWeekday) {
        if ($hours <= 3) {
            $first3HTotal += 1 * $weekdayRate[0];
        } else {
            $first3HTotal += 1 * $weekdayRate[0];
            $after3HTotal += ($hours - 3) * $weekdayRate[1];
        }
    } else {
        if ($hours <= 3) {
            $first3HTotal += $hours * $weekendRate[0];
        } else {
            $first3HTotal += 1 * $weekendRate[0];
            $after3HTotal += ($hours - 3) * $weekendRate[1];
        }
    }


    // Final totals
    $total = $first3HTotal + $after3HTotal;

    return [
        'total' => $total,
        'first3HTotal' => $first3HTotal,
        'after3HTotal' => $after3HTotal,
    ];
}

// Function to display  messages
function displayMessage($message, $buttonText = "Back to main page")
{
    echo '</br></br></br></br></br></br></br></br></br>';
    echo '<div style="display: flex; justify-content: center; align-items: center; height: 100px; /* Adjust height as needed */">';
    echo '<h1>' . $message . "</h1>&emsp;";
    echo '<button onclick="history.go(-1);">' . $buttonText . '</button></h3>';
    exit();
}


function displayRentalRow($type, $duration, $calculation)
{
    echo '<tr>';
    echo "<td>$type</td>";
    echo '<td> RM ' . htmlspecialchars($calculation['first3HTotal']) . " / First 3 Hour</br> RM " . htmlspecialchars($calculation['after3HTotal']) . " / 1 Hour</td>";
    echo '<td>' . htmlspecialchars($duration) . ' Hour</td>';
    echo '<td> RM ' . htmlspecialchars($calculation['total']) . '</td>';
    echo '</tr>';
}
?>