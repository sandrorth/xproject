<?php

namespace Xproject\ReservationSystem\Forms;

use Xproject\ReservationSystem\Classes\Vehicles;
use Xproject\ReservationSystem\Classes\Reservations;
use Xproject\ReservationSystem\Classes\Database;

require_once realpath('./../Config/Config.php');
require_once realpath('./../../vendor/autoload.php');

$db = new Database();
$reservations = new Reservations($db->dbConn);
$vehicles = new Vehicles($db->dbConn);
$allVehicles = $vehicles->getVehicles();

if (isset($_POST) && count($_POST) >= 5) {
    $vehicleId = pg_escape_string($_POST['vehicle']);
    $startDate = pg_escape_string($_POST['startDate']);
    $startTime = pg_escape_string($_POST['startTime']);
    $endDate = pg_escape_string($_POST['endDate']);
    $endTime = pg_escape_string($_POST['endTime']);

    $success = $reservations->scheduleReservation(1, $vehicleId, $startDate, $startTime, $endDate, $endTime);

    if ($success) {
//        TODO implement logic.
    }
}
?>

<form method="post">
    Vehicle:
    <br>
    <label>
        <select name="vehicle">
            <?php foreach ($allVehicles as $id => $info) : ?>
            <option value="<?php echo $id?>">
                <?php
                    echo $info->color . ' ' . $info->model . ' - License Plate: ' . $info->licensePlate;
            endforeach;
            ?>
            </option>
        </select>
    </label>
    <br>
    Desired reservation start date:
    <br>
    <label>
        <input type="date" name="startDate">
    </label>
    <br>
    <br>
    Starting at:
    <br>
    <label>
        <input type="time" name="startTime" value="08:00" step="1800" min="08:00AM" max="20:00">
    </label>
    <br>
    <small>Reservation times go from 8am to 8pm</small>
    <br>
    <br>
    Desired reservation end date:
    <br>
    <label>
        <input type="date" name="endDate">
    </label>
    <br>
    <br>
    Ending at:
    <br>
    <label>
        <input type="time" name="endTime" value="20:00" step="1800" min="08:00AM" max="20:00">
    </label>
    <br>
    <small>Reservation times go from 8am to 8pm</small>
    <br>
    <br>
    <input type="submit" value="Schedule reservation" formtarget="_blank">
</form>
