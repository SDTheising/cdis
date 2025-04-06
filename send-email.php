<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ==== Email Limit Settings ====
$limit = 100;
$count_file = __DIR__ . '/email_count.txt';

// Read count (create file if it doesn't exist)
if (!file_exists($count_file)) {
    file_put_contents($count_file, '0');
}

$current_count = (int)file_get_contents($count_file);

// If limit reached, stop processing
if ($current_count >= $limit) {
    header("Location: contact.html?status=limit");
    exit;
}

// Load environment variables
$api_key = getenv('MAILGUN_API_KEY');
$domain  = getenv('MAILGUN_DOMAIN');

if (!$api_key || !$domain) {
  die("Mailgun configuration not found.");
}

// Get and sanitize form data
$name    = htmlspecialchars($_POST['name']);
$email   = htmlspecialchars($_POST['email']);
$phone   = htmlspecialchars($_POST['phone']);
$message = htmlspecialchars($_POST['message']);

$subject = "New Contact Form Submission from $name";
$body = "Name: $name\nEmail: $email\nPhone: $phone\n\nMessage:\n$message";

// Send email via Mailgun API using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "api:$api_key");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/$domain/messages");

curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'from' => 'CDIS Contact Form <noreply@sandbox145aa4e62c9644df8ea05b224cf75a1f.mailgun.org>',
        'h:Reply-To' => $email,
    'to'      => 'cdistemp@outlook.com',
    'subject' => $subject,
    'text'    => $body
]);

$result = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
file_put_contents("mailgun_debug.log", "HTTP Status: $http_status\nResult: $result");
curl_close($ch);

if ($http_status === 200) {
    $new_count = $current_count + 1;
    file_put_contents($count_file, $new_count);
    header("Location: contact.html?status=success");
    exit;
} else {
    header("Location: contact.html?status=error");
    exit;
}

?>
