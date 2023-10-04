<?php
// Fetch database connection details from environment variables
$host = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');
$port = 3306;

// Create a new connection
$con = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($con->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $con->connect_error]);
    exit();
}

// Select data from 'demo.annual_wastes'
$query_wastes = "SELECT * FROM demo.annual_wastes";
$result_wastes = $con->query($query_wastes);
$rows_wastes = [];
while ($row = $result_wastes->fetch_assoc()) {
    $rows_wastes[] = $row;
}

// Select data from 'demo.annual_co2'
$query_co2 = "SELECT * FROM demo.annual_co2";
$result_co2 = $con->query($query_co2);
$rows_co2 = [];
while ($row = $result_co2->fetch_assoc()) {
    $rows_co2[] = $row;
}

// Select data from 'demo.score'
$query_score = "SELECT * FROM demo.score ORDER BY score DESC";
$result_score = $con->query($query_score);
$rows_score = [];
while ($row = $result_score->fetch_assoc()) {
    $rows_score[] = $row;
}

// Prepare the response
$response = [
    "status" => "success",
    "annual_wastes" => $rows_wastes,
    "annual_co2" => $rows_co2,
    "score" => $rows_score
];

// Output the JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
$con->close();
?>
