<?php
header('Access-Control-Allow-Origin: *');
include "conn.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'delete' parameter is set in the request
    if (isset($_POST['delete'])) {
        // Get the id_store to be deleted
        $id_store_to_delete = isset($_POST['id_store']) ? $_POST['id_store'] : '';

        // Check if the record with the given id_store exists in the database
        $sqlCheckDelete = "SELECT * FROM storename WHERE id_store = '$id_store_to_delete'";
        $resultCheckDelete = mysqli_query($conn, $sqlCheckDelete);

        if (mysqli_num_rows($resultCheckDelete) > 0) {
            // If the record exists, delete it
            $sqlDelete = "DELETE FROM storename WHERE id_store='$id_store_to_delete'";
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
        $id_store = isset($_POST['id_store']) ? $_POST['id_store'] : '';
        $name_store = isset($_POST['name_store']) ? $_POST['name_store'] : '';
        $id_type = isset($_POST['id_type']) ? $_POST['id_type'] : '';

        // Check if the record with the given id_store already exists in the database
        $sqlCheck = "SELECT * FROM storename WHERE id_store = '$id_store'";
        $resultCheck = mysqli_query($conn, $sqlCheck);

        if (mysqli_num_rows($resultCheck) > 0) {
            // If the record exists, update it
            $sqlUpdate = "UPDATE storename SET name_store='$name_store', id_type='$id_type'
                          WHERE id_store='$id_store'";
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
            $sqlInsert = "INSERT INTO storename (id_store, name_store, id_type)
                          VALUES ('$id_store', '$name_store', '$id_type')";
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
