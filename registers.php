<?php
// Database connection details
$servername = "localhost"; // Database server
$username = "root"; // Database username (default is root for local)
$password = ""; // Database password (default is empty for local)
$dbname = "skynet"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['register'])) {
    // Get user input from form
    $user_name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($user_name) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "All fields are required.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username or email already exists
        $sql_check = "SELECT * FROM registers WHERE username = ? OR email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $user_name, $email);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            echo "Username or Email already exists.";
        } else {
            // Insert new user into the database
            $sql = "INSERT INTO registers (username, password, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $user_name, $hashed_password, $email);
            if ($stmt->execute()) {
                echo "Admin registered successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
}

// Close the connection
$conn->close();
?>

