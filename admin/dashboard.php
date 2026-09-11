<?php session_start(); 
require_once __DIR__ . "/../php/db.php"; 

/* ========================================= ADMIN ACCESS PROTECTION ========================================= */ 
if (!isset($_SESSION["user_id"]) || 
!isset($_SESSION["is_admin"]) || 
$_SESSION["is_admin"] !== true) { 
    header("Location: admin-login.php"); 
    exit; } 
    
    
/* ========================================= GET DASHBOARD STATISTICS ========================================= */ 
function getCount($conn, $query)
{
    $result = pg_query($conn, $query);

    if ($result === false) {
        return 0;
    }

    return (int) pg_fetch_result($result, 0, 0);
 
} 
$totalUsers = getCount( $conn, "SELECT COUNT(*) FROM users" ); 
$totalDonors = getCount( $conn, "SELECT COUNT(*) FROM donors" ); 
$totalRequests = getCount( $conn, "SELECT COUNT(*) FROM urgent_requests" ); 
$totalResources = getCount( $conn, "SELECT COUNT(*) FROM emergency_resources" ); 

/* ========================================= RECENT USERS ========================================= */ 
$recentUsers = pg_query( 
    $conn, 
    "SELECT name, 
    email, 
    phone FROM users ORDER BY id DESC LIMIT 5" ); 
?> 

<!DOCTYPE html> 
<html lang="en"> 
    <head> 
        <meta charset="UTF-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0" > 
        <title>LifeLine | Admin Dashboard</title> 
        <style> 
        * { box-sizing: border-box; margin: 0; padding: 0; } 
        body { font-family: Arial, sans-serif; background: #f5f8fc; color: #172033; } 
        
        /* ================================= HEADER ================================= */ 
        .admin-header { background: #ffffff; border-bottom: 1px solid #e4e7ec; padding: 15px 20px; } 
        .header-container { max-width: 1200px; 
            margin: auto; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            gap: 15px; } .logo { display: flex; align-items: center; gap: 10px; } 
        .logo-icon { font-size: 28px; } 
        .logo-text { font-size: 22px; font-weight: 800; color: #d71920; } 
        .admin-label { display: none; color: #667085; font-size: 14px; } 
        .logout { text-decoration: none; 
            background: #d71920; 
            color: white; 
            padding: 9px 14px; 
            border-radius: 7px; 
            font-size: 14px; 
            font-weight: 600; } 
            
        /* ================================= MAIN ================================= */ 
        .dashboard { width: 100%; max-width: 1200px; margin: auto; padding: 25px 18px 50px; } 
        .welcome { margin-bottom: 25px; } 
        .welcome h1 { font-size: 27px; margin-bottom: 7px; } 
        .welcome p { color: #667085; line-height: 1.5; } 
        /* ================================= STATISTICS ================================= */ 
        .stats-grid { display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 30px; } 
        .stat-card { background: #ffffff; border-radius: 13px; padding: 20px; border: 1px solid #e4e7ec; } 
        .stat-icon { font-size: 28px; margin-bottom: 12px; } 
        .stat-number { font-size: 30px; font-weight: 800; margin-bottom: 5px; } 
        .stat-title { color: #667085; font-size: 14px; } 
        /* ================================= QUICK ACTIONS ================================= */ 
        .section-title { font-size: 20px; margin-bottom: 15px; } 
        .actions-grid { display: grid; grid-template-columns: 1fr; gap: 12px; margin-bottom: 30px; } 
        .action-card { display: block; 
            background: #ffffff; 
            border: 1px solid #e4e7ec; 
            border-radius: 12px; 
            padding: 18px; 
            text-decoration: none; 
            color: #172033; 
            transition: transform 0.2s ease, box-shadow 0.2s ease; } 
        .action-card:hover { transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07); } 
        .action-card strong { display: block; margin-bottom: 5px; font-size: 16px; } 
        .action-card span { color: #667085; font-size: 13px; } 
        /* ================================= RECENT USERS ================================= */ 
        .recent-section { background: #ffffff; 
            border: 1px solid #e4e7ec; 
            border-radius: 13px; 
            padding: 20px; 
            overflow-x: auto; } 
        .users-table { width: 100%; min-width: 600px; border-collapse: collapse; } 
        .users-table th, 
        .users-table td { text-align: left; padding: 13px 10px; border-bottom: 1px solid #eaecf0; font-size: 14px; } 
        .users-table th { color: #667085; font-size: 12px; text-transform: uppercase; } 
        .empty { color: #667085; padding: 15px 0; } 
        /* ================================= TABLET ================================= */ 
        @media (min-width: 600px) { 
            .admin-label { display: block; } 
            .stats-grid { grid-template-columns: repeat(2, 1fr); } 
            .actions-grid { grid-template-columns: repeat(2, 1fr); } 
            .dashboard { padding: 35px 25px 60px; } } 
            
        /* ================================= DESKTOP ================================= */ 
        @media (min-width: 900px) { 
            .stats-grid { grid-template-columns: repeat(4, 1fr); } 
            .actions-grid { grid-template-columns: repeat(4, 1fr); } 
            .welcome h1 { font-size: 32px; } } 
            
        </style> 
        </head> 
        <body> 
            <header class="admin-header"> 
                <div class="header-container"> 
                    <div class="logo"> 
                        <span class="logo-icon">🩸</span> 
                        <span class="logo-text"> LifeLine </span> 
                    </div> <span class="admin-label"> Administrator Dashboard </span> 
                    <a href="admin-login.php" class="logout" onclick="return confirm('Are you sure you want to log out?');" > Logout </a> 
                </div> 
            </header> 
            <main class="dashboard"> 
                <!-- WELCOME --> 
                <section class="welcome"> 
                    <h1> Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?> </h1> 
                    <p> Manage the LifeLine blood donation and emergency response network. </p> 
                </section> 
                <!-- STATISTICS --> 
                 <section class="stats-grid"> 
                    <div class="stat-card"> 
                        <div class="stat-icon">👥</div> 
                        <div class="stat-number"> <?= $totalUsers ?> </div> 
                        <div class="stat-title"> Registered Users </div> 
                    </div> 
                    <div class="stat-card"> 
                        <div class="stat-icon">🩸</div> 
                        <div class="stat-number"> <?= $totalDonors ?> </div> 
                        <div class="stat-title"> Registered Donors </div> 
                    </div> 
                    <div class="stat-card"> 
                        <div class="stat-icon">🚨</div> 
                        <div class="stat-number"> <?= $totalRequests ?> </div> 
                        <div class="stat-title"> Urgent Requests </div> 
                    </div> 
                    <div class="stat-card"> 
                        <div class="stat-icon">🏥</div> 
                        <div class="stat-number"> <?= $totalResources ?> </div> 
                        <div class="stat-title"> Emergency Resources </div> 
                    </div> 
                </section> 
                <!-- QUICK ACTIONS --> 
                 <section> 
                    <h2 class="section-title"> Management </h2> 
                    <div class="actions-grid"> 
                        <a href="donors.php" class="action-card"> 
                            <strong>🩸 Manage Donors</strong> 
                            <span> View and manage registered blood donors. </span> 
                        </a> 
                        <a href="urgent-requests.php" class="action-card"> 
                            <strong>🚨 Urgent Requests</strong> 
                            <span> Review blood emergency requests. </span> 
                        </a> 
                        <a href="emergency-resources.php" class="action-card"> 
                            <strong>🏥 Emergency Resources</strong> 
                            <span> Manage hospitals, blood banks and ambulances. </span> 
                        </a> 
                        <a href="users.php" class="action-card"> 
                            <strong>👥 Manage Users</strong> 
                            <span>View registered LifeLine accounts.</span> 
                        </a> 
                    </div> 
                </section> 
                <!-- RECENT USERS --> 
                <section class="recent-section"> 
                    <h2 class="section-title"> Recent Users </h2> 
                    <?php if ($recentUsers && pg_num_rows($recentUsers) > 0): 
                        ?> 
                    <table class="users-table"> 
                        <thead> 
                            <tr> 
                                <th>Name</th> 
                                <th>Email</th> 
                                <th>Phone</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php while ($user = pg_fetch_assoc($recentUsers)): 
                            ?> 
                            <tr> 
                                <td> <?= htmlspecialchars($user["name"]) ?> </td> 
                                <td> <?= htmlspecialchars($user["email"]) ?> </td> 
                                <td> <?= htmlspecialchars($user["phone"]) ?> </td> 
                            </tr> <?php endwhile; ?> 
                        </tbody> 
                    </table> 
                        <?php else: ?> 
                    <p class="empty"> No registered users found. </p> 
                    <?php endif; ?> 
                </section> 
            </main> 
        </body> 
        </html>