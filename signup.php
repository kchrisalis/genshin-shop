<?php

include("support/db_config.php");

$email = $username = $password = "";
$errors = ['email' => "", 'username' => "", 'password' => ""];

if (isset($_POST['submit'])) {

    // check email
    if (empty($_POST['email'])) {
        $errors['email'] = 'An email is required';
    } else {
        $email = $_POST['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email must be a valid email address";
        }
    }

    // check username
    if (empty($_POST['username'])) {
        $errors['username'] = 'An username is required';
    } else {
        $username = $_POST['username'];
        $sql = "SELECT username FROM users WHERE username='$username'";
        $duplicate = mysqli_query($conn, $sql);

        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $username)){
            $errors['username'] = 'Invalid username.';
        } else if (mysqli_num_rows($duplicate) != 0){
            $errors['username'] = 'Username is taken';
        }
        mysqli_free_result($duplicate);

    }

    // check password
    if (empty($_POST['password'])) {
        $errors['password'] = 'An password is required';
    } else {
        $password = $_POST['password'];
        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $password)){
            $errors['password'] = 'Invalid password. Alphanumeric characters only';
        }
    }

    if (array_filter($errors)) {
	} else {
        // add user to the database
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);

		// create query
		$sql = "INSERT INTO users(email,username,passW) VALUES('$email', '$username', '$password')";

		// save to database and check
		if(mysqli_query($conn, $sql)) {
            $email = $username = $password = "";
			// success;
			header('Location: signIn.php');
		} else {
			//error
			echo 'query error: ' . mysqli_error($conn);
		}	
	}

    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html>
<?php include("header.php") ?>

<head>
    <title>Create An Account</title>
</head>


<div>
    <!-- Create Account Form -->
    <form class="border form" action="signup.php" method="POST">
        <h2 class="mb-3">Create An Account</h2>

        <!-- Email -->
        <div class="mb-3">
            <label>Email</label>
            <input type="text" class="form-control" name="email" placeholder="example@website.com"value="<?php echo htmlspecialchars($email) ?>">
            <p class="text-danger"><small><?php echo $errors['email'] ?></small></p>
        </div>

        <!-- Username -->
        <div class=mb-3>
            <label for="username">Username </label>
            <input type="text" class="form-control" name="username" placeholder="Must be 8-20 alphanumeric characters"value="<?php echo htmlspecialchars($username) ?>">
            <p class='text-danger'><small><?php echo $errors['username']?></small></p>
        </div>

        <!-- Password -->
        <div class=mb-3>
            <label for="password">Password </label>
            <input type="text" class="form-control" name="password" placeholder="Must be 8-20 alphanumeric characters"value="<?php echo htmlspecialchars($password) ?>">
            <p class="text-danger"><small><?php echo $errors['password'] ?></small></p>
        </div>

        <!-- Add User -->
        <div class="mb-3">
            <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
        </div>
    </form>

</div>
<?php include("footer.php") ?>

</html>