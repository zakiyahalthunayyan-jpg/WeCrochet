<?php
// Maryam Shahin 2240001335

include("../includes/Connection.php");

$message = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Image Upload
    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];

    if (!empty($image_name)) {

        $folder = "../images/" . $image_name;

        move_uploaded_file($tmp_name, $folder);

        $sql = "UPDATE products SET
                P_Name='$name',
                P_Price='$price',
                P_Stock='$stock',
                P_Image='$image_name',
                P_Description='$description',
                P_Category='$category'
                WHERE P_ID='$id'";

    } else {

        $sql = "UPDATE products SET
                P_Name='$name',
                P_Price='$price',
                P_Stock='$stock',
                P_Description='$description',
                P_Category='$category'
                WHERE P_ID='$id'";
    }

    if (mysqli_query($conn, $sql)) {

        $message = "Product modified successfully!";
        $msg_type = "success";

    } else {

        $message = "Error: " . mysqli_error($conn);
        $msg_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Modify Product</title>

<link rel="stylesheet" href="../Styles/Modify_page.css">

<script>

function validateForm() {

    let id = document.getElementById("id").value;
    let name = document.getElementById("name").value;
    let stock = document.getElementById("stock").value;
    let price = document.getElementById("price").value;
    let description = document.getElementById("description").value;
    let category = document.getElementById("category").value;

    if (id == "" || id <= 0) {

        alert("Please enter valid Product ID");
        return false;
    }

    if (name == "") {

        alert("Product name is required");
        return false;
    }

    if (stock == "" || stock < 0) {

        alert("Please enter valid stock quantity");
        return false;
    }

    if (price == "" || price <= 0) {

        alert("Please enter valid price");
        return false;
    }

    if (description == "") {

        alert("Description is required");
        return false;
    }

    if (category == "") {

        alert("Category is required");
        return false;
    }

    return true;
}

</script>

</head>

<body>

<div class="container">

    <h3>Modify Products Here</h3>

    <?php if (!empty($message)) { ?>

        <p class="<?php echo $msg_type; ?>">

            <?php echo $message; ?>

        </p>

    <?php } ?>

    <form method="POST"
          enctype="multipart/form-data"
          onsubmit="return validateForm()">

        <label>Product ID:</label>

        <input type="number"
               name="id"
               id="id"
               required>

        <label>Name:</label>

        <input type="text"
               name="name"
               id="name"
               required>

        <label>Image:</label>

        <input type="file"
               name="image">

        <label>Stock:</label>

        <input type="number"
               name="stock"
               id="stock"
               required>

        <label>Price:</label>

        <input type="number"
               step="0.01"
               name="price"
               id="price"
               required>

        <label>Description:</label>

        <textarea name="description"
                  id="description"
                  required></textarea>

        <label>Category:</label>

        <input type="text"
               name="category"
               id="category"
               required>

        <button type="submit">

            Modify Product

        </button>

    </form>

</div>

</body>
</html>