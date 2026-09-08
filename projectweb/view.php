<?php
include 'header.php';
require_once 'db/DBConnection.php';
$conn = getConnection();

// Get product id from URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch coffee details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$coffee = $stmt->fetch(PDO::FETCH_ASSOC);
?>

    <main class="content">

        <h2>Coffee Details</h2>

        <?php if (!$coffee): ?>
            <p>Coffee not found.</p>
        <?php else: ?>

            <!-- Display coffee information in read only form -->

            <table class="view-table" >
                <tr>
                    <th>ID</th>
                    <td><?= $coffee['id'] ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?= htmlspecialchars($coffee['name']) ?></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td><?= htmlspecialchars($coffee['type']) ?></td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td><?= $coffee['price'] ?> SAR</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td><?= htmlspecialchars($coffee['description']) ?></td>
                </tr>
            </table>

            <?php
            // Back button logic
            $backTo = "add.php";

            if (isset($_GET['from'])) {
                if ($_GET['from'] === "list") {
                    $backTo = "list.php";}
                elseif ($_GET['from'] === "choice") {
                    $backTo = "choice.php";}
                elseif ($_GET['from'] === "add") {
                    $backTo = "add.php";}
            }
            ?>

            <a href="<?= $backTo ?>" class="btn btn--ghost btn--sm">Back</a>

        <?php endif; ?>

    </main>

<?php include 'footer.php'; ?>
