<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid Credentials'); window.location='login.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <style>
    /* Root Variables for Colors */
:root {
  --primary-color: #2c7a51;
  --primary-dark: #1e5c3a;
  --primary-light: #e8f5ee;
  --accent-color: #f8b500;
  --text-dark: #333333;
  --text-light: #666666;
  --text-lighter: #999999;
  --white: #ffffff;
  --off-white: #f9f9f9;
  --light-gray: #f0f5f9;
  --border-color: #e0e0e0;
  --error-color: #e53935;
  --success-color: #43a047;
  --back:#b7e4c7;
}

/* Global Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background-color: var(--back);
  background-image: url('../images/farm-background.jpg');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}

body::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

/* Login Container */
.login-container {
  width: 100%;
  max-width: 450px;
  padding: 20px;
  position: relative;
  z-index: 2;
}

.login-card {
  background-color: var(--white);
  border-radius: 16px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  padding: 40px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.login-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 5px;
  background: linear-gradient(to right, var(--primary-color), var(--accent-color));
}

/* Logo and Header */
.logo {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 30px;
}

.logo img {
  width: 90px;
  height: 90px;
  margin-bottom: 15px;
  filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
}

.logo h1 {
  font-size: 1.8rem;
  color: var(--primary-color);
  text-align: center;
  font-weight: 600;
  letter-spacing: -0.5px;
}

h2 {
  color: var(--text-dark);
  margin-bottom: 30px;
  text-align: center;
  font-size: 1.5rem;
  font-weight: 500;
  position: relative;
}

h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 50px;
  height: 3px;
  background-color: var(--primary-color);
  border-radius: 3px;
}

/* Form Styles */
.input-group {
  margin-bottom: 25px;
  position: relative;
}

label {
  display: block;
  margin-bottom: 8px;
  color: var(--text-dark);
  font-weight: 500;
  font-size: 0.95rem;
}

.input-with-icon {
  position: relative;
}

.input-with-icon i {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--text-lighter);
  font-size: 1.2rem;
  transition: color 0.3s;
}

.input-with-icon input:focus + i {
  color: var(--primary-color);
}

input {
  width: 100%;
  padding: 15px 15px 15px 50px;
  border: 1px solid var(--border-color);
  border-radius: 10px;
  font-size: 1rem;
  transition: all 0.3s;
  background-color: var(--off-white);
}

input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(44, 122, 81, 0.15);
  background-color: var(--white);
}

button {
  width: 100%;
  background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
  color: var(--white);
  border: none;
  border-radius: 10px;
  padding: 16px;
  font-size: 1.05rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(44, 122, 81, 0.25);
  position: relative;
  overflow: hidden;
  letter-spacing: 0.5px;
}

button:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(44, 122, 81, 0.3);
}

button:active {
  transform: translateY(0);
}

/* Form Footer */
.form-footer {
  margin-top: 25px;
  text-align: center;
}

.form-footer a {
  color: var(--primary-color);
  text-decoration: none;
  font-size: 0.95rem;
  transition: color 0.3s;
  font-weight: 500;
}

.form-footer a:hover {
  color: var(--primary-dark);
  text-decoration: underline;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.login-card {
  animation: fadeIn 0.6s ease-out forwards;
}

/* Responsive Styles */
@media (max-width: 480px) {
  .login-container {
    padding: 15px;
  }

  .login-card {
    padding: 30px 20px;
  }

  .logo h1 {
    font-size: 1.5rem;
  }
  
  .logo img {
    width: 70px;
    height: 70px;
  }
  
  h2 {
    font-size: 1.3rem;
  }
  
  input {
    padding: 14px 14px 14px 45px;
  }
  
  button {
    padding: 14px;
  }
}
  </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dairy Farm Shop Management</title>
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <img src="cow.png" alt="Dairy Farm Logo">
                <h1>Dairy Farm Shop Management</h1>
            </div>
            <h2>Welcome Back</h2>
            <form method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <button type="submit">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
                <div class="form-footer">
                    <a href="#">Forgot password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>