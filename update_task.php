<?php

//connecting db
include "partials/_config.php";

// update data
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $status = $_POST['status'];
    //update query
    $updateSql = "UPDATE tasks SET task = '$task', status = '$status' WHERE id = '$id'";
    $updateResult = mysqli_query($conn, $updateSql);

    if ($updateResult) {
        session_start();
        $_SESSION['message'] = "<i class='fas fa-check-circle me-2'></i><b>Successfully!</b> Task updated successfully";
    } else {
        $_SESSION['message'] = "<i class='fas fa-exclamation-circle me-2'></i><b>Error!</b> Failed to update task";
    }

    //redirect to dashboard.php
    header("location: dashboard.php");
    exit;
}

?>