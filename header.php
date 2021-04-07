<?php
?>

<head>
    <!-- Bootstrap Component -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- style sheet -->
    <style type="text/css">
        .form {
            max-width: 460px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.500);

        }

        .brand {
            background-color: #79a89b;
            color: white;
        }

        .bg {
            background-color: #a7c9c0;
        }

        .productCard {
            background-color: rgba(255, 255, 255, 0.500);
        }

        .updateCart {
            color: #e6ebf0;
            text-decoration: underline;
            outline: 0;
            border: none;
        }

    </style>
</head>

<body class="bg">
    <!-- Nav Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex sticky-top justify-content-lg-between px-4">

        <a class="h3 text-white mx-3" href="index.php">Genshin Shop</a>

        <!-- Nav Bar Dropdown-->
        <?php
        
        if (isset($_SESSION['userID'])) : 
            $userNavBar = $_SESSION['userID'];
            if ($userNavBar != 'Admin') : ?>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo "Welcome, " . $_SESSION['userID']; ?></a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="userCart.php">Shopping Cart</a>
                    <a class="dropdown-item" href="#">View Orders</a>
                    <a class="dropdown-item" href="endSession.php">Log Out</a>
                </div>
            </div>

        <?php elseif ($userNavBar == 'Admin') : ?>
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo "Welcome, " . $_SESSION['userID']; ?></a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="admin.php">Manage Products</a>
                    <a class="dropdown-item" href="#">Manage Orders</a>
                    <a class="dropdown-item" href="endSession.php">Log Out</a>
                </div>
            <?php endif;?>
        <?php else: ?>
            <a class="btn btn-primary mx-3" href="signIn.php" role="button">Sign In</a>
            <?php endif; ?>

    </nav>