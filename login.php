<?php
// Database connection details
$servername = "localhost";
$username = "root";  // Default MySQL username
$password = "";      // Default MySQL password
$dbname = "skynet";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // SQL query to check user credentials
    $sql = "SELECT * FROM registers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Check if the password matches the hashed password stored in the database
        if (password_verify($pass, $row['password'])) {
            // Password is correct, start session
            session_start();
            $_SESSION['username'] = $user;
            header("Location: index.html");
        } else {
            // Invalid credentials
            echo "<script>alert('Invalid Username or Password!');</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Invalid Username or Password!');</script>";
    }
}

$conn->close();
?>

