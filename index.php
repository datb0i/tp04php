<?php
// Fetch database connection details from environment variables
$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');
$port = 3306;

// Initialize connection
$con = mysqli_init();

// Connect to the database
if (!mysqli_real_connect($con, $host, $username, $password, $database, $port)) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . mysqli_connect_error()]);
    exit();
}

// Select data from 'demo.annual_wastes'
$query_wastes = "SELECT * FROM demo.annual_wastes";
$result_wastes = mysqli_query($con, $query_wastes);
$rows_wastes = [];
while ($row = mysqli_fetch_assoc($result_wastes)) {
    $rows_wastes[] = $row;
}

// Select data from 'demo.annual_co2'
$query_co2 = "SELECT * FROM demo.annual_co2";
$result_co2 = mysqli_query($con, $query_co2);
$rows_co2 = [];
while ($row = mysqli_fetch_assoc($result_co2)) {
    $rows_co2[] = $row;
}

// Prepare the response
$response = [
    "status" => "success",
    "annual_wastes" => $rows_wastes,
    "annual_co2" => $rows_co2
];

// Output the JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
mysqli_close($con);

?>
