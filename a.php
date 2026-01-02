<html>
<body align="center">

<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection details
$server = "tcp:tahira-sql-server.mysql.database.azure.com,1433"; 
$database = "tahira-sql-database"; 
$username = "pxxjxlukdz"; 
$password = "@bajwa123456789"; 

// Connection options for SQL Server
$connectionInfo = array( "Database"=>$database, "UID"=>$username, "PWD"=>$password);
$con = sqlsrv_connect($server, $connectionInfo);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
} else {
    echo "Connection successful.<br>";
}

// Create table if it doesn't exist
$tableCreateQuery = "
IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'formas3')
BEGIN
    CREATE TABLE formas3 (
        ID INT IDENTITY(1,1) PRIMARY KEY,
        First_name NVARCHAR(100) NOT NULL,
        Email_id NVARCHAR(255) NOT NULL,
        Telephone_Number NVARCHAR(20) NOT NULL,
        comments NVARCHAR(MAX) NOT NULL,
        created_at DATETIME DEFAULT GETDATE()
    );
END;
";

$tableCreateStmt = sqlsrv_query($con, $tableCreateQuery);
if ($tableCreateStmt === false) {
    die("Error creating table: " . print_r(sqlsrv_errors(), true));
} else {
    echo "Table check/creation successful.<br>";
}

// Validate POST data
$Fname = isset($_POST['First_name']) ? $_POST['First_name'] : '';
$F = isset($_POST['Email_id']) ? $_POST['Email_id'] : '';
$E = isset($_POST['Telephone_Number']) ? $_POST['Telephone_Number'] : '';
$R = isset($_POST['comments']) ? $_POST['comments'] : '';

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
$params = array($Fname, $F, $E, $R);
$stmt = sqlsrv_query($con, $sql, $params);

if ($stmt === false) {
    echo "Error inserting into table: " . print_r(sqlsrv_errors(), true) . "<br>";
} else {
    echo "<h2>Kindly Confirm your details👀</h2>Thanks...!<br>";
}

// Close the statement and connection
sqlsrv_free_stmt($stmt);
sqlsrv_close($con);

?>
</body>
</html>
