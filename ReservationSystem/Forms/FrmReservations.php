<?php

namespace Xproject\ReservationSystem\Forms;

use DateTime;
use Xproject\ReservationSystem\Classes\Calendar;
use Xproject\ReservationSystem\Classes\Database;
use Xproject\ReservationSystem\Classes\Reservations;
use Xproject\ReservationSystem\Classes\Users;
use Xproject\ReservationSystem\Classes\Vehicles;

require_once realpath('./../Config/Config.php');
require_once realpath('./../../vendor/autoload.php');

$db = new Database();
$reservations = new Reservations($db->dbConn);
$users = new Users($db->dbConn);
$vehicles = new Vehicles($db->dbConn);
$dateTimeSel = new DateTime();
$firstDate = $dateTimeSel->format('Y-m') . '-01';
$lastDate = date('Y-m-d', strtotime('+ 6 months', strtotime($firstDate)));
$selMonths = Calendar::generateDateInterval('2021-01-01', '2021-12-01', 'm', '+1 month', 'F');
$lastDate = date('Y-m-d', strtotime('+ 5 years', strtotime($firstDate)));
$selYears = Calendar::generateDateInterval($firstDate, $lastDate, 'Y', '+ 1 year', 'Y');

if (isset($_POST) && !empty($_POST['month']) && !empty($_POST['year'])) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $dateTimeObj = new DateTime("$year-$month");
    $searchDate = $dateTimeObj->format('Y-m');
    $scheduledReservations = $reservations->getReservations($searchDate);
    $vehicleIds = array();
    $userIds = array();

    if (!empty($scheduledReservations)) {
        foreach ($scheduledReservations as $scheduledReservation) {
            $vehicleIds[] = $scheduledReservation->vehicle_id;
            $userIds[] = $scheduledReservation->user_id;
        }

        $scheduledUsers = $users->getUsers($userIds);
        unset($userIds);
        $scheduledVehicles = $vehicles->getVehicles($vehicleIds);
        unset($vehicleIds);
    }
}
?>

<h4>Select the desired period to search for scheduled reservations:</h4>
<form name="searchPeriod" method="post">
    <select name="month">
        <?php
        foreach ($selMonths as $key => $selMonth) : ?>
            <option value="<?php
            echo $key; ?>"
                <?php
                if (!empty($_POST['month']) && $_POST['month'] == $key) {
                    echo 'selected';
                } ?>
            ><?php
                echo $selMonth->value; ?></option>
            <?php
        endforeach; ?>
    </select>
    <label>
        <select name="year">
            <?php
            foreach ($selYears as $key => $selYear) : ?>
                <option value="<?php
                echo $key; ?>"
                    <?php
                    if (!empty($_POST['year']) && $_POST['year'] == $key) {
                        echo 'selected';
                    } ?>
                ><?php
                    echo $selYear->value; ?></option>
                <?php
            endforeach; ?>
        </select>
    </label>
    <input type="submit" value="Search">
</form>


<?php
if (!empty($scheduledReservations)) { ?>
    <div align="center">
        <h1>Scheduled reservations</h1>
    </div>
<table id="scheduledReservations" align="center" border="1">
    <thead>
    <tr>
        <td>Vehicle:</td>
        <td>Reserved by:</td>
        <td>Reserved from:</td>
        <td>Reserved until:</td>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($scheduledReservations as $reservation) : ?>
        <tr>
            <td>
                <?php
                $vehicle = $scheduledVehicles->{$reservation->vehicle_id};
                echo $vehicle->color . ' '
                    . $vehicle->vehicle_name . ' License plate ' . $vehicle->license_plate;
                ?>
            </td>
            <td>
                <?php
                $user = $scheduledUsers->{$reservation->user_id};
                echo $user->user_name;
                ?>
            </td>
            <td>
                <?php
                $dateTime = new DateTime($reservation->start_datetime);
                $startDateTime = $dateTime->format('d/M/Y H:i:');
                echo $startDateTime;
                ?>
            </td>
            <td>
                <?php
                $dateTime = new DateTime($reservation->end_datetime);
                $endDateTime = $dateTime->format('d/M/Y H:i');
                echo $endDateTime;
                ?>
            </td>
        </tr>
        <?php
    endforeach; ?>
    <?php
} else { ?>
        <div align="center">
            <h3>No reservations found</h3>
        </div>
    <?php
} ?>
    </tbody>
</table>

<hr>

<?php
$calDateTime = new DateTime('now');
if (isset($_POST) && !empty($_POST['month']) && !empty($_POST['year'])) {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $calDateTime->setTimestamp(strtotime("$year-$month"));
}

$calendar = new Calendar($db->dbConn);
$struct = $calendar->createCalendarStruct($calDateTime);
?>

<div align="center">
    <h3>
        <?php
        echo date('F Y', strtotime($calDateTime->format('Y-m'))) ?>
    </h3>
    <table border="1px">
        <thead>
        <tr>
            <td>Sunday</td>
            <td>Monday</td>
            <td>Tuesday</td>
            <td>Wednesday</td>
            <td>Thursday</td>
            <td>Friday</td>
            <td>Saturday</td>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($line = 0; $line < $struct->numberOfLines; $line++) : ?>
            <tr>
                <?php
                for ($dow = 0; $dow < 7; $dow++) : ?>
                    <?php
                    $date = date(
                        'Y-m-d',
                        strtotime(
                            ($dow + ($line * 7)) . ' days',
                            strtotime($struct->firstCalendarDay)
                        )
                    );
//                    $dtPartYear = explode('-', $date)[0];
                    $dtPartMonth = explode('-', $date)[1];
                    $dtPartDay = explode('-', $date)[2];
                    ?>
                    <td><font color="<?php
                    if ($month != $dtPartMonth) {
                        echo '#696969';
                    } ?>">
                            <?php
                            echo $dtPartDay; ?>
                        </font>
                    </td>
                    <?php
                endfor; ?>
            </tr>
            <?php
        endfor; ?>
        </tbody>
    </table>
</div>

