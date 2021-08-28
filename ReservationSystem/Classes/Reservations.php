<?php

namespace Xproject\ReservationSystem\Classes;

use PDO;

class Reservations
{

    private PDO $dbConn;

    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getReservations($period = null)
    {
        $param = null;
        $sql = "SELECT *
                  FROM reservations";

        if (!empty($period)) {
            $period = pg_escape_string($period);
            $sql .= " WHERE (TO_CHAR(start_datetime,'YYYY-MM') = ?
                             OR TO_CHAR(end_datetime, 'YYYY-MM') = ?)";
            $param[] = $period;
            $param[] = $period;
        }
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute($param);

        if ($stmt->rowCount() > 0) {
            return (object) $stmt->fetchAll(PDO::FETCH_CLASS);
        }
        return false;
    }

    public function scheduleReservation($userId, $vehicleId, $startDate, $startTime, $endDate, $endTime)
    {
        $dateTime = Database::createDateTimeWithTimezone($startDate, $startTime, 'America/Sao_paulo');
        $startDateTime = $dateTime->format('Y-m-d H:i:sP');

        $dateTime =  Database::createDateTimeWithTimezone($endDate, $endTime, 'America/Sao_paulo');
        $endDateTime = $dateTime->format('Y-m-d H:i:sP');

        if ($this->isAvailable($vehicleId, $startDateTime, $endDateTime)) {
            $sql = "INSERT INTO reservations
                               (user_id, vehicle_id, start_datetime, end_datetime)
                        VALUES (:userId, :vehicleId, :startDateTime, :endDateTime)";
            $stmt = $this->dbConn->prepare($sql);
            $stmt->bindValue(':userId', $userId);
            $stmt->bindValue(':vehicleId', $vehicleId);
            $stmt->bindValue(':startDateTime', $startDateTime);
            $stmt->bindValue(':endDateTime', $endDateTime);

            return $stmt->execute();
        }
        return false;
    }

    private function isAvailable($vehicleId, $startDateTime, $endDateTime): bool
    {
        $sql = "SELECT *
                  FROM reservations
                 WHERE vehicle_id = :vehicleId
                   AND NOT ((start_datetime, end_datetime) OVERLAPS (:startDatetime, :endDatetime));";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindValue(':vehicleId', $vehicleId);
        $stmt->bindValue(':startDatetime', $startDateTime);
        $stmt->bindValue(':endDatetime', $endDateTime);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return false;
        }
        return true;
    }
}
