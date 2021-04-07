<?php

// establish connection with database
$conn = mysqli_connect('192.168.64.2', 'storeManager', 'hello', 'genshin_shop');

// check connection
if (!$conn) {
    echo 'connection error' . mysqli_connect_error();
}

// start session for use across the website
session_start();

// setting up user cart for the site

if (isset($_SESSION['userID']) && $_SESSION['userID'] != 'Admin') {
    $user = $_SESSION['userID'];
    $sql = "SELECT cart FROM users WHERE username = '$user'";
    $results = mysqli_query($conn, $sql);
    $cart = mysqli_fetch_row($results);

    $dbInfo = [];
    $userCart = [];

    // check if cart is empty
    $EmptyTestArray = array_filter($cart);

    if (!empty($EmptyTestArray)) {
        // user cart from database, only has id and qty
        foreach ($cart as $contents) {
            $items = explode(" ", $contents);
            foreach ($items as $prod) {
                $cartArray = explode(":", $prod);
                array_push($dbInfo, [
                    'id' => $cartArray[0],
                    'qty' => $cartArray[1]
                ]);
            }
        }

        // the user cart on the website (with additional information)
        foreach ($dbInfo as $product) {
            $pID = $product['id'];
            $sql = "SELECT pName, price FROM products WHERE pID = '$pID'";
            $results = mysqli_query($conn, $sql);
            $n = mysqli_fetch_row($results);
            array_push($userCart, [
                'pID' => $product['id'],
                'pName' => $n[0],
                'price' => $n[1],
                'qty' => $product['qty']
            ]);
        }
    }
}

// overwrites the current cart to updated values (for both 'delete item from cart' and 'update quantity')
function updateCart($cart, $username, $connection)
{
    $cart = join(' ', $cart);
    $sql = "UPDATE users SET cart = '$cart' WHERE username = '$username'";

    if (mysqli_query($connection, $sql)) {
        // success;
        header('Location: userCart.php');
    } else {
        //error
        echo 'query error: ' . mysqli_error($connection);
    }
}
