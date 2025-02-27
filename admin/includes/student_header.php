<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Student Assistant System</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js"></script>
    <script>
    // Handle back button navigation
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Page was loaded from back/forward cache
            window.location.reload();
        }
    });
    </script>
</head>

<body class="sb-nav-fixed">

    <?php include('includes/student_navbar-top.php'); ?>

    <div id="layoutSidenav">

        <?php include('includes/student_sidebar.php'); ?>

        <div id="layoutSidenav_content">
            <main>