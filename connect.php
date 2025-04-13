<?php
// Database connection settings
$servername = "localhost"; // Your MySQL server (e.g., localhost)
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "skynet"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if required form fields are set
if (!isset($_POST['date'], $_POST['time'], $_POST['day'], $_POST['services'], $_POST['quantity'], $_POST['price'])) {
    die("Missing required form fields.");
}

// Sanitize and validate the input values
$date = mysqli_real_escape_string($conn, $_POST['date']);
$time = mysqli_real_escape_string($conn, $_POST['time']);
$day = mysqli_real_escape_string($conn, $_POST['day']);
$service = mysqli_real_escape_string($conn, $_POST['services']);
$quantity = (int)$_POST['quantity'];  // Cast to integer
$price = (float)$_POST['price'];      // Cast to float
$total = $quantity * $price;          // Calculate total price

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO sales (date, time, day, service, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?, ?)");

// Check if preparation was successful
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters
$stmt->bind_param("ssssiid", $date, $time, $day, $service, $quantity, $price, $total);  // Use "d" for float (decimal)

// Execute the query
if ($stmt->execute()) {
    echo "Data inserted successfully.";
} else {
    echo "Error executing query: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
