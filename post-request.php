<?php 
  session_start();
  require_once __DIR__ . "/php/db.php"; 
   $message = ""; 
   $message_type = ""; 

  /* Form Submission */ 
  if ($_SERVER["REQUEST_METHOD"] === "POST") { 
    
      /* Get form values */ 
      $patient_name = trim($_POST["patient_name"] ?? ""); 
      $blood_type = trim($_POST["blood_type"] ?? ""); 
      $hospital = trim($_POST["hospital"] ?? ""); 
      $location = trim($_POST["location"] ?? ""); 
      $urgency = trim($_POST["urgency"] ?? "urgent"); 
      $units_needed = (int)($_POST["units_needed"] ?? 1); 
      $needed_by = trim($_POST["needed_by"] ?? ""); 
      $contact_phone = trim($_POST["contact_phone"] ?? ""); 
      $additional_info = trim($_POST["additional_info"] ?? ""); 

      /* Validation */ 
      $allowed_blood_types = [ "A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-" ]; 
      $allowed_urgency = [ "urgent", "critical", "normal" ]; 
      if ( $patient_name === "" || $blood_type === "" || $hospital === "" || $location === "" || $needed_by === "" || $contact_phone === "" ) { 
          $message = "Please fill in all required fields."; $message_type = "error"; 
       } elseif (!in_array($blood_type, $allowed_blood_types, true)) { 
          $message = "Please select a valid blood type."; $message_type = "error"; 
       } elseif (!in_array($urgency, $allowed_urgency, true)) { 
          $message = "Please select a valid urgency level."; $message_type = "error"; 
       } elseif ($units_needed < 1) { $message = "Units needed must be at least 1."; 
         $message_type = "error"; 
       } else { 

          /* Insert into database */ 
          $query = " INSERT INTO urgent_requests ( 
          patient_name, 
          blood_type, 
          hospital, 
          location, 
          urgency, 
          units_needed, 
          needed_by, 
          contact_phone, 
          additional_info ) 
          VALUES ( $1, $2, $3, $4, $5, $6, $7, $8, $9 ) 
          RETURNING id "; $result = pg_query_params( 
            $conn, $query, 
            [ $patient_name, $blood_type, $hospital, 
            $location, $urgency, $units_needed, 
            $needed_by, $contact_phone, $additional_info ] ); 

            /* Check result */ 
            if ($result) { $new_request = pg_fetch_assoc($result); $request_id = $new_request["id"];

            /* Request successfully created. */ 
            header( "Location: urgent-requests.php" ); 
            exit; 
            } else { 
                $message = "Unable to post the blood request. Please try again."; 
                $message_type = "error"; 
            } 
        } 
    } 

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Post an urgent blood request with LifeLine.">
   <title>Post Urgent Request | LifeLine</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   <header class="site-header">
       <div class="header-container">
            <a href="first-page.php" class="logo">
                <span class="logo-icon" aria-hidden="true"><span class="logo-drop"><img src="images/blood-bank.svg" alt="LifeLine"></span></span>
                <span class="logo-text">Life<span>Line</span></span>
            </a>
           <nav class="main-nav">
               <a href="first-page.php">Home</a>
               <a href="find-donor.php">Find a Donor</a>
               <a href="become-donor.php">Become a Donor</a>
               <a href="urgent-requests.php">Urgent Requests</a>
               <a href="emergency-resources.php">Emergency Resources</a>
           </nav>
       </div>
   </header>

<main>
<section class="page-header">
    <div class="container">
        <h1>Post an Urgent Blood Request</h1>
        <p>Share the details of the blood needed so suitable donors can respond.</p>
    </div>
</section>

<section>
    <div class="container">
        <div class="form-card">
            <?php if ($message !== ""): ?>
                <div class="message <?= htmlspecialchars($message_type) ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="post-request.php">

                <div class="form-group">
                    <label for="patient_name">Patient Name <span>*</span></label>
                    <input type="text" id="patient_name" name="patient_name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="blood_type">Blood Type <span>*</span></label>
                        <select id="blood_type" name="blood_type" required>
                            <option value=""  class="blood">Select Blood Type</option>
                            <option value="A+"  class="blood">A+</option>
                            <option value="A-"  class="blood">A-</option>
                            <option value="B+"  class="blood">B+</option>
                            <option value="B-"  class="blood">B-</option>
                            <option value="AB+"  class="blood">AB+</option>
                            <option value="AB-"  class="blood">AB-</option>
                            <option value="O+"  class="blood">O+</option>
                            <option value="O-"  class="blood">O-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="units_needed">Units Needed <span>*</span></label>
                        <input type="number" id="units_needed" name="units_needed" min="1" value="1" required>                    
                    </div>
                </div>

                <div class="form-group">
                    <label for="hospital">Hospital/Medical Facility <span>*</span></label>
                    <input type="text" id="hospital" name="hospital" required>
                </div>

                <div class="form-group">
                    <label for="location">Location/Area <span>*</span></label>
                    <input type="text" id="location" name="location" placeholder="e.g. Abakaliki" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="urgency">Urgency <span>*</span></label>
                        <select id="urgency" name="urgency" required>
                            <option value="urgent">Urgent</option>
                            <option value="critical">Critical</option>
                            <option value="normal">Normal</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="needed_by">Needed By <span>*</span></label>
                        <input type="datetime-local" id="needed_by" name="needed_by" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="contact_phone">Contact Phone <span>*</span></label>
                    <input type="tel" id="contact_phone" name="contact_phone" placeholder="e.g. 08012345678" required>
                </div>

                <div class="form-group">
                    <label for="additional_info">Additional Information </label>
                    <textarea id="additional_info" name="additional_info" placeholder="Add any other important information..."></textarea>
                </div>

                <button type="submit" class="submit-button">Post Urgent Request</button>
            </form>
        </div>
    </div>
</section>
</main>
</body>
</html>