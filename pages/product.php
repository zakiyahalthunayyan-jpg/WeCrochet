<?php
// Raghad Alyabis 2240003458

include("../includes/header.php");
include("../includes/Connection.php");

$id = (int) $_GET['id'];

$query = "SELECT * FROM products WHERE P_ID = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<p>Product not found.</p>";
    include("../includes/footer.php");
    exit();
}

if (isset($_POST['add_to_cart'])) {

    $qty = (int) $_POST['quantity'];

    if ($qty > 0 && $qty <= $product['P_Stock']) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        $found = false;

        foreach ($_SESSION['cart'] as &$item) {

            if ($item['id'] == $id) {

                $new_qty = $item['quantity'] + $qty;

                if ($new_qty <= $product['P_Stock']) {

                    $item['quantity'] = $new_qty;
                    $found = true;

                    header("Location: product.php?id=$id&added=1");
                    exit();

                } else {

                    $message = "Sorry, you already have " . $item['quantity'] .
                               " in your cart. Only " . $product['P_Stock'] .
                               " items are available in stock.";

                    $msg_type = "error";
                    $found = true;
                }

                break;
            }
        }

        if (!$found) {

            $_SESSION['cart'][] = array(
                'id' => $product['P_ID'],
                'name' => $product['P_Name'],
                'price' => $product['P_Price'],
                'image' => $product['P_Image'],
                'quantity' => $qty,
                'stock' => $product['P_Stock']
            );

            header("Location: product.php?id=$id&added=1");
            exit();
        }

    } elseif ($qty <= 0) {

        $message = "Please enter a valid quantity.";
        $msg_type = "error";

    } else {

        $message = "Sorry, only " . $product['P_Stock'] . " items available in stock.";
        $msg_type = "error";
    }
}
?>

<script>
    var productStock = <?php echo $product['P_Stock']; ?>;

    function openHelp() {
        alert(
            "Help Window\n\n" +
            "1- Enter quantity.\n" +
            "2- Click Add to Cart.\n" +
            "3- Stock must not exceed available quantity."
        );
    }

    function validateForm() {

        let qty = document.getElementById("quantity").value;

        if (qty == "") {
            alert("Quantity is required");
            return false;
        }

        if (isNaN(qty)) {
            alert("Quantity must be a number");
            return false;
        }

        if (qty <= 0) {
            alert("Please enter valid quantity");
            return false;
        }

        if (qty > productStock) {
            alert("Quantity exceeds stock");
            return false;
        }

        return true;
    }
</script>

<div class="product-top">
    <a href="../index.php" class="back-link">← Back to Products</a>
</div>

<div class="product-detail">

    <img src="../images/<?php echo htmlspecialchars($product['P_Image']); ?>"
         alt="<?php echo htmlspecialchars($product['P_Name']); ?>">

    <div class="info">

        <h1><?php echo htmlspecialchars($product['P_Name']); ?></h1>

        <p class="price">
            <?php echo htmlspecialchars($product['P_Price']); ?> SAR
        </p>

        <p class="stock">
            In Stock: <?php echo htmlspecialchars($product['P_Stock']); ?> items
        </p>

        <p class="description">
            <?php echo htmlspecialchars($product['P_Description']); ?>
        </p>

        <p>
            <strong>Category:</strong>
            <?php echo htmlspecialchars($product['P_Category']); ?>
        </p>

        <br>

        <?php if (isset($_GET['added'])) { ?>
            <p class="success-message">
                Product added to cart successfully!
            </p>
        <?php } ?>

        <?php if (isset($message) && $msg_type == "error") { ?>
            <p class="error-message">
                <?php echo $message; ?>
            </p>
        <?php } ?>

        <form method="POST" onsubmit="return validateForm()">

            <input type="number"
                   name="quantity"
                   id="quantity"
                   class="quantity-input"
                   min="1"
                   max="<?php echo $product['P_Stock']; ?>"
                   placeholder="Qty">

            <button type="submit"
                    name="add_to_cart"
                    class="btn btn-primary">
                Add to Cart 🛒
            </button>

            <button type="button"
                    onclick="openHelp()"
                    class="btn btn-warning">
                Help ❓
            </button>

        </form>

        <br>

        <a href="../cart.php" class="btn btn-success">
            Go to Checkout →
        </a>

    </div>

</div>

<?php
mysqli_close($conn);
include("../includes/footer.php");
?>