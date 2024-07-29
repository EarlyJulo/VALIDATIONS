<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Registration Form</title>
    <style>
        .body {
            background-color: goldenrod;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 30px 20px rgba(22, 22, 22, 0.1);
            padding: 20px;
            background-color: #ffffff;
            border: 2px solid #007bff;
            border-radius: 15px;
            background-color: #eceaf3;
        }
        .highlight-text {
            font-weight: bold;
            color: #ffc107;
        }
        .error {
            border-color: red;
        }
        .success {
            border-color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center highlight-text">Registration Form</h1>
        
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once "database.php";

            $error_message = ""; // Initialize error message variable

            // Retrieve form data
            $fullname = $_POST['fullname'];
            $gender = $_POST['gender'];
            $age = $_POST['age'];
            $password = $_POST['password'];
            $passwordRepeat = $_POST['repeat_password'];

            $errors = array();

            // Regular expressions
            $nameRegex = "/^[a-zA-Z\s'.-]+$/";
            $ageRegex = "/^\d{1,3}$/";

            // Validate form data
            if (empty($fullname) || empty($gender) || empty($age) || empty($password) || empty($passwordRepeat)) {
                array_push($errors, "All fields are required");
            }
            if (!preg_match($nameRegex, $fullname)) {
                array_push($errors, "Fullname can only contain letters, spaces, hyphens, apostrophes, and periods.");
            }
            if (!preg_match($ageRegex, $age)) {
                array_push($errors, "Age must be a valid integer with up to 3 digits and no decimals.");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long");
            }
            if ($password !== $passwordRepeat) {
                array_push($errors, "Passwords do not match");
            }

            if (count($errors) === 0) {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert data into the database
                $sql = "INSERT INTO users (full_name, gender, age, password) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssis", $fullname, $gender, $age, $hashed_password);

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>You are successfully registered.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
                }

                $stmt->close();
                $conn->close();
            } else {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
        }
        ?>
        
        <form id="form" action="" method="POST">
            <div class="form-group mb-3">
                <input type="text" id="fullname" class="form-control" name="fullname" placeholder="Full Name" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group mb-3">
                <select id="gender" class="form-control" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <span class="error-message"></span>
            </div>     
            <div class="form-group mb-3">
                <input type="text" id="age" class="form-control" name="age" placeholder="Age" required>
                <span class="error-message"></span>
            </div>       
            <div class="form-group mb-3">
                <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
                <span class="error-message"></span>
            </div>
            <div class="form-group mb-3">
                <input type="password" id="passwordRepeat" class="form-control" name="repeat_password" placeholder="Confirm Password" required>
                <span class="error-message"></span>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
    </div>
    <script>
        const form = document.getElementById('form');
        const fullname = document.getElementById('fullname');
        const gender = document.getElementById('gender');
        const age = document.getElementById('age');
        const password = document.getElementById('password');
        const passwordRepeat = document.getElementById('passwordRepeat');

        const setError = (element, message) => {
            const inputControl = element.parentElement;
            const errorDisplay = inputControl.querySelector('.error-message');

            errorDisplay.innerText = message;
            element.classList.add('error');
            element.classList.remove('success');
        }

        const setSuccess = element => {
            const inputControl = element.parentElement;
            const errorDisplay = inputControl.querySelector('.error-message');

            errorDisplay.innerText = '';
            element.classList.add('success');
            element.classList.remove('error');
        }

        const validateFullname = () => {
            const fullnameValue = fullname.value.trim();
            const nameRegex = /^[a-zA-Z\s'.-]+$/;

            if (fullnameValue === '') {
                setError(fullname, 'Fullname is required');
                return false;
            } else if (!nameRegex.test(fullnameValue)) {
                setError(fullname, 'Fullname can only contain letters, spaces, hyphens, apostrophes, and periods.');
                return false;
            } else {
                setSuccess(fullname);
                return true;
            }
        }

        const validateGender = () => {
            const genderValue = gender.value;

            if (genderValue === '') {
                setError(gender, 'Please select gender');
                return false;
            } else {
                setSuccess(gender);
                return true;
            }
        }

        const validateAge = () => {
            const ageValue = age.value.trim();
            const ageRegex = /^\d{1,3}$/;

            if (ageValue === '') {
                setError(age, 'Age is required');
                return false;
            } else if (!ageRegex.test(ageValue)) {
                setError(age, 'Age must be a valid integer with up to 3 digits and no decimals.');
                return false;
            } else {
                setSuccess(age);
                return true;
            }
        }

        const validatePassword = () => {
            const passwordValue = password.value.trim();

            if (passwordValue === '') {
                setError(password, 'Password is required');
                return false;
            } else if (passwordValue.length < 8) {
                setError(password, 'Password must be at least 8 characters long');
                return false;
            } else {
                setSuccess(password);
                return true;
            }
        }

        const validatePasswordRepeat = () => {
            const passwordValue = password.value.trim();
            const passwordRepeatValue = passwordRepeat.value.trim();

            if (passwordRepeatValue === '') {
                setError(passwordRepeat, 'Please confirm your password');
                return false;
            } else if (passwordRepeatValue !== passwordValue) {
                setError(passwordRepeat, 'Passwords do not match');
                return false;
            } else {
                setSuccess(passwordRepeat);
                return true;
            }
        }

        form.addEventListener('submit', e => {
            e.preventDefault();
            let isFormValid = validateFullname() & validateGender() & validateAge() & validatePassword() & validatePasswordRepeat();
            if (isFormValid) {
                form.submit();
            }
        });
    </script>
</body>
</html>
