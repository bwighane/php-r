<!DOCTYPE html>
<html>
<head>
	<title>Client Registration Form</title>
</head>
<body>
	<h1>Client Registration Form</h1>
	<form action="" method="POST">
		<label for="name">Name:</label>
		<input type="text" name="name" id="name" required><br>

		<label for="email">Email:</label>
		<input type="email" name="email" id="email" required><br>

		<label for="phone">Phone:</label>
		<input type="tel" name="phone" id="phone" required><br>

		<label for="message">Message:</label>
		<textarea name="message" id="message" rows="5" required></textarea><br>

		<input type="submit" name="submit" value="Register">
	</form>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer library
// require 'vendor/autoload.php';

require './vendor/autoload.php';

// Handle form submission
if(isset($_POST['submit'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Establishing a connection to the MySQL database
        $servername = "database";
        $username = "user";
        $password = "password";
        $dbname = "registrations";

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        // Checking if the connection is successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Retrieving the form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        // Inserting the form data into the MySQL database
        $sql = "INSERT INTO clients (name, email, phone, message)
                VALUES ('$name', '$email', '$phone', '$message')";

        if (mysqli_query($conn, $sql)) {
            echo "Registration Successful!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Get a summary of all registrations
        $result = mysqli_query($conn, "SELECT name, email, phone, message FROM clients");
        $registrations = "";
        while($row = mysqli_fetch_array($result)) {
            $registrations .= "Name: " . $row['name'] . "\nEmail: " . $row['email'] . "\nPhone: " . $row['phone'] . "\nMessage: " . $row['message'] . "\n\n";
        }

        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.sparkpostmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'SMTP_Injection';
        $mail->Password   = 'b41b5387e9104d465f00d6b472ce3b84e5bcbdea';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('revnyirongo@live.com', 'Client Registration');
        $mail->addAddress('revnyirongo@live.com', 'Organizers');

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'New Client Registration';
        $mail->Body    = "A new client has registered. Details are as follows:\n\nName: $name\nEmail: $email\nPhone: $phone\nMessage: $message\n\nSummary of all registrations:\n\n$registrations";

        $mail->send();
        echo 'Registration successful!';
    } catch (Exception $e) {
        echo "Registration failed: {$mail->ErrorInfo}";
    }
}
?>
</body>
</html>
