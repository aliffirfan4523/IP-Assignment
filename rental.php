<?php
include('./includes/header.html');
include('./includes/function.php');
?>
<div class="main-div">
    <!-- Left Side: Welcome Message -->
    <div>
        <h1>Welcome to Ride Bike Rental</h1>
        <p>Experience the joy of riding a bicycle and explore the beautiful surroundings with Ride Bike Rental. Choose
            from our wide range of bicycles and enjoy a memorable ride.</p>
        <h2>Rental Price </h2>
        <table>
            <thead>
                <tr>
                    <th>Bicycle Type</th>
                    <th>Weekday Rental</th>
                    <th>Weekend Rental</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>City bicycle</td>
                    <td>RM<?php echo $cityRates['weekday'][0]; ?> / first 3 hours,</br>
                        RM<?php echo $cityRates['weekday'][1]; ?>/ hour after 3
                        hours</td>
                    <td>RM<?php echo $cityRates['weekend'][0]; ?>/ first 3 hours,</br>
                        RM<?php echo $cityRates['weekend'][0]; ?> / hour after 3 hours</td>
                </tr>
                <tr>
                    <td>Tandem bicycle</td>
                    <td>RM<?php echo $tandemRates['weekday'][0]; ?> / first 3 hours,</br>
                        RM<?php echo $tandemRates['weekday'][1]; ?>/ hour after 3
                        hours</td>
                    <td>RM<?php echo $tandemRates['weekend'][0]; ?>/ first 3 hours,</br>
                        RM<?php echo $tandemRates['weekend'][0]; ?> / hour after 3 hours</td>
                </tr>
            </tbody>
        </table>
        <h4>Please note that the rental price will reset to the first 3 hours for the next day.</h2>

    </div>

    <!-- Right Side: Form -->
    <div>
        <form method="post" action="rental_display.php">

            <p>Please Enter Your Details</p>
            <p>
                <label for="name">Name: </label>
                <input type="text" name="name" value="<?php if (isset($_POST['name']))
                    echo $_POST['name']; ?>" required>
                <span class="warning">&#9888; Please fill this input</span>
            </p>
            <p><label for="mobileNumber">Mobile Number: </label>
                <input type="text" name="mobile" min="10" max="12" value="<?php if (isset($_POST['mobile']))
                    echo $_POST['mobile']; ?>" required>
                <span class="warning">&#9888; Please fill this input</span>
            </p>
            <p>
                <label for="biketype">Bike Type: </label><br>
                <input type="checkbox" id="cityBicycle" name="city" value="city">
                <label for="cityBicycle"> City Bicycle</label><br>
                <input type="checkbox" id="tandemBicycle" name="tandem" value="tandem">
                <label for="tandemBicycle"> Tandem Bicycle</label>
            </p>
            <p><label for="date">Date: </label>
                <input type="date" id="dateRental" name="dateRental" value="<?php echo date('Y-m-d'); ?>">
            </p>
            <p>
                <label for="days">Day:</label>
                <select name="days">
                    <option value="" <?php if (empty($_POST['dateRental']))
                        echo 'selected disabled hidden'; ?>>Choose
                        here</option>
                    <?php
                    $selectedDate = isset($_POST['dateRental']) ? $_POST['dateRental'] : date('Y-m-d'); // Use posted date or default to today
                    $dayOfWeek = date('N', strtotime($selectedDate));
                    $days = [
                        1 => '1|Monday',
                        2 => '2|Tuesday',
                        3 => '3|Wednesday',
                        4 => '4|Thursday',
                        5 => '5|Friday',
                        6 => '6|Saturday',
                        7 => '7|Sunday',
                    ];
                    
                    foreach ($days as $dayNum => $dayName) {
                        $separatedDays = explode('|', $dayName);
                        $selected = ($dayOfWeek == $separatedDays[0]) ? 'selected' : '';
                        echo "<option value=\"$dayName\" $selected>{$separatedDays[1]}</option>";
                    }
                    ?>
                </select>
            </p>
            <br>
                <label for="hours">Hours:</label>
                <input type="number" name="hours" id="hours" value="3" max="24">
                <span class="warning">Please select between 3 to 24 hours</span>
            </p>
            <p>
                <button type="submit" name="submit_rental" value="Rent Now">Rent Now</button>
            </p>
        </form>
    </div>
</div>

<?php
include('./includes/footer.html');
?>