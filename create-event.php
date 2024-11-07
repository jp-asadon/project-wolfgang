<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
// $name = $address = $salary = "";
// $name_err = $address_err = $salary_err = "";


$event_name = $event_date = $event_start_time = $event_end_time = $event_venue = $event_speaker = $event_image = "";
$event_name_err = $event_date_err = $event_start_time_err = $event_end_time_err = $time_err = $event_venue_err = $event_speaker_err = $event_image_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate event name
    $input_event_name = trim($_POST["id_event_name"]);
    if (empty($input_event_name)) {
        $name_err = "Please enter a name."; 
    } elseif (!filter_var($input_event_name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $name_err = "Please enter a valid name.";
    } else {
        $name = $input_event_name;
    }

      // Validate event date
      $input_date = trim($_POST["id_event_date"]);
      $current_date = date("Y-m-d");
      $tomorrow_date = date("Y-m-d", strtotime("+1 day"));
  
      if (empty($input_date)) {
          $event_date_err = "Please enter a date.";
      } elseif ($input_date < $tomorrow_date) {
          $event_date_err = "Please enter a date from tomorrow onwards.";
      } else {
          $event_date = $input_date;
      }


    // Validate start time and end time
    $input_start_time = trim($_POST["id_event_start_time"]);
    $input_end_time = trim($_POST["id_event_end_time"]);

    if (empty($input_start_time)) {
        $start_time_err = "Please enter a start time.";
    } elseif (empty($input_end_time)) {
        $end_time_err = "Please enter an end time.";
    } elseif (strtotime($input_start_time) >= strtotime($input_end_time)) {
        $time_err = "Start time must be before end time.";
    } else {
        $start_time = $input_start_time;
        $end_time = $input_end_time;
    }

    // Validate event vemue
    $input_venue = trim($_POST["id_event_venue"]);
    if (empty($input_venue)) {
        $event_venue_err = "Please enter the Event Venue.";
    } else {
        $event_venue = $input_venue;
    }

    // Validate event speaker name
    $input_speaker = trim($_POST["id_event_speaker"]);
    if (empty($input_speaker)) {
        $event_speaker_err = "Please enter the Event Venue.";
    } else {
        $event_speaker = $input_speaker;
    }

    // // Validate salary
    // $input_salary = trim($_POST["salary"]);
    // if (empty($input_salary)) {
    //     $salary_err = "Please enter the salary amount.";
    // } elseif (!ctype_digit($input_salary)) {
    //     $salary_err = "Please enter a positive integer value.";
    // } else {
    //     $salary = $input_salary;
    // }

    // Check input errors before inserting in database
    if (empty($event_name_err) && empty($event_date_err) && empty($event_start_time_err) && empty($event_end_time_err) 
    && empty($time_err) && empty($event_venue_err) && empty($event_speaker_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO create_event (event_name, event_date, event_start_time, event_end_time, event_venue, event_speaker) VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssss", $param_event_name, $param_event_date, $param_event_start_time, 
            $param_event_end_time, $param_event_venue, $param_event_speaker);


            // Set parameters
            $param_event_name = $event_name;
            $param_event_date = $event_date;
            $param_event_start_time = $event_start_time;
            $param_event_end_time = $event_end_time;
            $param_event_venue = $event_venue;
            $param_event_speaker = $event_speaker;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
                header("location: index.html");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Create New Event</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style-analytics.css" rel="stylesheet">

  <!-- QRCode.js Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>
  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center light-background sticky-top">
    <div class="container-fluid position-relative d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="scpeslogo.png" alt="">
        <span class="d-none d-lg-block">SCPES</span>
      </a>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul>
        <li><a href="index.html" class="active">Dashboard</a></li>
        <li><a href="results.html">Results</a></li>
      </ul>
  </nav>

  <div>  
    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
      <img src="uelogo.png" alt="Profile" class="rounded-circle" style="max-height: 36px;">
      <span class="d-none d-md-block dropdown-toggle ps-2">Admin</span>
    </a><!-- End Profile Iamge Icon -->

    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
      <a  href="pages-login.html" class="dropdown-item d-flex align-items-center" href="#">
        <i class="bi bi-box-arrow-right"></i>
        <span>Sign Out</span>
      </a>
    </ul><!-- End Profile Dropdown Items -->
    
  </div>

</header><!-- End Header -->


  <main id="main" class="main" style="margin: 10px;">

    <div class="pagetitle">
      <h1 style="font-size: 35px;">Create New Event</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
          <li class="breadcrumb-item active">Create event</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Vertical Form -->
    <form class="row g-3 needs-validation" id="eventForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="col-12">
        <label for="id_event_name" class="form-label">Event Name</label>
        <input type="text" class="form-control <?php echo (!empty($event_name_err)) ? 'is-invalid' : ''; ?>" name="id_event_name" style="text-transform: capitalize" value="<?php echo $event_name; ?>" required>
        <div class="invalid-feedback">
          <?php echo $event_name_err; ?>
        </div>
      </div>
      <div class="col-12">
        <label for="id_event_date" class="form-label">Event Date</label>
        <input type="date" class="form-control <?php echo (!empty($event_date_err)) ? 'is-invalid' : ''; ?>" name="id_event_date" value="<?php echo $event_date; ?>" required>
        <div class="invalid-feedback">
        <?php echo $event_date_err; ?>
        </div>
      </div>
      <div class="row" style="margin: 0px; padding: 0px;">
        <div class="col">
          <label for="id_event_start_time" class="form-label">Start Time</label>
          <input type="time" class="form-control <?php echo (!empty($event_start_time_err)) ? 'is-invalid' : ''; ?>" name="id_event_start_time" value="<?php echo $event_start_time; ?>" required>
          <div class="invalid-feedback">
          <?php echo $event_start_time_err; ?>
          </div>
        </div>
        <div class="col">
          <label for="id_event_end_time" class="form-label">End Time</label>
          <input type="time" class="form-control <?php echo (!empty($event_end_time_err)) ? 'is-invalid' : ''; ?>" name="id_event_end_time" value="<?php echo $event_end_time; ?>" required>
          <div class="invalid-feedback">
          <?php echo $event_end_time_err; ?>
        </div>
        </div>
      </div>

      <div class="col-12">
        <label for="id_event_venue" class="form-label">Event Venue</label>
        <input type="text" class="form-control <?php echo (!empty($event_venue_err)) ? 'is-invalid' : ''; ?>" name="id_event_venue" style="text-transform: capitalize" value="<?php echo $event_venue; ?>" required>
        <div class="invalid-feedback">
        <?php echo $event_venue_err; ?>
      </div>
      </div>
      <div class="col-12">
        <label for="id_event_speaker" class="form-label">Event Speaker/s</label>
        <input type="text" class="form-control <?php echo (!empty($event_speaker_err)) ? 'is-invalid' : ''; ?>" name="id_event_speaker" style="text-transform: capitalize" value="<?php echo $event_speaker; ?>" required>
        <div class="invalid-feedback">
        <?php echo $event_speaker_err; ?>
        </div>
      </div>
      <div class="text-center" style="margin-top: 40px;">
        <button type="submit" class="btn btn-primary" style="margin: 5px;">Submit</button>
        <button type="reset" class="btn btn-secondary" style="margin: 5px;">Reset</button>
        <a href="index.html" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
    <!-- Vertical Form -->

    <!-- Modal -->
    <!-- <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #03C03C; color: white;">
            <h5 class="modal-title" id="createEventModalLabel" style="font-weight: bold;">Event Created Successfully!</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="qrCode" class="text-center mb-3" style="max-width: fit-content;
            margin-left: auto;
            margin-right: auto;"></div>
            <h6 style="text-align: center; font-size: 15px; font-weight: 600;">Event Details:</h6>
            <p><strong>Name:</strong> <span id="modalEventName"></span></p>
            <p><strong>Date:</strong> <span id="modalEventDate"></span></p>
            <p><strong>Venue:</strong> <span id="modalEventVenue"></span></p>
            <p><strong>Speaker/s:</strong> <span id="modalEventSpeaker"></span></p>
            <p><strong></strong></p>
          </div>
          <div class="modal-footer">
            <button type="button" id="saveQrBtn" class="btn btn-success">Save QR Code</button>
            <button type="button" id="deleteEventBtn" class="btn btn-danger">Delete Event</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>



          </div>
        </div>
      </div>
    </div> -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>project-Wolfgang Developers</span></strong>. All Rights Reserved
    </div>
    <!-- <div class="credits">

      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div> -->
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>


  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

  <!-- <script>
    // Get the modal
    var modal = document.getElementById("createEventModal");
  
    // Get the button that opens the modal
    var btn = document.getElementById("openModalBtn");
  
    // Get the <span> element that closes the modal
  
  
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script> -->


</body>

</html>