<?php session_start(); 
require_once __DIR__ . "/../php/db.php"; 

if ( !isset($_SESSION["user_id"]) || 
!isset($_SESSION["is_admin"]) || 
$_SESSION["is_admin"] !== true ) { header("Location: admin-login.php"); 
exit; } $message = ""; $messageType = ""; 

/* DELETE RESOURCE */ 
if ($_SERVER["REQUEST_METHOD"] === "POST" && 
isset($_POST["delete_resource"])) { $resourceId = (int)($_POST["resource_id"] ?? 0); 
if ($resourceId > 0) { $result = pg_query_params( $conn, "DELETE FROM emergency_resources WHERE id = $1", [$resourceId] ); 
if ($result) { $message = "Emergency resource deleted successfully."; 
$messageType = "success"; } 
else { $message = "Unable to delete emergency resource."; $messageType = "error"; } } } 

/* GET RESOURCES */ 
$resources = pg_query( $conn, "SELECT * FROM emergency_resources ORDER BY id DESC" ); 
?> 

<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>LifeLine | Emergency Resources</title> 
        <style> 
        * { box-sizing: border-box; margin: 0; padding: 0; } 
        body { font-family: Arial, sans-serif; background: #f5f8fc; color: #172033; } 
        .header { background: white; border-bottom: 1px solid #e4e7ec; padding: 15px 18px; } 
        .header-inner { max-width: 1200px; 
            margin: auto; 
            display: flex; 
            justify-content:  space-between; 
            align-items: center; } 
        .logo { color: #d71920; font-size: 21px; font-weight: 800; } 
        .back { color: #1677ff; text-decoration: none; font-size: 14px; font-weight: 600; } 
        .container { max-width: 1200px; margin: auto; padding: 25px 18px 50px; } 
        .heading { margin-bottom: 22px; } 
        .heading h1 { font-size: 28px; margin-bottom: 7px; } 
        .heading p { color: #667085; } 
        .message { padding: 12px; border-radius: 8px; margin-bottom: 18px; } 
        .success { background: #ecfdf3; color: #067647; } 
        .error { background: #fff1f1; color: #c1121f; } 
        .table-container { background: white; border: 1px solid #e4e7ec; border-radius: 12px; overflow-x: auto; } 
        table { width: 100%; min-width: 850px; border-collapse: collapse; } 
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
                <div class="logo"> 🏥 LifeLine Admin </div> 
                <a href="dashboard.php" class="back"> ← Dashboard </a> 
            </div> 
        </header> 
        <main class="container"> 
            <section class="heading"> 
                <h1>Emergency Resources</h1> 
                <p> Manage hospitals, blood banks, ambulance services and other emergency resources. </p> 
            </section> <?php if ($message !== ""): ?> 
                <div class="message <?= htmlspecialchars($messageType) ?>"> <?= htmlspecialchars($message) ?> 
            </div> <?php endif; ?> 
            <div class="table-container"> <?php if ($resources && pg_num_rows($resources) > 0): ?> 
                <table> 
                    <thead> 
                        <tr> <?php $fields = pg_num_fields($resources); for ($i = 0; $i < $fields; $i++): $fieldName = pg_field_name($resources, $i); ?> 
                           <th> <?= htmlspecialchars($fieldName) ?> </th> 
                           <?php endfor; ?> 
                           <th>Action</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php while ($resource = pg_fetch_assoc($resources)): ?> 
                        <tr> <?php foreach ($resource as $value): ?> 
                            <td> <?= htmlspecialchars((string)$value) ?> </td> 
                            <?php endforeach; ?> 
                            <td> 
                                <form method="POST" onsubmit="return confirm('Delete this emergency resource?');"> 
                                    <input type="hidden" name="resource_id" value="<?= (int)$resource["id"] ?>" > 
                                    <button type="submit" name="delete_resource" class="delete-button" > Delete </button> 
                                </form> 
                            </td> 
                        </tr> <?php endwhile; ?> 
                    </tbody> 
                </table> 
                <?php else: ?> 
                <div class="empty"> No emergency resources found. </div> 
                <?php endif; ?> 
            </div> 
        </main> 
    </body> 
</html>