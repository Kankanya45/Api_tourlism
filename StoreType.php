<?php
header('Access-Control-Allow-Origin: *');
include "conn.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'delete' parameter is set in the request
    if (isset($_POST['delete'])) {
        // Get the id_type to be deleted
        $id_type_to_delete = isset($_POST['id_type']) ? $_POST['id_type'] : '';

        // Check if the record with the given id_type exists in the database
        $sqlCheckDelete = "SELECT * FROM storetype WHERE id_type = '$id_type_to_delete'";
        $resultCheckDelete = mysqli_query($conn, $sqlCheckDelete);

        if (mysqli_num_rows($resultCheckDelete) > 0) {
            // If the record exists, delete it
            $sqlDelete = "DELETE FROM storetype WHERE id_type='$id_type_to_delete'";
            $resultDelete = mysqli_query($conn, $sqlDelete);

            if ($resultDelete) {
                // Send success response code
                http_response_code(200);
                echo json_encode(array("message" => "Data deleted successfully"));
            } else {
                // Send error response code
                http_response_code(500);
                echo json_encode(array("message" => "Error deleting data from the database"));
            }
        } else {
            // Send error response code as the record doesn't exist
            http_response_code(404);
            echo json_encode(array("message" => "Record not found for deletion"));
        }
    } else {
        // Get data from the request
        $id_type = isset($_POST['id_type']) ? $_POST['id_type'] : '';
        $name_type = isset($_POST['name_type']) ? $_POST['name_type'] : '';

        // Check if the record with the given id_type already exists in the database
        $sqlCheck = "SELECT * FROM storetype WHERE id_type = '$id_type'";
        $resultCheck = mysqli_query($conn, $sqlCheck);

        if (mysqli_num_rows($resultCheck) > 0) {
            // If the record exists, update it
            $sqlUpdate = "UPDATE storetype SET name_type='$name_type'
                          WHERE id_type='$id_type'";
            $resultUpdate = mysqli_query($conn, $sqlUpdate);

            if ($resultUpdate) {
                // Send success response code
                http_response_code(200);
                echo json_encode(array("message" => "Data updated successfully"));
            } else {
                // Send error response code
                http_response_code(500);
                echo json_encode(array("message" => "Error updating data in the database"));
            }
        } else {
            // If the record doesn't exist, insert a new one
            $sqlInsert = "INSERT INTO storetype (id_type, name_type)
                          VALUES ('$id_type', '$name_type')";
            $resultInsert = mysqli_query($conn, $sqlInsert);

            if ($resultInsert) {
                // Send success response code
                http_response_code(200);
                echo json_encode(array("message" => "Data inserted successfully"));
            } else {
                // Send error response code
                http_response_code(500);
                echo json_encode(array("message" => "Error inserting data into the database"));
            }
        }
    }
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("message" => "Invalid Request Method"));
}

// Close the database connection
mysqli_close($conn);
