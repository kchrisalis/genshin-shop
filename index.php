<?php

include("support/db_config.php");

$sql = 'SELECT pID, pName, price, details FROM products ORDER BY pID';
$results = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($results, MYSQLI_ASSOC);
mysqli_free_result($results);

if (isset($_SESSION['userID']) && $_SESSION['userID'] != 'Admin') {
    for($n =0; $n < count($products); $n++) {
        for($i = 0; $i < count($userCart); $i++) {
            if ($userCart[$i]['pID'] == $products[$n]['pID']) :
                $products[$n]['cart'] = true;
            else: 
                $product[$n]['cart'] = false;
            endif;
        }
    }
}

if (isset($_POST['addToCart'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $qty = mysqli_real_escape_string($conn, $_POST['quantity']);
    $product = "{$id}:{$qty}";
    
    if (empty($EmptyTestArray)) {
        // prevents an 'empty product'
        $cart = $product;
    } else {
        array_push($items, $product);
        $cart = join(' ', $items);
    }

    $sql = "UPDATE users SET cart = '$cart' WHERE username = '$user'";

    if (mysqli_query($conn, $sql)) {
        $id = $qty = "";
        // success;
        header('Location: userCart.php');
    } else {
        //error
        echo 'query error: ' . mysqli_error($conn);
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

<div class="border p-3">
    <h1 class="mx-5 mx-auto my-5" style="width: 300px;">Browse Products</h1>
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center">
            <?php

            foreach ($products as $product) :
            ?>
                <form action="index.php" method="post">
                    <div class="mx-1 my-1 border p-3 productCard" style="width: 380px;">
                        <h3><?php echo $product['pName'] ?></h3>
                        <input type="hidden" name="productName" value="<?php echo $product['pName'] ?>">

                        <p><strong>Price: </strong>$<?php echo $product['price'] ?></p>
                        <input type="hidden" name="price" value="<?php echo $product['price'] ?>">

                        <h5>Product Details</h5>
                        <p><?php echo $product['details'] ?></p>
                        <?php if (isset($_SESSION['userID']) && $_SESSION['userID'] != 'Admin') :
                            if ($product['cart'] == true) :
                        ?>
                                <p><strong>Product is already in cart</strong></p>
                            <?php elseif ($product['cart'] == false) : ?>
                                <input type="number" name="quantity" min=0 value=1>
                                <input type="hidden" name="id" value="<?php echo $product['pID'] ?>">
                                <input type="submit" value="Add To Cart" name="addToCart" class="brand btn">
                        <?php
                            endif;
                        endif; ?>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include("footer.php");

?>

</html>