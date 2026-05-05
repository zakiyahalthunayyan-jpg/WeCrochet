<?php
session_start();
include("includes/Connection.php");

if (isset($_POST['quick_add'])) {

    $id = (int)$_POST['id'];
    $qty = (int)$_POST['quantity'];

    $result = mysqli_query($conn, "SELECT * FROM products WHERE P_ID = $id");
    $product = mysqli_fetch_assoc($result);

    if ($product && $qty <= $product['P_Stock']) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        $found = false;

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] += $qty;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = array(
                'id' => $product['P_ID'],
                'name' => $product['P_Name'],
                'price' => $product['P_Price'],
                'image' => $product['P_Image'],
                'quantity' => $qty
            );
        }
    }

    mysqli_close($conn);
    header("Location: index.php");
    exit();
}
?>