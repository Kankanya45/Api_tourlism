<?php
header('Access-Control-Allow-Origin: *');
include "conn.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'delete' parameter is set in the request
    if (isset($_POST['delete'])) {
        // Get the numbers to be deleted
        $numbers_to_delete = isset($_POST['numbers']) ? $_POST['numbers'] : '';

        // Use prepared statement to delete
        $sqlDelete = "DELETE FROM tramschedule WHERE numbers = ?";
        $stmtDelete = mysqli_prepare($conn, $sqlDelete);
        mysqli_stmt_bind_param($stmtDelete, "s", $numbers_to_delete);

        if (mysqli_stmt_execute($stmtDelete)) {
            // Send success response code
            http_response_code(200);
            echo json_encode(array("message" => "Data deleted successfully"));
        } else {
            // Send error response code
            http_response_code(500);
            echo json_encode(array("message" => "Error deleting data from the database"));
        }

        mysqli_stmt_close($stmtDelete);
    } else {
        // Get data from the request
        $numbers = isset($_POST['numbers']) ? $_POST['numbers'] : '';
        $time_s = isset($_POST['time_s']) ? $_POST['time_s'] : '';
        $place_code = isset($_POST['place_code']) ? $_POST['place_code'] : '';

        // Use prepared statement to check if the record exists
        $sqlCheck = "SELECT * FROM tramschedule WHERE numbers = ?";
        $stmtCheck = mysqli_prepare($conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $numbers);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            // If the record exists, update it
            $sqlUpdate = "UPDATE tramschedule SET time_s=?, place_code=?
                          WHERE numbers=?";
            $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "ss", $time_s, $place_code, $numbers);

            if (mysqli_stmt_execute($stmtUpdate)) {
                // Send success response code
                http_response_code(200);
                echo json_encode(array("message" => "Data updated successfully"));
            } else {
                // Send error response code
                http_response_code(500);
                echo json_encode(array("message" => "Error updating data in the database"));
            }

            mysqli_stmt_close($stmtUpdate);
        } else {
            // If the record doesn't exist, insert a new one
            $sqlInsert = "INSERT INTO tramschedule (numbers, time_s, place_code)
                          VALUES (?, ?, ?)";
            $stmtInsert = mysqli_prepare($conn, $sqlInsert);
            mysqli_stmt_bind_param($stmtInsert, "sss", $numbers, $time_s, $place_code);

            if (mysqli_stmt_execute($stmtInsert)) {
                // Send success response code
                http_response_code(200);
                echo json_encode(array("message" => "Data inserted successfully"));
            } else {
                // Send error response code
                http_response_code(500);
                echo json_encode(array("message" => "Error inserting data into the database"));
            }

            mysqli_stmt_close($stmtInsert);
        }

        mysqli_stmt_close($stmtCheck);
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Invalid Request Method"));
}

// Close the database connection
mysqli_close($conn);
