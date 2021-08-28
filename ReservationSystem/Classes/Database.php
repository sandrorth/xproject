<?php

namespace Xproject\ReservationSystem\Classes;

class Database
{
    public \PDO $dbConn;
    public static \stdClass $dates;

    public function __construct()
    {
        try {
            $dsn = "pgsql:host=localhost;port=5432;dbname=reservation_system;";
            $user = 'postgres';
            $pass = 'postgres';
            $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            $this->dbConn = $pdo;
        } catch (\PDOException $exception) {
            echo "ERROR: " . $exception->getMessage();
            exit;
        }
    }

    public static function createDateTimeWithTimezone($date, $time, $timezone): \DateTime
    {
        $dateTime = new \DateTime($date . ' ' . $time);
        $dateTime->setTimezone(new \DateTimeZone($timezone));

        return $dateTime;
    }

    /**
     * Replaces any parameter placeholders in a query with the value of that
     * parameter. Useful for debugging. Assumes anonymous parameters from
     * $params are are in the same order as specified in $query
     *
     * @param string $query The sql query with parameter placeholders
     * @param array $params The array of substitution parameters
     * @return string The interpolated query
     */
    public static function interpolateQuery($query, $params)
    {
        $keys = array();

        # build a regular expression for each parameter
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if (is_string($key)) {
                    $keys[] = '/:' . $key . '/';
                } else {
                    $keys[] = '/[?]/';
                }
            }
        }


        $query = preg_replace($keys, $params, $query, 1, $count);
        return $query;
    }
}
