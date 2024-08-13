<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login-container img {
            width: 150px;
            margin-bottom: 20px;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"],
        .login-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .login-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="magento_logo.svg" alt="Magento Logo">
        <h2>User Login</h2>
        <form id="loginForm">
            <select id="userType" name="userType" required>
                <option value="" disabled selected>Select User Type</option>
                <option value="superUser">Super User</option>
                <option value="adminUser">Admin User</option>
                <option value="regularUser">Regular User</option>
            </select>
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <a id="forgotPassword" href="#">Forgot Password?</a>
        </form>
    </div>

    <script>
        document.getElementById('userType').addEventListener('change', function() {
            const userType = this.value;
            const forgotPasswordLink = document.getElementById('forgotPassword');
            const loginForm = document.getElementById('loginForm');

            if (userType === 'superUser') {
                forgotPasswordLink.href = 'super-user-forgot-password-url';
                loginForm.action = 'super-user-login-url';
            } else if (userType === 'adminUser') {
                forgotPasswordLink.href = 'admin-user-forgot-password-url';
                loginForm.action = 'admin-user-login-url';
            } else if (userType === 'regularUser') {
                forgotPasswordLink.href = 'regular-user-forgot-password-url';
                loginForm.action = 'regular-user-login-url';
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(form)),
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.token) {
                      // Handle successful login
                      console.log('Login successful:', data);
                  } else {
                      // Handle login error
                      console.error('Login failed:', data);
                  }
              });
        });
    </script>
</body>
</html>
