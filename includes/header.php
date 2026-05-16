
<?php
// Zainab Ali Alfaraj 2240006683
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;

if (isset($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>WeCrochet</title>

    <link rel="stylesheet" href="/WECROCHET/Styles/style.css">
    <link rel="stylesheet" href="/WECROCHET/Styles/home.css">
    <link rel="stylesheet" href="/WECROCHET/Styles/cart.css">
    <link rel="stylesheet" href="/WECROCHET/Styles/contact.css">
    <link rel="stylesheet" href="/WECROCHET/Styles/footer.css">
</head>

<body>

<nav class="navbar">

    <a href="/WECROCHET/home.php" class="logo">
        🧶 WeCrochet
    </a>

    <form method="GET"
          action="/WECROCHET/products.php"
          class="search-form">

        <input type="text"
               name="search"
               placeholder="Search products..."
               class="search-input">

        <button type="submit" class="search-btn">
            🔍
        </button>

    </form>

    <ul class="nav-links">

        <li>
            <a href="/WECROCHET/home.php">Home</a>
        </li>

        <li>
            <a href="/WECROCHET/products.php">Products</a>
        </li>

        <li>
            <a href="/WECROCHET/pages/contact.php">Contact Us</a>
        </li>

    </ul>

    <a href="/WECROCHET/cart.php" class="cart-icon">

        🛒

        <span class="cart-count">
            <?php echo $cart_count; ?>
        </span>

    </a>

</nav>