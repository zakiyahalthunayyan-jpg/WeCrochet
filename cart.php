<?php
// Zainab Ali Alfaraj 2240006683

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("includes/header.php");
include("includes/Connection.php");

// Delete single item
if (isset($_GET['delete'])) {

    $index = (int) $_GET['delete'];

    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }

    header("Location: cart.php");
    exit();
}

// Empty cart
if (isset($_GET['empty'])) {

    unset($_SESSION['cart']);

    header("Location: cart.php");
    exit();
}

// Update quantity
if (isset($_POST['update'])) {

    $index = (int) $_POST['index'];
    $new_qty = (int) $_POST['new_quantity'];

    if (isset($_SESSION['cart'][$index])) {

        $pid = (int) $_SESSION['cart'][$index]['id'];

        $result = mysqli_query($conn, "SELECT P_Stock FROM products WHERE P_ID = $pid");
        $product = mysqli_fetch_assoc($result);

        if ($product) {

            if ($new_qty >= 1 && $new_qty <= $product['P_Stock']) {

                $_SESSION['cart'][$index]['quantity'] = $new_qty;

                header("Location: cart.php");
                exit();

            } else {

                echo "<script>alert('Quantity must be between 1 and " . $product['P_Stock'] . "');</script>";
            }
        }
    }
}

// Buy
if (isset($_POST['buy'])) {

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

        foreach ($_SESSION['cart'] as $item) {

            $item_id = (int) $item['id'];
            $item_qty = (int) $item['quantity'];

            $query = "UPDATE products 
                      SET P_Stock = P_Stock - $item_qty 
                      WHERE P_ID = $item_id";

            mysqli_query($conn, $query);
        }

        $past = array();

        if (isset($_COOKIE['past_purchases'])) {

            $decoded = json_decode($_COOKIE['past_purchases'], true);

            if (is_array($decoded)) {
                $past = $decoded;
            }
        }

        foreach ($_SESSION['cart'] as $item) {

            $past[] = array(
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image']
            );
        }

        setcookie('past_purchases', json_encode($past), time() + (86400 * 30), "/");

        unset($_SESSION['cart']);

        echo "<script>alert('Thank you for your purchase!'); window.location='cart.php';</script>";
        exit();
    }
}
?>

<div class="cart-top">
    <a href="index.php" class="back-link">← Back</a>
</div>

<h2 class="page-title">Shopping Cart</h2>

<div class="cart-container">

    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { ?>

        <table class="cart-table">

            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>

            <?php
            $grand_total = 0;

            foreach ($_SESSION['cart'] as $index => $item) {

                $item_total = $item['price'] * $item['quantity'];
                $grand_total += $item_total;
            ?>

                <tr>

                    <td>
                        <img src="images/<?php echo htmlspecialchars($item['image']); ?>"
                             alt="<?php echo htmlspecialchars($item['name']); ?>"
                             width="80">
                    </td>

                    <td>
                        <?php echo htmlspecialchars($item['name']); ?>
                    </td>

                    <td>
                        <?php echo number_format($item['price'], 2); ?> SAR
                    </td>

                    <td>

                        <form method="POST" class="inline-form">

                            <input type="hidden"
                                   name="index"
                                   value="<?php echo $index; ?>">

                            <input type="number"
                                   name="new_quantity"
                                   value="<?php echo $item['quantity']; ?>"
                                   min="1"
                                   max="<?php echo $item['stock']; ?>"
                                   required
                                   class="quantity-input"
                                   oninput="validateQty(this)">

                            <button type="submit"
                                    name="update"
                                    class="btn btn-warning small-btn">
                                Update
                            </button>

                        </form>

                    </td>

                    <td>
                        <?php echo number_format($item_total, 2); ?> SAR
                    </td>

                    <td>
                        <a href="cart.php?delete=<?php echo $index; ?>"
                           class="btn btn-danger small-btn">
                            Delete
                        </a>
                    </td>

                </tr>

            <?php } ?>

        </table>

        <p class="cart-total">
            Grand Total: <?php echo number_format($grand_total, 2); ?> SAR
        </p>

        <div class="cart-actions">

            <a href="cart.php?empty=1" class="btn btn-danger">
                Empty Cart 🗑️
            </a>

            <a href="index.php" class="btn btn-warning">
                Continue Shopping 🛍️
            </a>

            <form method="POST" class="inline-form">
                <button type="submit"
                        name="buy"
                        class="btn btn-success">
                    Buy Now ✅
                </button>
            </form>

        </div>

    <?php } else { ?>

        <div class="empty-cart">
            <p>Your cart is empty 🛒</p>
            <br>
            <a href="index.php" class="btn btn-primary">
                Start Shopping →
            </a>
        </div>

    <?php } ?>

</div>

<script>
function validateQty(input) {

    let value = parseInt(input.value);
    let max = parseInt(input.max);

    if (value < 1) {
        alert("Quantity must be greater than 0");
        input.value = 1;
    }

    if (value > max) {
        alert("Quantity exceeds available stock");
        input.value = max;
    }
}
</script>

<?php
mysqli_close($conn);
include("includes/footer.php");
?>