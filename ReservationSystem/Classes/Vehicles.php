<?php

namespace Xproject\ReservationSystem\Classes;

class Vehicles
{
    private \PDO $dbConn;
    public \stdClass $vehicles;

    public function __construct(\PDO $dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getVehicles($vehicleIds = array()): \stdClass
    {
        $vehicles = new \stdClass();
        $stmtParam = count($vehicleIds) > 0 ? $vehicleIds : null;
        $sql = "SELECT * FROM vehicles";

        if (count($vehicleIds) > 0) {
            $inQuery = implode(',', array_fill(0, count($vehicleIds), '? '));
            $sql .= " WHERE vehicle_id IN (" . pg_escape_string($inQuery) . ")";
        }
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute($stmtParam);

        if ($stmt->rowCount() > 0) {
            while ($result = $stmt->fetchObject()) {
                $vehicles->{$result->vehicle_id} = new \stdClass();
                $vehicles->{$result->vehicle_id} = $result;
            }
        }
        return $vehicles;
    }

    public function getVehicleAvailableSchedules($vehicleId)
    {
//        TODO Implement function.
    }
}
