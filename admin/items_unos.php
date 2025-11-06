<?php
require_once __DIR__ . '/include/php/header.php';
require_once __DIR__ . '/../../lib/security.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = $id ? Item::getById($id) : null;

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['_csrf_token'] ?? '';
    if (!verify_csrf($token)) {
        $errors[] = 'Invalid form submission (CSRF token).';
    } else {
        // collect safe inputs
        $data = [];
        $data['title_hr'] = trim($_POST['title_hr'] ?? '');
        $data['title_en'] = trim($_POST['title_en'] ?? '');
        $data['description'] = trim($_POST['description'] ?? '');
        $data['price'] = isset($_POST['price']) ? floatval($_POST['price']) : 0;
        $data['area_m2'] = isset($_POST['area_m2']) ? (int)$_POST['area_m2'] : null;
        $data['rooms'] = isset($_POST['rooms']) ? (int)$_POST['rooms'] : null;
        $data['city'] = trim($_POST['city'] ?? '');
        $data['category_id'] = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
        $data['published'] = isset($_POST['published']) ? 1 : 0;
        $data['address'] = trim($_POST['address'] ?? '');
        $data['floor'] = isset($_POST['floor']) ? (int)$_POST['floor'] : null;
        $data['total_floors'] = isset($_POST['total_floors']) ? (int)$_POST['total_floors'] : null;
        $data['heating'] = trim($_POST['heating'] ?? '');
        $data['energetski_certifikat'] = trim($_POST['energetski_certifikat'] ?? '');
        $data['lat'] = trim($_POST['lat'] ?? '');
        $data['lng'] = trim($_POST['lng'] ?? '');

        if ($data['title_hr'] === '') {
            $errors[] = 'Naslov (HR) je obavezan.';
        }

        if (!$errors) {
            if ($id) {
                Item::update($id, $data);
                header('Location: items_pregled.php');
                exit;
            } else {
                $newId = Item::create($data);
                header('Location: items_unos.php?id=' . (int)$newId);
                exit;
            }
        }
    }
}
?>
<div style="padding:20px;">
    <h1><?php echo $id ? 'Uredi nekretninu' : 'Dodaj nekretninu'; ?></h1>
    <?php if ($errors): foreach($errors as $e): ?>
        <div style="color:red;"><?php echo e($e); ?></div>
    <?php endforeach; endif; ?>
    <form method="post">
        <?php echo csrf_input(); ?>
        <label>Naslov (HR)<br/><input name="title_hr" value="<?php echo e($item['title_hr'] ?? ''); ?>" style="width:100%"/></label><br/><br/>
        <label>Naslov (EN)<br/><input name="title_en" value="<?php echo e($item['title_en'] ?? ''); ?>" style="width:100%"/></label><br/><br/>
        <label>Opis<br/><textarea name="description" rows="6" style="width:100%"><?php echo e($item['description'] ?? ''); ?></textarea></label><br/><br/>
        <label>Cijena<br/><input name="price" value="<?php echo e($item['price'] ?? ''); ?>" /></label><br/><br/>
        <label>Površina (m2)<br/><input name="area_m2" value="<?php echo e($item['area_m2'] ?? ''); ?>" /></label><br/><br/>
        <label>Sobe<br/><input name="rooms" value="<?php echo e($item['rooms'] ?? ''); ?>" /></label><br/><br/>
        <label>Grad / Lokacija<br/><input name="city" value="<?php echo e($item['city'] ?? ''); ?>" /></label><br/><br/>
        <label>Adresa<br/><input name="address" value="<?php echo e($item['address'] ?? ''); ?>" style="width:100%"/></label><br/><br/>
        <label>Kat (floor)<br/><input name="floor" value="<?php echo e($item['floor'] ?? ''); ?>" /></label><br/><br/>
        <label>Ukupno katova<br/><input name="total_floors" value="<?php echo e($item['total_floors'] ?? ''); ?>" /></label><br/><br/>
        <label>Grijanje<br/><input name="heating" value="<?php echo e($item['heating'] ?? ''); ?>" /></label><br/><br/>
        <label>Energetski certifikat<br/><input name="energetski_certifikat" value="<?php echo e($item['energetski_certifikat'] ?? ''); ?>" /></label><br/><br/>
        <label>Latitude<br/><input name="lat" value="<?php echo e($item['lat'] ?? ''); ?>" /></label><br/><br/>
        <label>Longitude<br/><input name="lng" value="<?php echo e($item['lng'] ?? ''); ?>" /></label><br/><br/>
        <label><input type="checkbox" name="published" <?php echo (!empty($item['published'])) ? 'checked' : ''; ?> /> Objavljeno</label><br/><br/>
        <button type="submit">Spremi</button>
    </form>
</div>
