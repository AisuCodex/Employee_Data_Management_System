<?php
include 'adminAuth.php';
include("../Employee_Database/config.php"); // Add database connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css">
  <link rel="stylesheet" href="../CSS/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
  <div class="loading-overlay" id="loading-screen">
    <div class="loader"></div>
  </div>
  <div class="allContainer">
    <div class="navbar">
      <div class="hamburger-menu" onclick="toggleMenu()">
        <div class="bar bar1"></div>
        <div class="bar bar2"></div>
        <div class="bar bar3"></div>
      </div>
      <div class="admin-page-text">
        <p>Admin Page</p>
      </div>
      <div class="nav-links">
<!--         <a href="changePassword.php" onclick="showLoadingScreen()">Change Pass <i class="fa-solid fa-key"></i></a> -->
        <a href="#" id="logout-link">Logout <i class="fa-solid fa-right-from-bracket"></i></a>
      </div>
    </div>

<!-- Side Menu for Mobile -->
<nav class="side-menu">
  <!-- <a href="changePassword.php" onclick="showLoadingScreen()">Change Password <i class="fa-solid fa-key"></i></a> -->
  <a href="#" id="logout-link-mobile">Logout <i class="fa-solid fa-right-from-bracket"></i></a> <!-- Added ID here -->
</nav>

    <!-- main content -->
    <div class="contain">
      <div class="container">

        <div class="card-container">
          <section id="capstone">
              <a class="box-link" onclick="showLoadingScreen()" href="../Employee_Database/index.php">
                  <div class="box">
                      <h2>Employee's Data</h2>
                      <p>Manage and view <br> Employee's Data here.</p>
                  </div>
              </a>
          </section>

          <section id="capstone">
          <a class="box-link" onclick="showLoadingScreen()" href="leaveRequests.php">
                  <div class="box">
                      <h2>Employee's Leave Request</h2>
                      <p>Manage and view <br> Employee's Leave Request here.</p>
                  </div>
              </a>
          </section>

          <section id="capstone">
          <a class="box-link" onclick="showLoadingScreen()" href="leaveStatistics.php">
                  <div class="box">
                      <h2>Leave Statistics</h2>
                      <p>View detailed <br> Leave Request Statistics.</p>
                  </div>
              </a>
          </section>

          <section id="capstone">
          <a class="box-link" onclick="showLoadingScreen()" href="manageAccounts.php">
                  <div class="box">
                      <h2>Employee's Accounts</h2>
                      <p>Manage and view <br> Employee's Accounts here.</p>
                  </div>
              </a>
          </section>

          <section id="capstone">
          <a class="box-link" onclick="showLoadingScreen()" href="adminApproval.php">
                  <div class="box">
                      <h2>Pending Approvals</h2>
                      <p>Manage and view <br> Pending Approvals here.</p>
                  </div>
              </a>
          </section>

          <section id="capstone">
          <a class="box-link" onclick="showLoadingScreen()" href="announcements.php">
                  <div class="box">
                      <h2>Announcements</h2>
                      <p>Create and Manage <br> Announcements here.</p>
                  </div>
              </a>
          </section>

          <section id="capstone">
          <a class="box-link" onclick="showLoadingScreen()" href="employeeLogs.php">
                  <div class="box">
                      <h2>Employee Logs</h2>
                      <p>View and Manage <br> Employee Logs here.</p>
                  </div>
              </a>
          </section>
        </div>

        <style>
            .dashboard-summary {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .summary-card {
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                text-align: center;
            }

            .summary-card i {
                font-size: 2em;
                color: #556B2F;
                margin-bottom: 10px;
            }

            .summary-card h3 {
                margin: 10px 0;
                color: #333;
            }

            .summary-card p {
                font-size: 2em;
                font-weight: bold;
                color: #556B2F;
                margin: 0;
            }

            .leave-statistics {
                background-color: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                margin-bottom: 30px;
            }

            .leave-statistics h2 {
                color: #556B2F;
                margin-bottom: 20px;
            }

            .statistics-table {
                overflow-x: auto;
            }

            .statistics-table table {
                width: 100%;
                border-collapse: collapse;
            }

            .statistics-table th,
            .statistics-table td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #eee;
            }

            .statistics-table th {
                background-color: #f8f9fa;
                color: #556B2F;
            }

            .statistics-table td.approved {
                color: #28a745;
            }

            .statistics-table td.pending {
                color: #ffc107;
            }

            .statistics-table td.rejected {
                color: #dc3545;
            }

            .statistics-table tr:hover {
                background-color: #f8f9fa;
            }
        </style>

  <!-- Logout Confirmation Modal -->
  <div id="logout-modal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Logout Confirmation</h2>
      <p>Are you sure you want to log out?</p>
      <div class="modal-buttons">
        <button class="modal-button confirm-button" id="confirm-logout">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
        <button class="modal-button cancel-button" id="cancel-logout">
          <i class="fas fa-times"></i> Cancel
        </button>
      </div>
    </div>
  </div>

  <script>
    // Logout Modal Functionality
    const modal = document.getElementById('logout-modal');
    const logoutLink = document.getElementById('logout-link');
    const logoutLinkMobile = document.getElementById('logout-link-mobile');
    const closeBtn = document.querySelector('.close');
    const confirmLogoutBtn = document.getElementById('confirm-logout');
    const cancelLogoutBtn = document.getElementById('cancel-logout');

    function showModal() {
      modal.style.display = 'block';
      setTimeout(() => {
        modal.classList.add('show');
      }, 10);
    }

    function hideModal() {
      modal.classList.remove('show');
      setTimeout(() => {
        modal.style.display = 'none';
      }, 300);
    }

    logoutLink.addEventListener('click', (e) => {
      e.preventDefault();
      showModal();
    });

    logoutLinkMobile.addEventListener('click', (e) => {
      e.preventDefault();
      showModal();
    });

    closeBtn.addEventListener('click', hideModal);
    cancelLogoutBtn.addEventListener('click', hideModal);

    confirmLogoutBtn.addEventListener('click', () => {
      window.location.href = '../PHP/logout.php';
    });

    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        hideModal();
      }
    });

    // Mobile Menu Toggle
    function toggleMenu() {
      const hamburger = document.querySelector('.hamburger-menu');
      const sideMenu = document.querySelector('.side-menu');
      hamburger.classList.toggle('active');
      sideMenu.classList.toggle('active');
    }

    // Loading Screen
    function showLoadingScreen() {
      document.getElementById('loading-screen').style.display = 'flex';
    }
  </script>
</body>
</html>
