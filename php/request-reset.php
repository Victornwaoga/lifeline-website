<?php

session_start();
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/send-reset-email.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
    exit;
}

$identifier = trim($_POST["identifier"] ?? "");

if ($identifier === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please enter your email or phone number."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Find the user
|--------------------------------------------------------------------------
*/

$result = pg_query_params(
    $conn,
    "SELECT id, name, email
     FROM users
     WHERE email = $1 OR phone = $1
     LIMIT 1",
    [$identifier]
);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);
    exit;
}

$user = pg_fetch_assoc($result);

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "No account was found with those details."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Generate secure reset token
|--------------------------------------------------------------------------
*/

$token = bin2hex(random_bytes(32));

$expires_at = date(
    "Y-m-d H:i:s",
    time() + (60 * 30)
);

/*
|--------------------------------------------------------------------------
| Remove previous reset tokens for this user
|--------------------------------------------------------------------------
*/

$delete_result = pg_query_params(
    $conn,
    "DELETE FROM password_resets WHERE user_id = $1",
    [$user["id"]]
);

if (!$delete_result) {
    echo json_encode([
        "success" => false,
        "message" => "Unable to create password reset request."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Store new reset token
|--------------------------------------------------------------------------
*/

$insert_result = pg_query_params(
    $conn,
    "INSERT INTO password_resets
        (user_id, token, expires_at)
     VALUES
        ($1, $2, $3)",
    [
        $user["id"],
        $token,
        $expires_at
    ]
);

if (!$insert_result) {
    echo json_encode([
        "success" => false,
        "message" => "Unable to create password reset request."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Create reset link
|--------------------------------------------------------------------------
*/

$reset_link =
    "http://localhost/lifeline/reset-password.php?token=" .
    urlencode($token);

/*
|--------------------------------------------------------------------------
| Email will be added in the next step
|--------------------------------------------------------------------------
*/

$email_sent = sendResetEmail(
    $user["email"],
    $user["name"],
    $reset_link
);

if (!$email_sent) {
    echo json_encode([
        "success" => false,
        "message" => "We could not send the password reset email. Please try again later."
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "A password reset link has been sent to your email address."
]);

?>