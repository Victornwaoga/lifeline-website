<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../vendor/autoload.php";

function sendResetEmail($recipientEmail, $recipientName, $resetLink)
{
    $config = require __DIR__ . "/mail-config.php";

    $mail = new PHPMailer(true);

    try {
        // Gmail SMTP
        $mail->isSMTP();
        $mail->Host = $config["host"];
        $mail->SMTPAuth = true;
        $mail->Username = $config["username"];
        $mail->Password = $config["password"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config["port"];

        // Sender
        $mail->setFrom(
            $config["from_email"],
            $config["from_name"]
        );

        // Recipient
        $mail->addAddress($recipientEmail, $recipientName);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "LifeLine Password Reset";

        $mail->Body = "
            <h2>LifeLine Password Reset</h2>

            <p>Hello " . htmlspecialchars($recipientName) . ",</p>

            <p>
                We received a request to reset the password
                for your LifeLine account.
            </p>

            <p>
                Click the button below to create a new password:
            </p>

            <p>
                <a href=\"" . htmlspecialchars($resetLink) . "\"
                   style=\"
                       display:inline-block;
                       padding:12px 20px;
                       background:#c62828;
                       color:white;
                       text-decoration:none;
                       border-radius:6px;
                   \">
                    Reset My Password
                </a>
            </p>

            <p>
                This link will expire in 30 minutes.
            </p>

            <p>
                If you did not request a password reset,
                you can safely ignore this email.
            </p>

            <p>
                — LifeLine Team
            </p>
        ";

        $mail->AltBody =
            "Hello " . $recipientName . ",\n\n" .
            "We received a request to reset your LifeLine password.\n\n" .
            "Reset your password here:\n" .
            $resetLink . "\n\n" .
            "This link will expire in 30 minutes.\n\n" .
            "— LifeLine Team";

        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log("LifeLine email error: " . $mail->ErrorInfo);

        return false;
    }
}
?>