<?php

//connecting db
include "partials/_config.php";

//check id if provided in query string
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $user_id = $_GET['user_id'];

    //delete query (with user_id for security prurpose)
    $deleteSql = "DELETE FROM tasks WHERE id = '$task_id' AND user_id = '$user_id'";
    $deleteResult = mysqli_query($conn, $deleteSql);

    if ($deleteResult) {
        $_SESSION['success_msg'] = "<i class='fas fa-check-circle me-2'></i><b>Success!</b> Task deleted successfully.";
    } else {
        $_SESSION['error_msg'] = "<i class='fas fa-exclamation-circle me-2'></i><b>Error!</b> Failed to delete task.";
    }
} else {
    $_SESSION['error_msg'] = "<i class='fas fa-exclamation-circle me-2'></i>Invalid request.";
}

//redirecting to dashboard.php
header("location: dashboard.php");


?>