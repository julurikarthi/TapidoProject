<?php
$filename = 'countries.json';

// Check if the file exists
if (file_exists($filename)) {
    // Read the contents of the file
    $data = file_get_contents($filename);

    // Decode the JSON data
    $decodedData = json_decode($data, true);
    $response = [
        "status" => "success",
        "data" => ["countries" => $decodedData]
    ];
    // Output the data (you can perform further processing here)
    echo json_encode($response);
} else {
    echo 'File not found.';
}
return;

?>