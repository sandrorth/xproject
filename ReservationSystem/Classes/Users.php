<?php

namespace Xproject\ReservationSystem\Classes;

class Users
{
    private \PDO $dbConn;

    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    public function getUsers($userIds = array())
    {
        $users = new \stdClass();
        $stmtParam = count($userIds) > 0 ? $userIds : null;
        $sql = "SELECT *
                  FROM users";

        if (count($userIds) > 0) {
            $inQuery = implode(',', array_fill(0, count($userIds), '? '));
            $sql .= " WHERE user_id IN (" . pg_escape_string($inQuery) . ")";
        }
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute($stmtParam);

        if ($stmt->rowCount() > 0) {
            while ($result = $stmt->fetchObject()) {
                $users->{$result->user_id} = new \stdClass();
                $users->{$result->user_id} = $result;
            }
        }
        return $users;
    }
}
