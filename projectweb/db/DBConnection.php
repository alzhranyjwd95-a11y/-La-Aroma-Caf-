<?php
function getConnection()
{
    try {
        $conn = new PDO('sqlite:db/myDB1.db');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch (PDOException $e) {
        die("Connection Failed" . $e->getMessage());
    }
}