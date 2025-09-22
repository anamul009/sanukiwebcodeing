<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Configuration ---
    $recipient_email = "info@kajitsunoousama.com"; // YOUR EMAIL ADDRESS
    $form_sender_email = "noreply@your-domain.com";    // AN EMAIL FROM YOUR DOMAIN

    // --- Sanitize Input ---
    $name = strip_tags(trim($_POST["name"]));
    $phone = strip_tags(trim($_POST["phone"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    // --- Validation ---
    if (empty($name) || empty($email) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: contact.html?status=error");
        exit;
    }

    // --- Build Email ---
    // Subject (Encoded for Japanese)
    $encoded_subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

    // Body
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n\n";
    $email_content .= "Subject: $subject\n";
    $email_content .= "Message:\n$message\n";

    // Headers (Crucial for Japanese characters)
    $headers = "From: " . mb_encode_mimeheader($name) . " <" . $form_sender_email . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit";

    // --- Send Email ---
    if (mail($recipient_email, $encoded_subject, $email_content, $headers)) {
        header("Location: thank-you.html");
    } else {
        header("Location: contact.html?status=server_error");
    }
    exit;
} else {
    header("Location: contact.html");
    exit;
}
?>