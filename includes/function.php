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

function getFullDaysAndRemainingHours($totalHours)
{
    $fullDays = floor($totalHours / 24);
    $remainingHours = $totalHours % 24;
    return [$fullDays, $remainingHours];
}


function countWeekdaysAndWeekends($rentalDate, $fullDays)
{
    $weekdayTotal = 0;
    $weekendTotal = 0;

    for ($x = 0; $x < $fullDays; $x++) {
        $date = date('Y-m-d H:i:s', strtotime($rentalDate . " +$x day"));
        isWeekday($date) ? $weekdayTotal++ : $weekendTotal++;
    }

    return [$weekdayTotal, $weekendTotal];
}

function calculateRentalAmountByDay($weekdayDays, $weekendDays, $hours, $weekdayRate, $weekendRate, $rentalDate, $fullDays)
{
    $weekdayFirst3HTotal = 0;
    $weekdayAfter3HTotal = 0;
    $weekendFirst3HTotal = 0;
    $weekendAfter3HTotal = 0;

    // Weekday days
    if (!empty($weekdayDays)) {
        for ($i = 0; $i < $weekdayDays; $i++) {
            $weekdayFirst3HTotal += 3 * $weekdayRate[0];
            $weekdayAfter3HTotal += 21 * $weekdayRate[1]; // remaining of 24-hour day
        }
    }

    // Weekend days
    if (!empty($weekendDays)) {
        for ($i = 0; $i < $weekendDays; $i++) {
            $weekendFirst3HTotal += 3 * $weekendRate[0];
            $weekendAfter3HTotal += 21 * $weekendRate[1];
        }
    }

    // Remaining hours (partial day)
    $partialDate = date('Y-m-d H:i:s', strtotime($rentalDate . " +$fullDays day"));

    if (isWeekday($partialDate)) {
        if ($hours <= 3) {
            $weekdayFirst3HTotal += $hours * $weekdayRate[0];
        } else {
            $weekdayFirst3HTotal += 3 * $weekdayRate[0];
            $weekdayAfter3HTotal += ($hours - 3) * $weekdayRate[1];
        }
    } else {
        if ($hours <= 3) {
            $weekendFirst3HTotal += $hours * $weekendRate[0];
        } else {
            $weekendFirst3HTotal += 3 * $weekendRate[0];
            $weekendAfter3HTotal += ($hours - 3) * $weekendRate[1];
        }
    }


    // Final totals
    $total = $weekdayFirst3HTotal + $weekdayAfter3HTotal + $weekendFirst3HTotal + $weekendAfter3HTotal;

    return [
        'total' => $total,
        'weekdayFirst3HTotal' => $weekdayFirst3HTotal,
        'weekdayAfter3HTotal' => $weekdayAfter3HTotal,
        'weekendFirst3HTotal' => $weekendFirst3HTotal,
        'weekendAfter3HTotal' => $weekendAfter3HTotal,
    ];
}


function displayRentalRow($type, $duration, $calculation)
{
    echo '<tr>';
    echo "<td>$type</td>";
    echo '<td> RM ' . htmlspecialchars($calculation['weekdayFirst3HTotal']) . " / First 3 Hour</br> RM " . htmlspecialchars($calculation['weekdayAfter3HTotal']) . " / 1 Hour</td>";
    echo '<td> RM ' . htmlspecialchars($calculation['weekendFirst3HTotal']) . " / First 3 Hour</br> RM " . htmlspecialchars($calculation['weekendAfter3HTotal']) . " / 1 Hour</td>";
    echo '<td>' . htmlspecialchars($duration) . ' Hour</td>';
    echo '<td> RM ' . htmlspecialchars($calculation['total']) . '</td>';
    echo '</tr>';
}
?>