<?php
header('Access-Control-Allow-Origin: *');
include "conn.php";

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'delete' parameter is set in the request
    if (isset($_POST['delete'])) {
        // Get the place_code to be deleted
        $place_code_to_delete = isset($_POST['place_code']) ? $_POST['place_code'] : '';

        // Check if the record with the given place_code exists in the database
        $sqlCheckDelete = "SELECT * FROM locationtravel WHERE place_code = '$place_code_to_delete'";
        $resultCheckDelete = mysqli_query($conn, $sqlCheckDelete);

        if (mysqli_num_rows($resultCheckDelete) > 0) {
            // If the record exists, delete it
            $sqlDelete = "DELETE FROM locationtravel WHERE place_code='$place_code_to_delete'";
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
        $place_code = isset($_POST['place_code']) ? $_POST['place_code'] : '';
        $location_name = isset($_POST['location_name']) ? $_POST['location_name'] : '';
        $details = isset($_POST['details']) ? $_POST['details'] : '';
        $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : '';
        $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : '';

        // Check if the record with the given place_code already exists in the database
        $sqlCheck = "SELECT * FROM locationtravel WHERE place_code = '$place_code'";
        $resultCheck = mysqli_query($conn, $sqlCheck);

        if (mysqli_num_rows($resultCheck) > 0) {
            // If the record exists, update it
            $sqlUpdate = "UPDATE locationtravel SET location_name='$location_name', details='$details',
                          latitude='$latitude', longitude='$longitude'
                          WHERE place_code='$place_code'";
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
            $sqlInsert = "INSERT INTO locationtravel (place_code, location_name, details, latitude, longitude)
                          VALUES ('$place_code', '$location_name', '$details', '$latitude', '$longitude')";
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
