<?php

include("support/db_config.php");

$errors = ['address' => "", 'phone' => "", 'payment' => "", 'cart' => ""];

// edit quantity
if (isset($_POST['editQty'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $qty = mysqli_real_escape_string($conn, $_POST['amount']);
    $inCart = [];

    print_r($userCart);

    for ($i = 0; $i < count($userCart); $i++) {
        if ($id === $userCart[$i]['pID']) {
            $userCart[$i]['qty'] = $qty;
        }
        $product = "{$userCart[$i]['pID']}:{$userCart[$i]['qty']}";
        array_push($inCart, $product);
    }
}

// delete product
if (isset($_POST['deleteCart'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id_delete']);
    $inCart = [];

    for ($i = 0; $i < count($userCart); $i++) {
        if ($id === $userCart[$i]['pID']) {
            // echo $userCart[$i]['pID'];
            array_splice($userCart, $i, 1);
        }
    }
    for ($i = 0; $i < count($userCart); $i++) {
        $product = "{$userCart[$i]['pID']}:{$userCart[$i]['qty']}";
        array_push($inCart, $product);
    }
    updateCart($inCart, $user, $conn);
}

if (isset($_POST['order'])) {
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);
    $inCart = [];

    // check if cart is empty
    if (!$userCart) {
        $errors['cart'] = "Cart is empty. Add to cart first.";

    } else {
        for ($i = 0; $i < count($userCart); $i++) {
            $product = "{$userCart[$i]['pID']}:{$userCart[$i]['qty']}";
            array_push($inCart, $product);
        }
        $cart = join(' ', $inCart);
    }

    // check if address is empty
    if (empty($_POST['address'])) {
        $errors['address'] = 'An address is required';
    } else {
        $address = $_POST['address'];
    }

    // check if phone is empty
    if (empty($_POST['phone'])) {
        $errors['phone'] = 'Phone number is required';
    } else {
        $phone = $_POST['phone'];
        if (!preg_match('/^\d{10}$/', $phone)) {
            $errors['phone'] = 'Invalid phone number';
        }
    }

    // check if payment is empty
    if (empty($_POST['payment'])) {
        $errors['payment'] = 'Enter a payment method';
    } else {
        $payment = $_POST['payment'];
        if (!preg_match('/^[a-zA-Z0-9]{8,20}$/', $payment)) {
            $errors['payment'] = 'Please enter a proper payment method';
        }
    }

    if (array_filter($errors)) {
        print_r($errors);
    } else {
        $sql = "INSERT INTO orders(username,cart,place,phoneNum,payment) VALUES('$user', '$cart', '$address', '$phone', '$payment')";
        // save to database and check
        if (mysqli_query($conn, $sql)) {
            $address = $phone = $payment = "";
        } else {
            //error
            echo 'query error: ' . mysqli_error($conn);
        }

        $sql = "UPDATE users SET cart = '' WHERE username = '$user'";
        if (mysqli_query($conn, $sql)) {
            // success;
            header('Location: index.php');
        } else {
            //error
            echo 'query error: ' . mysqli_error($conn);
        }
    }
}

?>



<!DOCTYPE html>
<html>

<head>
    <title>Genshin Shop</title>
</head>

<?php include("header.php") ?>

<h1>User Cart</h1>
<div class="p-4 d-flex">
    <div class="border w-75 p-3 mx-1">
        <?php
        if ($userCart) : ?>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>

                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0;
                    foreach ($userCart as $inCart) : ?>
                        <tr>
                            <form action="userCart.php" method="POST">
                            sc  <td scope="row">
                                    <strong><?php echo $inCart['pName'] ?></strong><br>
                                    <input type="hidden" name="id_delete" value="<?php echo $inCart['pID'] ?>">
                                    <input class="btn updateCart" type="submit" name="deleteCart" value="Remove Product">
                                </td>
                                <td>$<?php echo $inCart['price'] ?></td>
                                <td><input type="number" name="amount" min=1 value="<?php echo $inCart['qty'] ?>" style="width: 50px;">
                                    <input class="btn updateCart" type="submit" name="editQty" value="Update Quantity">
                                </td>

                                <td>$<?php echo number_format($inCart["price"] * $inCart["qty"], 2);
                                        $total = $total + ($inCart["price"] * $inCart["qty"]);
                                        ?></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Cart Total = $<?php echo number_format($total, 2); ?></h3>
        <?php else : ?>
            <h1>Cart is empty</h1>
            <a class="btn brand" href="index.php">Start Shopping Here</a>
        <?php endif; ?>
    </div>

    <div class="w-50 p-3 mx-1">
        <form class="border form" action="userCart.php" method="POST">
            <h2 class="mb-3">Shipping Information</h2>

            <!-- Email -->
            <div class="mb-3">
                <label>Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($email) ?>">
                <p class="text-danger"><small><?php echo $errors['address'] ?></small></p>
            </div>

            <div class="mb-3">
                <label>Phone Number</label>
                <input type="number" name="phone" class="form-control" value="<?php echo htmlspecialchars($email) ?>" placeholder="example: 7801231234">
                <p class="text-danger"><small><?php echo $errors['phone'] ?></small></p>
            </div>

            <div class="mb-3">
                <label>Payment Type</label>
                <input type="text" class="form-control" name="payment" value="<?php echo htmlspecialchars($email) ?>">
                <p class="text-danger"><small><?php echo $errors['payment'] ?></small></p>
            </div>


            <p><strong>Cart Total = $<?php echo number_format($total, 2); ?></strong></p>
            <input type="submit" name="order" value="Place Order" class="brand btn">
            <a class="brand btn" href="index.php">Continue Shopping</a>
            <p class="text-danger"><?php echo $errors['cart'] ?></p>
        </form>
    </div>

</div>


<?php include("footer.php");

?>

</html>