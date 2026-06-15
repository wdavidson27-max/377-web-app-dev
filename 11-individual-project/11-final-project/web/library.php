<?php

function get_connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $dbname = "poker";

    $connection = new mysqli($servername, $username, $password);
    if ($connection->connect_error)
    {
        die("Connection failed: " . $connection->connect_error);
    }

    $connection->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
    $connection->select_db($dbname);
    $connection->query(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            total_winnings INT NOT NULL DEFAULT 0,
            total_losses INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );
    add_column_if_missing($connection, 'users', 'total_winnings', 'INT NOT NULL DEFAULT 0');
    add_column_if_missing($connection, 'users', 'total_losses', 'INT NOT NULL DEFAULT 0');

    return $connection;
}

function add_column_if_missing($connection, $tableName, $columnName, $columnDefinition)
{
    $result = $connection->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");

    if ($result->num_rows === 0) {
        $connection->query("ALTER TABLE `$tableName` ADD COLUMN `$columnName` $columnDefinition");
    }
}
