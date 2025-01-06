<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>admin_or_student</title>
  <link rel="stylesheet" type="text/css" href="../CSS/admin_or_student.css">
  <link rel="stylesheet" type="text/css" href="../CSS/Loading_screen.css"> <!-- Link to the loading screen CSS -->
</head>
<body>
  <!-- Loading screen element -->
  <div class="loading-overlay" id="loading-screen">
    <div class="loader"></div>
  </div>

  <div class="secondContainer">
    <div class="child">
      <div class="backBtn-div">
        <a class="backBtn" href="start.php" onclick="showLoadingScreen()">X</a>
      </div>
      <div class="btn-div">
        <p class="text1">Are you?</p>
        <a class="btn" href="register_process.php" onclick="showLoadingScreen()">EMPLOYEE</a>
        <a class="btn" href="adminLogin.php" onclick="showLoadingScreen()">ADMIN</a>
      </div>
    </div>
  </div> 

  <script src="../JavaScripts/loadingScreen.js"></script>
</body>
</html>
