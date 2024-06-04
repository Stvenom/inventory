<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fungsi untuk menambah item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "create") {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "INSERT INTO items (name, quantity, price, description) VALUES ('$name', $quantity, $price, '$description')";
    $conn->query($sql);
    header("Location: inventory.php");
}

// Fungsi untuk mengedit item
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "update") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "UPDATE items SET name='$name', quantity=$quantity, price=$price, description='$description' WHERE id=$id";
    $conn->query($sql);
    header("Location: inventory.php");
}

// Fungsi untuk menghapus item
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == "delete") {
    $id = $_GET['id'];
    $sql = "DELETE FROM items WHERE id=$id";
    $conn->query($sql);
    header("Location: inventory.php");
}

// Menampilkan item untuk diedit
$editItem = null;
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == "edit") {
    $id = $_GET['id'];
    $sql = "SELECT * FROM items WHERE id=$id";
    $result = $conn->query($sql);
    $editItem = $result->fetch_assoc();
}

// Mengambil semua item dari database
$sql = "SELECT * FROM items";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
</head>
<body>
    <h1>Inventory</h1>

    <?php if ($editItem): ?>
        <h2>Edit Item</h2>
        <form method="post" action="inventory.php">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
            Name: <input type="text" name="name" value="<?php echo $editItem['name']; ?>" required><br>
            Quantity: <input type="number" name="quantity" value="<?php echo $editItem['quantity']; ?>" required><br>
            Price: <input type="text" name="price" value="<?php echo $editItem['price']; ?>" required><br>
            Description: <textarea name="description" required><?php echo $editItem['description']; ?></textarea><br>
            <input type="submit" value="Update">
        </form>
    <?php else: ?>
        <h2>Add New Item</h2>
        <form method="post" action="inventory.php">
            <input type="hidden" name="action" value="create">
            Name: <input type="text" name="name" required><br>
            Quantity: <input type="number" name="quantity" required><br>
            Price: <input type="text" name="price" required><br>
            Description: <textarea name="description" required></textarea><br>
            <input type="submit" value="Add">
        </form>
    <?php endif; ?>

    <h2>Item List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['description']}</td>
                        <td>
                            <a href='inventory.php?action=edit&id={$row['id']}'>Edit</a> | 
                            <a href='inventory.php?action=delete&id={$row['id']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No items found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
