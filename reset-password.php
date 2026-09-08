<?php

session_start();
require_once __DIR__ . "/php/db.php";

$token = trim($_GET["token"] ?? "");
$message = "";
$success = false;
$user_id = null;

/* To check the reset token */

if ($token === "") {
    $message = "Invalid password reset link.";
} else {

    $result = pg_query_params(
        $conn,
        "SELECT user_id
         FROM password_resets
         WHERE token = $1
         AND expires_at > CURRENT_TIMESTAMP
         LIMIT 1",
        [$token]
    );

    if (!$result) {
        $message = "Unable to verify the reset link.";
    } else {

        $reset = pg_fetch_assoc($result);

        if (!$reset) {
            $message = "This password reset link is invalid or has expired.";
        } else {
            $user_id = $reset["user_id"];
            $success = true;
        }
    }
}

/* To handle new password */

if ($_SERVER["REQUEST_METHOD"] === "POST" && $success) {

    $new_password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($new_password === "" || $confirm_password === "") {

        $message = "Please enter your new password.";
        $success = false;

    } elseif ($new_password !== $confirm_password) {

        $message = "The passwords do not match.";
        $success = false;

    } elseif (strlen($new_password) < 8) {

        $message = "Password must be at least 8 characters.";
        $success = false;

    } else {

        /* To hash the new password */

        $password_hash = password_hash(
            $new_password,
            PASSWORD_DEFAULT
        );

        $update = pg_query_params(
            $conn,
            "UPDATE users
             SET password_hash = $1
             WHERE id = $2",
            [
                $password_hash,
                $user_id
            ]
        );

        if (!$update) {

            $message = "Unable to update your password.";
            $success = false;

        } else {

            /* To delete the used reset token */

            pg_query_params(
                $conn,
                "DELETE FROM password_resets
                 WHERE token = $1",
                [$token]
            );

            $message = "Your password has been reset successfully.";
            $success = false;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - LifeLine</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f4f8fb;
        }

        .reset-card {
            width: 100%;
            max-width: 430px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            color: #b71c1c;
            text-align: center;
        }

        p {
            color: #555;
            line-height: 1.6;
        }

        label {
            display: block;
            margin-top: 20px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #c62828;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #a91f1f;
        }

        .message {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 6px;
            background: #fbe9e7;
            color: #b71c1c;
        }

        .success-message {
            text-align: center;
        }

        .login-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #b71c1c;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <main class="reset-card">
        <h1>Reset Password</h1>
        <?php if ($message !== ""): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <p>Enter a new password for your LifeLine account.</p>
            <form method="POST">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" minlength="8" required>
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="8" required>
                <button type="submit">Reset Password</button>
            </form>

        <?php else: ?>
            <a href="first-page.php" class="login-link">Return to Login</a>
        <?php endif; ?>
    </main>
</body>
</html>