<?php
include("includes/header.php");
include("includes/Connection.php");

// === Display All Products ===
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT * FROM products WHERE P_Name LIKE '%$search%' OR P_Category LIKE '%$search%' OR P_Description LIKE '%$search%'";
} else {
    $query = "SELECT * FROM products";
}
$result = mysqli_query($conn, $query);
?>

<h2 class="page-title">Our Products</h2>

<div class="products-grid">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="product-card">
            <a href="pages/product.php?id=<?php echo $row['P_ID']; ?>">
    <img src="images/<?php echo htmlspecialchars($row['P_Image']); ?>" 
         alt="<?php echo htmlspecialchars($row['P_Name']); ?>">
    <div class="card-info">
        <h3><?php echo htmlspecialchars($row['P_Name']); ?></h3>
        <p class="price"><?php echo htmlspecialchars($row['P_Price']); ?> SAR</p>
    </div>
</a>
            <form method="POST" action="add_to_cart.php" class="quick-add">
                <input type="hidden" name="id" value="<?php echo $row['P_ID']; ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" name="quick_add" class="btn-quick-add">+</button>
            </form>
        </div>
    <?php } ?>
</div>


<?php
if (isset($_COOKIE['past_purchases'])) {

    $past = json_decode($_COOKIE['past_purchases'], true);

    if (!empty($past)) {
        echo "<h2 class='page-title'>Past Purchases</h2>";
        echo "<div class='products-grid'>";

        foreach ($past as $item) {
?>
            <div class="product-card">
                <img src="images/<?php echo htmlspecialchars($item['image']); ?>" 
                     alt="<?php echo htmlspecialchars($item['name']); ?>">

                <div class="card-info">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p class="price"><?php echo htmlspecialchars($item['price']); ?> SAR</p>
                </div>
            </div>
<?php
        }

        echo "</div>";
    }
}
?>
<?php
mysqli_close($conn);
include("includes/footer.php");
?>