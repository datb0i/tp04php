<?php
// // PHP Data Objects(PDO) Sample Code:
// try {
//     $conn = new PDO("sqlsrv:server = tcp:fit5120demo.database.windows.net,1433; Database = sampledb", "sqladmin", "Fit5120*");
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch (PDOException $e) {
//     print("Error connecting to SQL Server.");
//     die(print_r($e));
// }

// // SQL Server Extension Sample Code:
// $connectionInfo = array("UID" => "sqladmin", "pwd" => "Fit5120*", "Database" => "sampledb", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
// $serverName = "tcp:fit5120demo.database.windows.net,1433";
// $conn = sqlsrv_connect($serverName, $connectionInfo);

$con = mysqli_init();
mysqli_real_connect($con, "fit5120demo2.mysql.database.azure.com", "sqladmin", "Fit5120*", "demo", 3306);
$garbarge_stats = "SELECT * FROM demo.annual_totals";
$check = mysqli_query($con, $garbarge_stats) or die("2: query failed");

// Create an array to store all the rows
$rows = array();

// Loop through each row and fetch the data
while ($row = mysqli_fetch_assoc($check)) {
// Add each row to the array
$rows[] = $row;
}

// Check if any rows were found
if (count($rows) > 0) {
// Create an associative array to store the result
$result = array("status" => "success", "data" => $rows);
} else {
// No rows found, still return an empty data array
$result = array("status" => "error", "message" => "No data found in the table.", "data" => $rows);
}

// Convert the result array to JSON and echo it
echo json_encode($result);

?>
