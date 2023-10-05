<?php
header("Access-Control-Allow-Origin: *");

$rawData = file_get_contents('php://input');
// Retrieve API key from Unity
$api_key = getenv('API_KEY');
$api_key_post = filter_input(INPUT_POST, 'api_key', FILTER_SANITIZE_STRING);

echo "Stored API Key: " . $api_key . "<br>";
echo "Received API Key: " . $api_key_post . "<br>";

if ($api_key == $api_key_post){

    // Fetch database connection details from environment variables
    $host = getenv('DB_HOST');
    $username = getenv('DB_USERNAME');
    $password = getenv('DB_PASSWORD');
    $database = getenv('DB_DATABASE');
    $port = 3306;


    // Initialize connection
    $con = mysqli_init();

    // Connect to the database
    if (!mysqli_real_connect($con, $host, $username, $password, $database, $port)) {
        echo json_encode(["status" => "error", "message" => "Connection failed: " . mysqli_connect_error()]);
        exit();
    }

    // Retrieve Action data from Unity
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($action == "create"){
        $playername = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $score = 0;

        $insert_stmt = $con->prepare("INSERT INTO demo.players (playername, score) VALUES (?, ?)");
        $insert_stmt->bind_param("si", $playername, $score); 

        if ($insert_stmt->execute()) {
            $last_id = $con->insert_id;
            echo $last_id;
        } else {
            // Catching any error during the insertion. 
            echo "Database error: " . $con->error;
        }

        $insert_stmt->close();

    } elseif ($action == "update_score"){
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $score = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_INT);

        // score was not a valid integer
        if ($score === false) {
            echo "Error processing request!";
            exit();
        }

        // Update the score only if it's higher than the current score
        $update_stmt = $con->prepare("UPDATE demo.players SET score = ? WHERE id = ? AND score < ?");
        $update_stmt->bind_param("iii", $score, $id, $score);
        $update_stmt->execute();

        // Check if any rows were affected
        if ($update_stmt->affected_rows > 0) {
            echo "Success! Score updated.";
        } else {
            echo "ID doesn't exist or the new score isn't higher!";
        }

        $update_stmt->close();
    } elseif ($action == "update_name"){
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $playername = filter_input(INPUT_POST, 'playername', FILTER_VALIDATE_STRING);

        // score was not a valid string
        if ($playername === false) {
            echo "Error processing request!";
            exit();
        }

        // Update the score only if it's higher than the current score
        $update_stmt = $con->prepare("UPDATE demo.players SET playername = ? WHERE id = ?");
        $update_stmt->bind_param("si", $playername, $id);
        $update_stmt->execute();

        // Check if any rows were affected
        if ($update_stmt->affected_rows > 0) {
            echo "Success! Name updated.";
        } else {
            echo "ID doesn't exist";
        }

        $update_stmt->close();
    }
    $con->close();
    
} else {
    echo json_encode(["status" => "error", "message" => "Invalid API key"]);
    exit();
}
?>
