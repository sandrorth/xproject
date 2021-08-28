<?php

namespace Xproject\ReservationSystem\Config;

try {
    $dsn = "pgsql:host=localhost;port=5432;dbname=reservation_system;";
    $user = 'postgres';
    $pass = 'postgres';
    $pdo = new \PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
} catch (\PDOException $exception) {
    echo "ERROR: " . $exception->getMessage();
    exit;
}
