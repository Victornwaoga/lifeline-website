<?php session_start(); 
require_once __DIR__ . "/../php/db.php"; 

/* If already logged in as admin, go straight to dashboard */ 
if (isset($_SESSION["user_id"]) && isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true) { 
    header("Location: dashboard.php"); 
exit; } $error = ""; 
    
if ($_SERVER["REQUEST_METHOD"] === "POST") { $identifier = trim($_POST["identifier"] ?? ""); 
$password = $_POST["password"] ?? ""; 
if ($identifier === "" || $password === "") { $error = "Please enter your email/phone and password."; 
} else { $result = pg_query_params( 
    $conn, 
    "SELECT id, 
    name, 
    email, 
    phone, 
    password_hash, 
    is_admin FROM users WHERE email = $1 OR phone = $1 LIMIT 1", [$identifier] ); 

if (!$result) { $error = "Database error. Please try again."; 
    } else { $user = pg_fetch_assoc($result); 
    if (!$user) { $error = "Invalid administrator login details."; 
    } elseif (!password_verify($password, $user["password_hash"])) { $error = "Invalid administrator login details."; 
    } elseif ($user["is_admin"] !== "t" && $user["is_admin"] !== true) { 
        $error = "This account does not have administrator access."; 
        } else { session_regenerate_id(true); 
        $_SESSION["user_id"] = $user["id"]; 
        $_SESSION["user_name"] = $user["name"]; 
        $_SESSION["user_email"] = $user["email"]; 
        $_SESSION["is_admin"] = true; 
        header("Location: dashboard.php"); 
        exit; } } } } 
?> 

<!DOCTYPE html> 
<html lang="en"> <head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>LifeLine | Admin Login</title> 
    <style> 
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            } 

        body { min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px; 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #f8fbff, #eaf5ff); 
            color: #172033; } 
            
        .admin-login { width: 100%; 
            max-width: 430px; 
            background: #ffffff; 
            padding: 35px 25px; 
            border-radius: 18px; 
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.10); } 
            
        .logo { text-align: center; 
            margin-bottom: 25px; } 
        
        .logo-icon { font-size: 42px; 
            display: block; 
            margin-bottom: 8px; } 
            
        .logo h1 { color: #d71920; 
            font-size: 28px; } 
            
        .logo p { color: #667085; 
            margin-top: 5px; 
            font-size: 14px; } 
            
        .admin-title { text-align: center; margin-bottom: 25px; } 
        
        .admin-title h2 { font-size: 22px; margin-bottom: 7px; } 
        
        .admin-title p { color: #667085; font-size: 14px; } 
        .form-group { margin-bottom: 18px; } 
        label { display: block; margin-bottom: 7px; font-weight: 600; font-size: 14px; } 
        input { width: 100%; 
            padding: 13px 14px; 
            border: 1px solid #d0d5dd; 
            border-radius: 9px; 
            font-size: 16px; outline: none; } 
        input:focus { border-color: #1677ff; box-shadow: 0 0 0 3px rgba(22, 119, 255, 0.10); } 
        .error { background: #fff1f1; 
            color: #c1121f; 
            border: 1px solid #ffcaca; 
            padding: 12px; 
            border-radius: 8px; 
            margin-bottom: 18px; 
            font-size: 14px; } 
        button { width: 100%; 
            border: none; 
            padding: 14px; 
            border-radius: 9px; 
            background: #d71920; 
            color: white; 
            font-size: 16px; 
            font-weight: 700; 
            cursor: pointer; } 
        button:hover { background: #b9151b; } 
        .back-link { display: block; 
            text-align: center; 
            margin-top: 20px; 
            color: #1677ff; 
            text-decoration: none; 
            font-size: 14px; } 
        .back-link:hover { text-decoration: underline; } 
        .security-note { margin-top: 25px; 
            padding: 12px; 
            border-radius: 8px; 
            background: #f5f8fc; 
            color: #667085; 
            text-align: center; 
            font-size: 12px; 
            line-height: 1.5; } 
        @media (min-width: 600px) { 
            .admin-login { padding: 40px; } } 

    </style> 
    </head> 
    <body> 
        <main class="admin-login"> 
            <div class="logo"> 
                <span class="logo-icon">🩸</span> 
                <h1>LifeLine</h1> <p>Blood Donation & Emergency Response Network</p> 
            </div> 
            <div class="admin-title"> 
                <h2>Administrator Login</h2> 
                <p>Access the LifeLine management dashboard.</p> 
            </div> 
            <?php if ($error !== ""): ?> 
                <div class="error"> <?= htmlspecialchars($error) ?> 
            </div> 
            <?php endif; 
            ?> 
            <form method="POST"> 
                <div class="form-group"> 
                    <label for="identifier">Email or Phone</label> 
                    <input type="text" id="identifier" name="identifier" placeholder="Enter your email or phone" autocomplete="username" required > 
                </div> 
                <div class="form-group"> 
                    <label for="password">Password</label> 
                    <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required > 
                </div> 
                <button type="submit"> Login as Administrator </button> 
            </form> 
            <a href="../index.php" class="back-link"> ← Back to LifeLine </a> 
            <div class="security-note"> This area is restricted to authorized LifeLine administrators. </div> 
        </main> 
    </body> 
</html>