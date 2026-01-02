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


// Set SSL certificate path
$ssl_ca_path = "DigiCertGlobalRootCA.crt.pem";

// Initialize the MySQL connection
$con = mysqli_init();

// Set SSL options
mysqli_ssl_set($con, NULL, NULL, $ssl_ca_path, NULL, NULL);

// Try to connect using SSL
if (!mysqli_real_connect($con, $server, $username, $password, $database, 3306, NULL, MYSQLI_CLIENT_SSL)) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connection successful.<br>";
}

// ----- Add table check here -----
$table_check = $con->query("SHOW TABLES LIKE 'formas3'");
if ($table_check->num_rows == 0) {
    $create_table_sql = "
    CREATE TABLE formas3 (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        First_name VARCHAR(100) NOT NULL,
        Email_id VARCHAR(100) NOT NULL,
        Telephone_Number VARCHAR(50) NOT NULL,
        comments TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
    ";
    if ($con->query($create_table_sql) === TRUE) {
        echo "Table 'formas3' created successfully.<br>";
    } else {
        die("Error creating table: " . $con->error);
    }
}
// -------------------------------

// Validate POST data
$Fname = isset($_POST['First_name']) ? mysqli_real_escape_string($con, $_POST['First_name']) : '';
$F = isset($_POST['Email_id']) ? mysqli_real_escape_string($con, $_POST['Email_id']) : '';
$E = isset($_POST['Telephone_Number']) ? mysqli_real_escape_string($con, $_POST['Telephone_Number']) : '';
$R = isset($_POST['comments']) ? mysqli_real_escape_string($con, $_POST['comments']) : '';

// Check if form data is empty
if (empty($Fname) || empty($F) || empty($E) || empty($R)) {
    echo "All fields are required. Please fill out the form correctly.<br>";
    exit;
}

// Output the received values
echo "Your response is submitted successfully😀😀😀.<br>";
echo "<h2>Your Contact Information</h2>";
echo "Name: " . $Fname . "<br>";
echo "Email: " . $F . "<br>";
echo "Telephone Number: " . $E . "<br>";
echo "Comments: " . $R . "<br><br>";

// Insert data into the database using prepared statements
$sql = "INSERT INTO formas3 (First_name, Email_id, Telephone_Number, comments) VALUES (?, ?, ?, ?)";
$stmt = $con->prepare($sql);

if ($stmt === false) {
    echo "Error preparing statement: " . $con->error . "<br>";
    exit;
}

$stmt->bind_param("ssss", $Fname, $F, $E, $R);

if ($stmt->execute()) {
    echo "<h2>Kindly Confirm your details👀</h2>Thanks...!<br>";
} else {
    echo "Error inserting into table: " . $stmt->error . "<br>";
}

// Close the statement and connection
$stmt->close();
$con->close();

?>
</body>
</html>
