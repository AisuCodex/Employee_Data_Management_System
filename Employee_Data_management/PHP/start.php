ewawww<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Data Management</title>
    <link rel="stylesheet" href="../CSS/StarT.css">
    <link rel="stylesheet" href="../CSS/Loading_screen.css"> <!-- Link to the new CSS file -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #2a3417;">
    <!-- Loading screen element -->
    <div class="loading-overlay" id="loading-screen">
        <div class="loader"></div>
    </div>

    <header class="headerContent" id="headerContent">
        <div class="header-content">
            <!-- <img src="../img/orangeLogo.png" alt="Logo 1" class="logo"> -->
            <h2><b>EMPLOYEE DATA MANAGEMENT</b></h2>
            <!-- <img src="../img/greenLogo.png" alt="Logo 2" class="logo"> -->
        </div>
    </header>
    <main class="main-content">
        <div class="caption-container">
            <h1 class="caption">Welcome to <br> Employee Data <br>Management System</h1>
            <div class="start-container">
              <a class="start_btn" href="../PHP/adminLogin.php" onclick="showLoadingScreen()">Get Started</a>
            </div>
        </div>
    </main> 

    <script src="../JavaScripts/loadingScreen.js"></script>
</body>
</html>
