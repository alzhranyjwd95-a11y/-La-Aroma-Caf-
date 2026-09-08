<?php
include 'header.php';
require_once 'db/DBConnection.php';
$conn = getConnection();

$typeStmt = $conn->query("SELECT DISTINCT type FROM products ORDER BY type");
$types = $typeStmt->fetchAll(PDO::FETCH_ASSOC);

$selectedType = null;
$coffees = [];

if (isset($_POST['type'])) {
    $selectedType = $_POST['type'];

    if ($selectedType === 'all') {
        $stmt = $conn->query("SELECT * FROM products ORDER BY name ASC");
        $coffees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $conn->prepare("SELECT * FROM products WHERE type = :type ORDER BY name ASC");
        $stmt->execute([':type' => $selectedType]);
        $coffees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

    <main class="content">

        <section class="coffee-form">
            <h2>Choose Coffee by Type</h2>

            <form method="post">
                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="" disabled <?= !$selectedType ? 'selected' : '' ?>>Select Type</option>
                    <option value="all" <?= $selectedType === 'all' ? 'selected' : '' ?>>All Types</option>

                    <?php foreach ($types as $type): ?>
                        <option value="<?= htmlspecialchars($type['type']) ?>"
                                <?= $selectedType === $type['type'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type['type']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Show Coffees</button>
            </form>
        </section>

        <?php if (isset($_POST['type'])): ?>
            <section class="coffee-list">
                <h2>Result</h2>

                <?php if (!$coffees): ?>
                    <p>No coffees found for this type.</p>
                <?php else: ?>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Details</th>
                        </tr>

                        <?php foreach ($coffees as $coffee): ?>
                            <tr>
                                <td><?= $coffee['id'] ?></td>
                                <td><?= htmlspecialchars($coffee['name']) ?></td>
                                <td><?= htmlspecialchars($coffee['type']) ?></td>
                                <td><?= $coffee['price'] ?></td>
                                <td>
                                    <a class="btn btn--ghost btn--sm" href="view.php?id=<?= $coffee['id'] ?>&from=choice">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </section>
        <?php endif; ?>

    </main>

<?php include 'footer.php'; ?>