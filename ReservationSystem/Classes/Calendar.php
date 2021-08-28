<?php

namespace Xproject\ReservationSystem\Classes;

class Calendar
{
    private \PDO $dbConn;

    public \stdClass $struct;

    public function __construct($pdo)
    {
        $this->dbConn = $pdo;
    }

    public function createCalendarStruct(\DateTime $dateTime)
    {
        $date = $dateTime->format('Y-m');
        $fstDOWInMonth = date('w', strtotime($date));
        $numberOfDays = date('t', strtotime($date));
        $numberOfLines = (int) ceil(($fstDOWInMonth + $numberOfDays) / 7);
        $firstCalendarDay = date('Y-m-d', strtotime($fstDOWInMonth * -1 . ' days', strtotime($date)));
        $lastCalendarDay = date('Y-m-d', strtotime(
            ((($fstDOWInMonth * -1) + ($numberOfLines * 7) - 1)) . ' days',
            strtotime($date)
        ));
        $this->struct = new \stdClass();
        $this->struct->firstDOWMonth = $fstDOWInMonth;
        $this->struct->numberOfDays = $numberOfDays;
        $this->struct->numberOfLines = $numberOfLines;
        $this->struct->firstCalendarDay = $firstCalendarDay;
        $this->struct->lastCalendarDay = $lastCalendarDay;

        return $this->struct;
    }

    public static function generateDateInterval($startDate, $endDate, $id, $step = '+1 day', $format = 'Y-m-d'): \stdClass
    {
        $starting = strtotime($startDate);
        $ending = strtotime($endDate);
        $dates = new \stdClass();

        while ($starting <= $ending) {
            $key = date($id, $starting);
            $dates->$key = new \stdClass();
            $dates->$key->value = date($format, $starting);
            $starting = strtotime($step, $starting);
        }
        return $dates;
    }
}
