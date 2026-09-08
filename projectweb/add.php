<?php
include 'header.php';
require_once 'db/DBConnection.php';
$conn = getConnection();

//Delete product by id (Get request)
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: add.php?success=deleted&focus=list");
    exit;
}

$editCoffee = null;
//Fetch product data for editing
if (isset($_GET['edit_id'])) {
    $id = (int) $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $editCoffee = $stmt->fetch(PDO::FETCH_ASSOC);
}

//Handle from submission (Add or Update)
if (isset($_POST['add']))  {
    $name  = $_POST['name'];
    $type  = $_POST['type'];
    $price = $_POST['price'];
    $desc  = $_POST['description'];
    $stock = 0;
    $image = '';

    //if user selects other
    if($name === 'Other' && !empty($_POST['other_name'])){
        $image = $_POST['other_name'];
    }

//Update existing product
    if (!empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        $sql = "UPDATE products 
                SET name = ?, type = ?, price = ?, description = ?  WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
                 $name,
                 $type,
                $price,
                $desc,
                $id
        ]);
        header("Location: add.php?success=updated&focus=list");
        exit;
//insert new product
    } else {
        $sql = "INSERT INTO products (name,type,price,stock,description,image)VALUES (?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
                 $name,
                 $type,
                 $price,
                 $stock,
                 $desc,
                $image
        ]);
    }
    header("Location: add.php?success=added&focus=list");
    exit;
}
//fetch all product to display in the table
$stmt = $conn->query("SELECT * FROM products ORDER BY name ASC");
$coffees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">

    <section class="coffee-list">
        <h2 id="coffee-list">All Coffees</h2>

        <?php if (!$coffees): ?>
            <p>No coffees available yet.</p>
        <?php else: ?>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th colspan="3">Actions</th>
                </tr>

                <?php foreach ($coffees as $coffee): ?>
                <!-- Display each product-->
                    <tr>
                        <td><?= $coffee['id'] ?></td>
                        <td><?= htmlspecialchars($coffee['name']) ?></td>
                        <td><?= htmlspecialchars($coffee['type']) ?></td>
                        <td><?= $coffee['price'] ?></td>
                        <td class="table-actions">
                            <a href="view.php?id=<?= $coffee['id'] ?>&from=add" class="btn btn--ghost btn--sm">View</a>
                            <a href="add.php?edit_id=<?= $coffee['id'] ?>#coffee-form" class="btn btn--primary btn--sm" >Edit</a>
                            <a href="add.php?delete_id=<?= $coffee['id'] ?>" class="btn btn--danger btn--sm"
                               onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
        <?php endif; ?>
    </section>

    <section class="coffee-form">
        <h2><?= $editCoffee ? 'Edit Coffee' : 'Add Coffee' ?></h2>

        <form id="coffee-form" action="" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" list="coffeeNames" value="<?= $editCoffee ? htmlspecialchars($editCoffee['name']) : '' ?>" required>

            <datalist id="coffeeNames">
                <option value="Latte">
                <option value="Cappuccino">
                <option value="Espresso">
                <option value="Americano">
                <option value="Other">
            </datalist>

            <?php if ($editCoffee): ?>
                <input type="hidden" name="id" value="<?= $editCoffee['id'] ?>">
            <?php endif; ?>

             <!-- This box appears only when other is selected-->
            <div id="otherNameBox" style="display:none;">
                <label for="other_name">Enter coffee name:</label>
                <input type="text" id="other_name" name="other_name">
            </div>

            <label for="type">Type:</label>
            <select id="type" name="type">
                <option value="Hot"  <?= $editCoffee && $editCoffee['type']==='Hot'  ? 'selected' : '' ?>>Hot</option>
                <option value="Iced" <?= $editCoffee && $editCoffee['type']==='Iced' ? 'selected' : '' ?>>Iced</option>
                <option value="Bean"<?= $editCoffee && $editCoffee['type']==='Bean'  ? 'selected' : '' ?>>Bean</option>
            </select>

                <label for="price">Price:</label>
            <input type="number" id="price" step="0.01" name="price"
                   placeholder="Price in SAR"
                   value="<?= $editCoffee ? $editCoffee['price'] : '' ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description"><?=
                $editCoffee ? htmlspecialchars($editCoffee['description']) : ''
                ?></textarea>

            <button type="submit" name="add">
                <?= $editCoffee ? 'Update Coffee' : 'Add Coffee' ?>
            </button>

            <?php if ($editCoffee): ?>
                <div style="margin-top:10px; text-align:center;">
                    <button type="button"
                            onclick="window.location.href='add.php'"
                            class="btn btn--ghost btn--cancel">
                        Cancel
                    </button>
                </div>
            <?php endif; ?>

        </form>
    </section>


</main>
<?php include 'footer.php'; ?>