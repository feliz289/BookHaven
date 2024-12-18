<?php

declare(strict_types = 1);

function get_instance(string $tableName, object $pdo, string $column, string $instance_name) {
    $query = "SELECT $column FROM $tableName WHERE $column = :instane_name;";
    $stmt = $pdo->prepare($query);  //Prepared statement to query into the database
    $stmt->bindParam(":instane_name", $instance_name);    //Bind the data
    $stmt->execute();   //Execute the query to the database

    $result = $stmt->fetch(PDO::FETCH_ASSOC); //To get the data from database, fetch() only grabs one data
    return $result;
}