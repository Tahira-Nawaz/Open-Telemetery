<html>
<body align="center">

<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$server = "tahira-server.mysql.database.azure.com";
$database = "tahira-database";
$username = "tahira";
$password = "@bajwa123456789";
$port = 3306;

// Establish MySQL connection
$con = new mysqli($server, $username, $password, $database, $port);

// Check connection
if ($con->connect_error) {
    die("❌ Connection failed: " . $con->connect_error);
} else {
    echo "✅ MySQL Connection Successful!<br>";
}

// Create table if it doesn't exist
$tableCreateQuery = "CREATE TABLE IF NOT EXISTS formas3 (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    First_name VARCHAR(100) NOT NULL,
    Email_id VARCHAR(255) NOT NULL,
    Telephone_Number VARCHAR(20) NOT NULL,
    comments TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($con->query($tableCreateQuery) === TRUE) {
    echo "✅ Table 'formas3' checked/created successfully.<br>";
} else {
    die("❌ Error creating table: " . $con->error);
}

// Validate POST data
$Fname = $_POST['First_name'] ?? '';
$F = $_POST['Email_id'] ?? '';
$E = $_POST['Telephone_Number'] ?? '';
$R = $_POST['comments'] ?? '';

// Check if form data is empty
if (empty($Fname) || empty($F) || empty($E) || empty($R)) {
    echo "⚠️ All fields are required. Please fill out the form correctly.<br>";
    exit;
}

// Insert data using prepared statement
$sql = "INSERT INTO formas3 (First_name, Email_id, Telephone_Number, comments) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssss", $Fname, $F, $E, $R);

if ($stmt->execute()) {
    echo "✅ Your response is submitted successfully!<br>";
    echo "<h2>Your Contact Information</h2>";
    echo "Name: " . htmlspecialchars($Fname) . "<br>";
    echo "Email: " . htmlspecialchars($F) . "<br>";
    echo "Telephone Number: " . htmlspecialchars($E) . "<br>";
    echo "Comments: " . htmlspecialchars($R) . "<br><br>";
    echo "<h2>Kindly Confirm your details👀</h2>Thanks...!<br>";
} else {
    echo "❌ Error inserting into table: " . $stmt->error . "<br>";
}

// Close statement and connection
$stmt->close();
$con->close();
?>

</body>
</html>
