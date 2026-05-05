<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Products</title>
    <link rel="stylesheet" href="../Styles/addproducts_page.css">
</head>

<body>

<div class="container">
    <h3>Add Products Here</h3>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Image:</label>
        <input type="file" name="image" required>

        <label>Stock:</label>
        <input type="number" name="stock" required>

        <label>Price:</label>
        <input type="number" name="price" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Category:</label>
        <input type="text" name="category" required>

        <button type="submit" name="add_product">Add Product</button>
    </form>
</div>

</body>
</html>