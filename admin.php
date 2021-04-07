<?php

include("support/db_config.php");

$pName = $price = $details = "";
$errors = ['name' => "", 'price' => "", 'details' => ""];
$products = "";

// write query 
$sql = 'SELECT pID, pName, price, details FROM products ORDER BY pID';
$results = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($results, MYSQLI_ASSOC);
mysqli_free_result($results);


if (isset($_POST['addProduct'])) {
    // check product name
    if (empty($_POST['pName'])) {
        $errors['name'] =  'Enter product name';
    } else {
        $pName = $_POST['pName'];
        $sql = "SELECT pName FROM products WHERE pName='$pName'";
        $duplicate = mysqli_query($conn, $sql);

        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $pName)) {
            $errors['name'] = 'Invalid product name.';
        } else if (mysqli_num_rows($duplicate) != 0) {
            $errors['name'] = 'Product already exists';
        }
        mysqli_free_result($duplicate);
    }

    // check price
    if (empty($_POST['price'])) {
        $errors['price'] =  'Enter a price';
    } else {
        $price = $_POST['price'];
        if (!preg_match('/^[0-9]{1,4}$/', $price)) {
            $errors['price'] = 'Enter a valid price (no more than 9999)';
        }
    }

    // check product details
    if (empty($_POST['details'])) {
        $errors['details'] = 'Enter product details';
    } else {
        $details = $_POST['details'];
        if (!preg_match('/^[a-zA-Z0-9 ]+$/', $details)) {
            $errors['details'] = 'Invalid details, try again';
        }
    }

    if (array_filter($errors)) {
    } else if (isset($_POST['pName']) && isset($_POST['price']) && isset($_POST['details'])) {
        // adding product to the database
        $pName = mysqli_real_escape_string($conn, $_POST['pName']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $details = mysqli_real_escape_string($conn, $_POST['details']);

        // create query
        $sql = "INSERT INTO products(pName,price,details) VALUES('$pName', '$price', '$details')";

        // save to database and check
        if (mysqli_query($conn, $sql)) {
            // success
            $pName = $price = $details = "";
        } else {
            //error
            echo 'query error: ' . mysqli_error($conn);
        }
    }

}

mysqli_close($conn);


?>


<!DOCTYPE html>
<html>

<head>
    <title>Genshin Shop</title>
</head>

<?php include("header.php") ?>

<h1 class="mx-5 mx-auto my-4">Admin Management Page</h1>
<div class="border d-flex">
    <div class="border w-50 p-3">
        <form class="border form" action="admin.php" method="POST">
            <h2 class="mb-3">New Product</h2>

            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" class="form-control" name="pName" value="<?php echo htmlspecialchars($pName) ?>">
                <p class="text-danger"><small><?php echo $errors['name'] ?></small></p>
            </div>

            <div class=mb-3>
                <label for="username">Price ($)</label>
                <input type="number" name="price" class="form-control" min="0" value="<?php echo htmlspecialchars($price) ?>">
                <p class='text-danger'><small><?php echo $errors['price'] ?></small></p>
            </div>

            <div class=mb-3>
                <label for="password">Details</label>
                <input type="text" class="form-control" name="details" value="<?php echo htmlspecialchars($details) ?>">
                <p class="text-danger"><small><?php echo $errors['details'] ?></small></p>
            </div>

            <div class="mb-3">
                <input type="submit" name="addProduct" value="Add Product" class="btn brand z-depth-0">
            </div>
        </form>
    </div>


    <!-- Adding Product Form -->
    <div class="border w-75 p-3">
        <h1 class="mx-auto my-3">Products</h1>
        <div class="container">
            <div class="d-flex flex-wrap">
                <?php foreach ($products as $product) : ?>
                        <div class="productCard mx-1 my-1 border p-3" style="width: 259px;">
                            <h3><?php echo $product['pName'] ?></h3>
                            <p><strong>Price: </strong>$<?php echo $product['price'] ?></p>
                            <h5>Product Details</h5>
                            <p><?php echo $product['details'] ?></p>
                            <p>
                            <form action="admin.php" method="post">
                                <a href="manageProduct.php?id=<?php echo $product['pID'] ?>">Edit</a>
                            </form>
                            </p>
                        </div>
                <?php endforeach;
                ?>

            </div>
        </div>
    </div>
</div>

<?php include('footer.php')?>
</html>