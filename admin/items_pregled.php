<?php
require_once __DIR__ . '/include/php/header.php';
require_once __DIR__ . '/../../lib/security.php';

$perPage = isset($_SESSION['on-page']) ? (int)$_SESSION['on-page'] : 30;
$page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$offset = ($page - 1) * $perPage;

$total = Item::countAll(false);
$items = Item::getList($perPage, $offset, false);
$totalPages = (int)ceil($total / $perPage);
?>
<div style="padding:20px;">
    <h1>Pregled nekretnina</h1>
    <p><a href="items_unos.php">Dodaj novu nekretninu</a></p>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Naslov (HR)</th>
                <th>Cijena</th>
                <th>Površina (m2)</th>
                <th>Objavljeno</th>
                <th>Akcije</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($items): foreach($items as $it): ?>
            <tr>
                <td><?php echo (int)$it['id']; ?></td>
                <td><?php echo e($it['title_hr'] ?? $it['title_en'] ?? ''); ?></td>
                <td><?php echo isset($it['price']) ? e($it['price']) : '-'; ?></td>
                <td><?php echo isset($it['area_m2']) ? (int)$it['area_m2'] : '-'; ?></td>
                <td><?php echo (!empty($it['published'])) ? 'Da' : 'Ne'; ?></td>
                <td>
                    <a href="items_unos.php?id=<?php echo (int)$it['id']; ?>">Uredi</a> |
                    <form method="post" action="items_pregled.php" style="display:inline">
                        <?php echo csrf_input(); ?>
                        <input type="hidden" name="delete_id" value="<?php echo (int)$it['id']; ?>" />
                        <button type="submit" onclick="return confirm('Obrisati nekretninu?');">Obriši</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; else: ?>
            <tr><td colspan="6">Nema nekretnina.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
        <div style="margin-top:12px;">
            Stranice:
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $page): ?>
                    <strong><?php echo $i; ?></strong>
                <?php else: ?>
                    <a href="?p=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
<?php
// handle deletes (POST with CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_id'])) {
    $token = $_POST['_csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        die('Invalid CSRF token.');
    }
    $del = (int)$_POST['delete_id'];
    Item::delete($del);
    header('Location: items_pregled.php');
    exit;
}
?>