<?php session_start(); 
require_once __DIR__ . "/../php/db.php"; 

if ( !isset($_SESSION["user_id"]) || 
!isset($_SESSION["is_admin"]) || 
$_SESSION["is_admin"] !== true ) { header("Location: admin-login.php"); exit; } $message = ""; 
$messageType = ""; 

/* DELETE USER */ 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) { $userId = (int)($_POST["user_id"] ?? 0); 

/* Prevent administrator from deleting their own account */ 
if ($userId === (int)$_SESSION["user_id"]) { $message = "You cannot delete the administrator account you are currently using."; 
$messageType = "error"; 
} elseif ($userId > 0) { $result = pg_query_params( $conn, "DELETE FROM users WHERE id = $1", [$userId] ); 
if ($result) { $message = "User deleted successfully."; 
$messageType = "success"; 
} else { $message = "Unable to delete user."; $messageType = "error"; 
} } } 

/* GET USERS */
$users = pg_query( $conn, "SELECT id, name, email, phone, is_admin FROM users ORDER BY id DESC" ); 
?> 

<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>LifeLine | Manage Users</title> 
        <style> 
        * { box-sizing: border-box; margin: 0; padding: 0; } 
        body { font-family: Arial, sans-serif; background: #f5f8fc; color: #172033; } 
        .header { background: white; border-bottom: 1px solid #e4e7ec; padding: 15px 18px; } 
        .header-inner { max-width: 1200px; 
            margin: auto; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; } 
            .logo { color: #d71920; font-size: 21px; font-weight: 800; } 
            .back { color: #1677ff; text-decoration: none; font-size: 14px; font-weight: 600; } 
            .container { max-width: 1200px; margin: auto; padding: 25px 18px 50px; } 
            .heading { margin-bottom: 22px; } 
            .heading h1 { font-size: 28px; margin-bottom: 7px; } 
            .heading p { color: #667085; } 
            .message { padding: 12px; border-radius: 8px; margin-bottom: 18px; font-size: 14px; } 
            .success { background: #ecfdf3; color: #067647; } 
            .error { background: #fff1f1; color: #c1121f; } 
            .table-container { background: white; border: 1px solid #e4e7ec; border-radius: 12px; overflow-x: auto; } 
            table { width: 100%; min-width: 750px; border-collapse: collapse; } 
            th, td { padding: 13px; text-align: left; border-bottom: 1px solid #eaecf0; font-size: 14px; } 
            th { color: #667085; font-size: 12px; text-transform: uppercase; } 
            .admin-badge { display: inline-block; padding: 5px 9px; border-radius: 20px; background: #eef4ff; color: #175cd3; font-size: 12px; font-weight: 700; } 
            .user-badge { display: inline-block; padding: 5px 9px; border-radius: 20px; background: #f2f4f7; color: #475467; font-size: 12px; font-weight: 700; } 
            .delete-button { border: none; background: #fee4e2; color: #b42318; padding: 7px 10px; border-radius: 6px; cursor: pointer; font-weight: 600; } 
            .current { color: #067647; font-size: 12px; font-weight: 700; } 
            .empty { padding: 25px; text-align: center; color: #667085; } 
            @media (min-width: 700px) { 
                .container { padding: 35px 25px 60px; } 
                .heading h1 { font-size: 32px; } } 
        </style> 
    </head> 
    <body> 
        <header class="header"> 
            <div class="header-inner"> 
                <div class="logo"> 👥 LifeLine Admin </div> 
                <a href="dashboard.php" class="back"> ← Dashboard </a> 
            </div> 
        </header> 
        <main class="container"> 
            <section class="heading"> 
                <h1>Registered Users</h1> 
                <p> View accounts registered on the LifeLine platform. </p> 
            </section> <?php if ($message !== ""): ?> 
            <div class="message <?= htmlspecialchars($messageType) ?>"> <?= htmlspecialchars($message) ?> </div> 
            <?php endif; ?> 
            <div class="table-container"> <?php if ($users && pg_num_rows($users) > 0): ?> 
                <table> 
                    <thead> 
                        <tr> 
                            <th>Name</th> 
                            <th>Email</th> 
                            <th>Phone</th> 
                            <th>Access</th> 
                            <th>Action</th> 
                        </tr> 
                    </thead> 
                    <tbody> <?php while ($user = pg_fetch_assoc($users)): ?> 
                        <tr> 
                            <td> <?= htmlspecialchars($user["name"]) ?> <?php if ((int)$user["id"] === (int)$_SESSION["user_id"]): ?> 
                                <div class="current"> Current account </div> 
                                <?php endif; ?> 
                            </td> 
                            <td> <?= htmlspecialchars($user["email"]) ?> </td> 
                            <td> <?= htmlspecialchars($user["phone"]) ?> </td> 
                            <td> <?php if ($user["is_admin"] === "t" || $user["is_admin"] === true): ?> 
                                <span class="admin-badge"> Administrator </span> 
                                <?php else: ?> 
                                <span class="user-badge"> User </span> 
                                <?php endif; ?> 
                            </td> 
                            <td> <?php if ((int)$user["id"] === (int)$_SESSION["user_id"]): ?> — <?php else: ?> 
                                <form method="POST" onsubmit="return confirm('Delete this user account?');"> 
                                    <input type="hidden" name="user_id" value="<?= (int)$user["id"] ?>" > 
                                    <button type="submit" name="delete_user" class="delete-button" > Delete </button> 
                                </form> <?php endif; ?> 
                            </td> 
                        </tr> <?php endwhile; ?> 
                    </tbody> 
                </table> 
                <?php else: ?> 
                <div class="empty"> No users found. </div> 
                    <?php endif; ?> 
            </div> 
        </main> 
    </body> 
</html>