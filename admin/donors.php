<?php session_start(); 
require_once __DIR__ . "/../php/db.php"; 

if ( !isset($_SESSION["user_id"]) || 
!isset($_SESSION["is_admin"]) || 
$_SESSION["is_admin"] !== true ) { 
    header("Location: admin-login.php"); 
    exit; 
} $message = ""; 
$messageType = ""; 

/* DELETE DONOR */ 
if ($_SERVER["REQUEST_METHOD"] === "POST" && 
isset($_POST["delete_donor"])) { 
    $donorId = (int)($_POST["donor_id"] ?? 0); 
 if ($donorId > 0) { $result = pg_query_params( 
        $conn, 
        "DELETE FROM donors WHERE id = $1", [$donorId]
     ); 
if ($result) { $message = "Donor deleted successfully."; 
$messageType = "success"; 
} else { $message = "Unable to delete donor."; 
$messageType = "error"; } } } 

/* GET DONORS */ 
$search = trim($_GET["search"] ?? ""); 
if ($search !== "") { $donors = pg_query_params( 
    $conn, 
    "SELECT id, 
    full_name, 
    blood_type, 
    phone, 
    location, 
    available FROM donors 
    WHERE full_name ILIKE $1 OR blood_type ILIKE $1 
    OR phone ILIKE $1 OR location ILIKE $1 ORDER BY id DESC", ["%" . $search . "%"] ); } 
else { $donors = pg_query( 
    $conn, 
    "SELECT id, 
    full_name, 
    blood_type, 
    phone, 
    location, 
    available FROM donors ORDER BY id DESC" ); 
    } 
    ?> 

<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>LifeLine | Manage Donors</title> 
        <style> 
        * { box-sizing: border-box; margin: 0; padding: 0; } 
        body { font-family: Arial, sans-serif; background: #f5f8fc; color: #172033; } 
        .header { background: #ffffff; border-bottom: 1px solid #e4e7ec; padding: 15px 18px; } 
        .header-inner { max-width: 1200px; margin: auto; display: flex; align-items: center; justify-content: space-between; gap: 12px; } 
        .logo { color: #d71920; font-size: 21px; font-weight: 800; } 
        .back { text-decoration: none; color: #1677ff; font-size: 14px; font-weight: 600; } 
        .container { max-width: 1200px; margin: auto; padding: 25px 18px 50px; } 
        .page-heading { margin-bottom: 22px; } 
        .page-heading h1 { font-size: 28px; margin-bottom: 7px; } 
        .page-heading p { color: #667085; } 
        .search-box { background: white; padding: 15px; border: 1px solid #e4e7ec; border-radius: 12px; margin-bottom: 20px; } 
        .search-form { display: flex; gap: 10px; } 
        .search-form input { flex: 1; min-width: 0; padding: 12px; border: 1px solid #d0d5dd; border-radius: 8px; font-size: 15px; } 
        .search-form button { border: none; background: #1677ff; color: white; padding: 12px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; } 
        .message { padding: 12px; border-radius: 8px; margin-bottom: 18px; font-size: 14px; } 
        .success { background: #ecfdf3; color: #067647; } 
        .error { background: #fff1f1; color: #c1121f; } 
        .table-container { background: white; border: 1px solid #e4e7ec; border-radius: 12px; overflow-x: auto; } 
        table { width: 100%; min-width: 750px; border-collapse: collapse; } 
        th, td { padding: 14px 12px; text-align: left; border-bottom: 1px solid #eaecf0; font-size: 14px; } 
        th { color: #667085; font-size: 12px; text-transform: uppercase; } 
        .badge { display: inline-block; padding: 5px 9px; border-radius: 20px; font-size: 12px; font-weight: 700; } 
        .available { background: #ecfdf3; color: #067647; } 
        .unavailable { background: #fff1f1; color: #c1121f; } 
        .blood { color: #d71920; font-weight: 800; } 
        .delete-button { border: none; background: #fee4e2; color: #b42318; padding: 7px 10px; border-radius: 6px; cursor: pointer; font-weight: 600; } 
        .empty { padding: 25px; color: #667085; text-align: center; } 
        @media (min-width: 700px) { 
            .container { padding: 35px 25px 60px; } 
            .page-heading h1 { font-size: 32px; } 
        } 
    </style> 
    </head> 
    <body> 
        <header class="header"> 
            <div class="header-inner"> 
                <div class="logo"> 🩸 LifeLine Admin </div> 
                    <a href="dashboard.php" class="back"> ← Dashboard </a> 
            </div> 
        </header> 
        <main class="container"> 
            <section class="page-heading"> 
                <h1>Registered Donors</h1> 
                <p> View and manage people registered in the LifeLine donor network. </p> 
            </section>                 <?php if ($message !== ""): ?> 
                 <div class="message <?= htmlspecialchars($messageType) ?>"> <?= htmlspecialchars($message) ?> 
            </div> 
            <?php endif; ?> 
            <div class="search-box"> 
                <form method="GET" class="search-form"> 
                    <input type="search" name="search" placeholder="Search name, blood type, phone or location..." value="<?= htmlspecialchars($search) ?>" > 
                    <button type="submit"> Search </button> 
                </form> 
            </div> 
            <div class="table-container"> <?php if ($donors && pg_num_rows($donors) > 0): ?> 
                <table> 
                    <thead> 
                        <tr> 
                           <th>Name</th> 
                            <th>Blood Type</th> 
                            <th>Phone</th> 
                            <th>Location</th> 
                            <th>Status</th> 
                            <th>Action</th> 
                        </tr> 
                    </thead> 
                    <tbody> <?php while ($donor = pg_fetch_assoc($donors)): ?> 
                        <tr> 
                            <td> <?= htmlspecialchars($donor["full_name"]) ?> </td> 
                            <td class="blood"> <?= htmlspecialchars($donor["blood_type"]) ?> </td> 
                            <td> <?= htmlspecialchars($donor["phone"]) ?> </td> 
                            <td> <?= htmlspecialchars($donor["location"]) ?> </td> 
                            <td> <?php if ($donor["available"] === "t" || $donor["available"] === true): ?> 
                                <span class="badge available"> Available </span> 
                                <?php else: ?> 
                                    <span class="badge unavailable"> Unavailable </span> 
                                    <?php endif; ?> 
                            </td> 
                            <td> 
                                <form method="POST" onsubmit="return confirm('Delete this donor?');"> 
                                    <input type="hidden" name="donor_id" value="<?= (int)$donor["id"] ?>" > 
                                    <button type="submit" name="delete_donor" class="delete-button" > Delete </button> 
                                </form> 
                            </td> 
                        </tr> 
                        <?php endwhile; ?> 
                    </tbody> 
                </table> 
                <?php else: ?> 
                <div class="empty"> No donors found. </div> 
                    <?php endif; ?> 
            </div> 
        </main> 
    </body> 
</html>