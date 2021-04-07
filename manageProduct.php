<?php

include('support/db_config.php');

// display product
$product = "";
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // make sql
    $sql = "SELECT pName, price, details, pID FROM products WHERE pID = $id";

    // get the query result
    $result = mysqli_query($conn, $sql);

    // fetch result in array format
    $product = mysqli_fetch_assoc($result);

    mysqli_free_result($result);
    mysqli_close($conn);
}

// delete product
if (isset($_POST['deleteProduct'])) {
    $id_to_delete = mysqli_real_escape_string($conn, $_POST['id']);

    $sql = "DELETE FROM products WHERE pID = $id_to_delete";

    if (mysqli_query($conn, $sql)) {
        header('location: admin.php');
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

// edit product
$pName = $price = $details = "";
if (isset($_POST['saveChanges'])) {
    // unable to do form validation because my page keeps refreshing

    $pName = mysqli_real_escape_string($conn, $_POST['pName']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $details = mysqli_real_escape_string($conn, $_POST['details']);
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $sql = "UPDATE products SET pName='$pName', price='$price', details='$details' WHERE pId=$id";

    if (mysqli_query($conn, $sql)) {
        // success
        header('location: admin.php');
    } else {
        echo 'query error: ' . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Genshin Shop</title>
</head>

<?php include("header.php") ?>

<div class="p-3">

    <form class="border form" action="manageProduct.php" method="POST">
        <h2 class="mb-3">Edit Product</h2>
        <?php if ($product): ?>
        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" class="form-control" name="pName" value="<?php echo htmlspecialchars($product['pName']) ?>">
            <p class="text-danger"><small><?php echo $errors['name'] ?></small></p>
        </div>

        <div class=mb-3>
            <label for="username">Price</label>
            <input type="number" name="price" class="form-control" min="0" value="<?php echo htmlspecialchars($product['price']) ?>">
            <p class='text-danger'><small><?php echo $errors['price'] ?></small></p>
        </div>

        <div class=mb-3>
            <label for="password">Details</label>
            <input type="text" class="form-control" name="details" value="<?php echo htmlspecialchars($product['details']) ?>">
            <p class="text-danger"><small><?php echo $errors['details'] ?></small></p>
        </div>

        <div class="mb-3">
            <input type="hidden" name="id" value="<?php echo $product['pID'] ?>">
            <input type="submit" value="Save Changes" class="btn brand z-depth-0" name="saveChanges">
            <input type="submit" value="Delete Product" class="btn brand z-depth-0" name="deleteProduct">
        </div>
<?php endif;?>
    </form>
</div>

<?php include('footer.php'); ?>

</html>