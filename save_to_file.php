<?php
// Define the file path where submissions will be saved
$file = 'form_data.csv';
$redirect_url = 'contact.html?status=success';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Gather and sanitize form data
    $name = filter_input(INPUT_POST, 'Name', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'Phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_EMAIL);
    $service = filter_input(INPUT_POST, 'Service', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'Message', FILTER_SANITIZE_STRING);
    $submission_date = date('Y-m-d H:i:s');
    
    // 2. Prepare data for CSV
    // Ensure data is properly escaped for CSV format (e.g., replacing quotes)
    $data_array = array(
        $submission_date,
        $name,
        $phone,
        $email,
        $service,
        str_replace(array("\r", "\n", ","), array(" ", " ", ";"), $message) // Remove new lines and commas
    );
    
    // Convert array to a CSV string
    $line = implode(",", $data_array) . "\n";

    // 3. Open file and append data
    // Use FILE_APPEND to add to the end, LOCK_EX to prevent concurrent writes
    if (file_put_contents($file, $line, FILE_APPEND | LOCK_EX) !== false) {
        // Success: Redirect back to the contact page
        header("Location: " . $redirect_url);
        exit;
    } else {
        // Error handling
        die("Error saving data. Please check file permissions.");
    }
} else {
    // Direct access redirect
    header("Location: contact.html");
    exit;
}
?>