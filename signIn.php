<?php


include("support/class-sheet.php");
include("support/db_config.php");
$username = $password = "";
$errors = ['username' => "", 'password' => "", 'login'=>""];

if(isset($_POST['submit'])) {

    // checking for empty fields
    if (empty($_POST['username']) || empty($_POST['password'])) {
        // check username field
        if (empty($_POST['username'])) {
            $errors['username'] = 'Please enter a username';
        } else {
            $username = $_POST['username'];
        }

        // check password field
        if(empty($_POST['password'])) {
            $errors['password'] = 'Please enter a password';
        } else {
            $password = $_POST['password'];
        }
      
      // check if user is an admin  
    } else if($_POST['username'] == "GSAdmin" && $_POST['password'] == "admin231") {
        $_SESSION['userID'] = 'Admin';
        header('location: admin.php');
      
      // check if user exists  
    } else if(isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // get username and password from database to check
        $sql = "SELECT username, passW FROM users WHERE username='$username' and passW='$password'";
        $result = mysqli_query($conn, $sql);

        // checking using rows
        if(mysqli_num_rows($result)==1) {
            echo 'Success';

            //initialize 'userID' for reference during session
            $_SESSION['userID'] = $username;
            header('location: index.php');

        } else{
            $error['login'] = 'Username and password do not match. Please try again';
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Genshin Shop</title>
</head>

<?php include("header.php") ?>

<div>
    <!-- Sign In Form -->
    <form class="border form" action="signIn.php" method="POST">
        <h2 class="mb-3">Sign In</h2>

        <!-- username -->
        <div class=mb-3>
            <label for="username">Username </label>
            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($username) ?>">
            <p class="text-danger"><small><?php echo $errors['username'] ?></small></p>
        </div>

        <!-- password -->
        <div class=mb-3>
            <label for="password">Password </label>
            <input type="text" class="form-control" name="password" value="<?php echo htmlspecialchars($password) ?>">
            <p class="text-danger"><small><?php echo $errors['password'] ?></small></p>
        </div>

        <!-- other login error messages -->
        <p class="text-danger"><small><?php echo $error['login']?></small></p>

        <!-- log in -->
        <div class="mb-3">
            <input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
        </div>

        <p>New to the shop? Sign up <a href="signup.php">here</a></p>
    </form>
</div>

<?php include("footer.php") ?>
</html>