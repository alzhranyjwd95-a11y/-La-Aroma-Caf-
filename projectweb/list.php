//Display product with pagination
<?php
include 'header.php';
require_once  'db/DBConnection.php';
$conn = getConnection();
//Number of product to display per page
$perPage = 5;
//get current page number using GET
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {$page = 1;}
//get total number of products
$total = (int) $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
// Calculate total number of pages
$totalPages = $total > 0 ? ceil($total / $perPage) : 1;
if ($page > $totalPages) {$page = $totalPages;}
// Calculate offset for pagination
$offset = ($page - 1) * $perPage;
$stmt = $conn->prepare("SELECT * FROM products ORDER BY name ASC LIMIT ?,?");
$stmt->execute([$offset, $perPage]);
$coffees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

    <main class="content">
        <h2  id="coffee-list">All Coffees </h2>

        <?php if (!$coffees): ?>
            <p>No coffees found.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Details</th>
                </tr>
                <!--Display products in table-->
                <?php foreach ($coffees as $coffee): ?>
                    <tr>
                        <td><?= $coffee['id'] ?></td>
                        <td><?= htmlspecialchars($coffee['name']) ?></td>
                        <td><?= htmlspecialchars($coffee['type']) ?></td>
                        <td><?= $coffee['price'] ?> SAR </td>
                        <td>
                            <a class="btn btn--ghost btn--sm"
                               href="view.php?id=<?= $coffee['id'] ?>&from=list"
                               title="view">
                                view
                            </a>

                        </td>

                    </tr>
                <?php endforeach; ?>
            </table>
    <!-- Pagination link -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">

            <!-- Previous -->
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="page-link">Previous</a>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="page-link active"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="page-link"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Next -->
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="page-link">Next</a>
            <?php endif; ?>

        </div>
    <?php endif; ?>

        <?php endif; ?>
    </main>

<?php include 'footer.php'; ?>