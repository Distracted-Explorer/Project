<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <label for="switch" class="switch">
        <input id="switch" type="checkbox" />
        <span class="slider"></span>
        <span class="decoration"></span>
    </label>
    
    <?php
        if (isset($_POST["submit"])) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $passwordRepeat = $_POST["password_2"];
            
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
            if (empty($username) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
                array_push($errors,"All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password)<8) {
                array_push($errors,"Password must be at least 8 charactes long");
            }
            if ($password!==$passwordRepeat) {
                array_push($errors,"Password does not match");
            }
            require_once "database.php";
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $rowCount = mysqli_num_rows($result);
            if ($rowCount>0) {
                array_push($errors,"Email already exists!");
            }
            if (count($errors)>0) {
                foreach ($errors as  $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }else{
            
                $sql = "INSERT INTO user (username, email, password) VALUES ( ?, ?, ? )";
                $stmt = mysqli_stmt_init($conn);
                $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
                if ($prepareStmt) {
                    mysqli_stmt_bind_param($stmt,"sss",$username, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfully.</div>";
                }
                else{
                    die("Something went wrong");
                }
            }
        }
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM user WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    header("Location: flappybird/flappy.html");
                    die();
                }
                else{
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            }
            else{
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        }
    ?>


    </div>
    <div class="box">
        <div class="button_box">
            <div class="slider_button"></div>
            <button class="signup_button">Sign Up</button>
            <button class="login_button">Login</button>
        </div>

        <div class="form_box">
            <div class="form_slider">
                
                <form action="index.php" method="post"  class="signup_form">

                    <div class="input_field_box">

                        <div class="input_box">
                            <input type="text" required name="username">
                            <label>Username</label>
                        </div>
                        <div class="input_box">
                            <input type="email" required name="email" placeholder="Email id">
                        </div>
                        <div class="input_box">
                            <input type="password" required name="password">
                            <label>Create Password</label>
                        </div>
                        <div class="input_box">
                            <input type="text" required name="password_2">
                            <label>Confirm Password</label>
                        </div>
                    </div>


                    <button type="submit" name="submit">Create Account</button>
                </form>

                

                <form action="index.php" method="post" class="login_form">

                    <div class="input_box">
                        <input type="email" required name="email" placeholder="Email id">
                    </div>
                    <div class="input_box">
                        <input type="password" required name="password">
                        <label>Password</label>
                    </div>


                    <p class="password_link"><a href="flappybird/flappy.html">Forgot Password ?</a></p>
                    <button type="submit" name="login">Login</button>

                </form>

    </div>
        </div>

    </div>
    <script>
        let slider_button = document.querySelector(".slider_button");
        let signup_button = document.querySelector(".signup_button");
        let login_button = document.querySelector(".login_button");
        let form_slider = document.querySelector(".form_slider");
        const toggleSwitch = document.querySelector('.switch input');

        // Function to check if dark mode is active
        function isDarkMode() {
            return document.body.classList.contains('dark-mode');
        }

        // Function to update button colors based on dark mode
        function updateButtonColors() {
            if (isDarkMode()) {
                signup_button.style.color = "#fff";
                login_button.style.color = "#fff";
            } else {
                signup_button.style.color = "#fff";
                login_button.style.color = "#000";
            }
        }

        // Function to add or remove the 'inactive' class
        function toggleButtonState(activeButton, inactiveButton) {
            activeButton.classList.remove('inactive');
            inactiveButton.classList.add('inactive');
        }

        // Initialize button states on page load
        function initializeButtonState() {
            // Set initial state for signup button to active and login button to inactive
            toggleButtonState(signup_button, login_button); // Signup is active by default
            updateButtonColors();
        }

        // Handle login button click
        login_button.onclick = function () {
            slider_button.style.left = "50%";
            login_button.style.color = "#fff";
            signup_button.style.color = isDarkMode() ? "#fff" : "#000";
            form_slider.style.left = "-100%";

            // Toggle button borders
            toggleButtonState(login_button, signup_button);
        }

        // Handle signup button click
        signup_button.onclick = function () {
            slider_button.style.left = "0%";
            signup_button.style.color = "#fff";
            login_button.style.color = isDarkMode() ? "#fff" : "#000";
            form_slider.style.left = "0%";

            // Toggle button borders
            toggleButtonState(signup_button, login_button);
        }

        // Dark mode toggle functionality
        toggleSwitch.addEventListener('change', () => {
            document.body.classList.toggle('dark-mode');
            updateButtonColors();  // Update button colors when dark mode is toggled
        });

// Initialize button state and colors when the page loads
window.onload = initializeButtonState;


    </script>
</body>
</html>