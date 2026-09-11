<?php session_start(); 
require_once __DIR__ . "/../php/db.php"; 
if ( !isset($_SESSION["user_id"]) || 
!isset($_SESSION["is_admin"]) || 
$_SESSION["is_admin"] !== true ) { header("Location: admin-login.php"); 
exit; } 
$message = ""; 
$messageType = ""; 
/* DELETE REQUEST */ 
if ($_SERVER["REQUEST_METHOD"] === "POST" && 
isset($_POST["delete_request"])) { $requestId = (int)($_POST["request_id"] ?? 0); 
if ($requestId > 0) { $result = pg_query_params( $conn, "DELETE FROM urgent_requests WHERE id = $1", [$requestId] ); 
if ($result) { $message = "Urgent request deleted successfully."; 
$messageType = "success"; } 
else { $message = "Unable to delete request."; 
$messageType = "error"; } } } 

/* GET REQUESTS */ 
$requests = pg_query( $conn, "SELECT * FROM urgent_requests ORDER BY id DESC" ); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>LifeLine | Urgent Requests</title> 
        <style> 
        * { box-sizing: border-box; margin: 0; padding: 0; } 
        body { font-family: Arial, sans-serif; background: #f5f8fc; color: #172033; } 
        .header { background: white; border-bottom: 1px solid #e4e7ec; padding: 15px 18px; } 
        .header-inner { max-width: 1200px; 
            margin: auto; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; } 
        .logo { color: #d71920; font-weight: 800; font-size: 21px; } 
        .back { text-decoration: none; color: #1677ff; font-weight: 600; font-size: 14px; } 
        .container { max-width: 1200px; margin: auto; padding: 25px 18px 50px; } 
        .heading { margin-bottom: 22px; } 
        .heading h1 { font-size: 28px; margin-bottom: 7px; } 
        .heading p { color: #667085; } 
        .message { padding: 12px; border-radius: 8px; margin-bottom: 18px; } 
        .success { background: #ecfdf3; color: #067647; } 
        .error { background: #fff1f1; color: #c1121f; } 
        .table-container { background: white; border: 1px solid #e4e7ec; border-radius: 12px; overflow-x: auto; } 
        table { width: 100%; min-width: 900px; border-collapse: collapse; } 
        th, td { padding: 13px; text-align: left; border-bottom: 1px solid #eaecf0; font-size: 14px; } 
        th { color: #667085; font-size: 12px; text-transform: uppercase; } 
        .delete-button { border: none; 
            background: #fee4e2; 
            color: #b42318; 
            padding: 7px 10px; 
            border-radius: 6px; 
            cursor: pointer; 
            font-weight: 600; } 
        .empty { padding: 25px; text-align: center; color: #667085; } 
        @media (min-width: 700px) { 
            .container { padding: 35px 25px 60px; } 
            .heading h1 { font-size: 32px; } } 
        </style>
    </head> 
    <body> 
        <header class="header"> 
            <div class="header-inner"> 
                <div class="logo"> 🚨 LifeLine Admin </div> 
                <a href="dashboard.php" class="back"> ← Dashboard </a> 
            </div> 
        </header> 
        <main class="container"> 
            <section class="heading"> 
                <h1>Urgent Blood Requests</h1> 
                <p> Monitor emergency blood requests submitted through LifeLine. </p> 
            </section> <?php if ($message !== ""): ?> 
            <div class="message <?= htmlspecialchars($messageType) ?>"> <?= htmlspecialchars($message) ?> </div> 
             <?php endif; ?> 
            <div class="table-container"> <?php if ($requests && pg_num_rows($requests) > 0): ?> 
                <table> 
                    <thead> 
                        <tr> <?php $fields = pg_num_fields($requests); for ($i = 0; $i < $fields; $i++): $fieldName = pg_field_name($requests, $i); ?> 
                           <th> <?= htmlspecialchars($fieldName) ?> </th> 
                           <?php endfor; ?> 
                           <th>Action</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php while ($request = pg_fetch_assoc($requests)): ?> 
                        <tr> <?php foreach ($request as $value): ?> 
                            <td> <?= htmlspecialchars((string)$value) ?> </td> 
                            <?php endforeach; ?> 
                            <td> 
                                <form method="POST" onsubmit="return confirm('Delete this urgent request?');"> 
                                    <input type="hidden" name="request_id" value="<?= (int)$request["id"] ?>" > 
                                    <button type="submit" name="delete_request" class="delete-button" > Delete </button> 
                                </form> 
                            </td> 
                        </tr> 
                        <?php endwhile; ?> 
                    </tbody> 
                </table> 
                <?php else: ?> 
                    <div class="empty"> No urgent requests found. </div> 
                    <?php endif; ?> 
            </div> 
        </main> 
    </body> 
</html>