<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Use a reliable path to functions.php
require_once __DIR__ . '/../lib/functions.php';

// Check authentication
$user = new Admin_User;
if (! $user->is_logged()) {
    header('Location:' . _SITE_URL . 'admin/login');
    exit;
}

if (! get_conf('production')) {
    $page_stats = new PageStats;
}

// Logout
if (isset($_GET['odjava']) && $_GET['odjava'] === 'odjava') {
    $user->logout();
    header('Location:' . _SITE_URL . 'admin/login/');
    exit;
}

// default pagination
if (! isset($_SESSION['on-page']) || empty($_SESSION['on-page'])) {
    $_SESSION['on-page'] = 30;
}

// safer filename extraction
$filename = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title><?php echo htmlspecialchars(get_conf('app_name'), ENT_QUOTES, 'UTF-8'); ?> - Adresar CMS</title>
	<meta name='robots' content='noindex,nofollow' />
	<meta name="author" content="Adresar nekretnine" />
	<meta name="viewport" content="initial-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" />
	
	<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	<link href="images/favicon.png" rel="shortcut icon" type="image/x-icon"/>
	<link href="include/css/style.css" rel="stylesheet" type="text/css" />
	<link href="include/css/460up.css" rel="stylesheet" media="screen and (min-width: 460px)"/>
	<link href="include/css/560up.css" rel="stylesheet" media="screen and (min-width: 560px)"/>
	<link href="include/css/660up.css" rel="stylesheet" media="screen and (min-width: 660px)"/>
	<link href="include/css/760up.css" rel="stylesheet" media="screen and (min-width: 760px)"/>
	<link href="include/css/860up.css" rel="stylesheet" media="screen and (min-width: 860px)"/>
	<link href="include/css/960up.css" rel="stylesheet" media="screen and (min-width: 960px)"/>
	<link href="include/css/1280up.css" rel="stylesheet" media="screen and (min-width: 1280px)"/>
	<link href="include/css/1600up.css" rel="stylesheet" media="screen and (min-width: 1600px)"/>
	<link href="include/css/dropzone.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" src="include/js/jquery-1.8.3.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="include/js/datepicker.js"></script>
	<script type="text/javascript" src="include/js/google_maps.js"></script>
	<script type="text/javascript" src="include/js/respond.js"></script>
	<script type="text/javascript" src="include/js/dropzone.js"></script>
	<script type="text/javascript" src="include/js/functions.js"></script>
	

	<!-- NOTE: move API keys to config/environment and do not commit them -->
	<script src="https://maps.googleapis.com/maps/api/js?key=REPLACE_WITH_YOUR_KEY&libraries=places" async defer></script>
	
	<script type="text/javascript" src="../js/sajax.js"></script>
	
	<script type="text/javascript" src="../lib/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="../lib/plugins/ckeditor/adapters/jquery.js"></script>
	
	<!--[if lte IE 6]>
	<meta http-equiv="refresh" content="0; url="warning" />
	<script type="text/javascript">
	/* <![CDATA[ */
	window.top.location = '<?php echo _SITE_URL; ?>warning';
	/* ]]> */
	</script>
	<![endif]-->
	
	<!--[if lte IE 7]>
	<meta http-equiv="refresh" content="0; url="warning" />
	<script type="text/javascript">
	/* <![CDATA[ */
	window.top.location = '<?php echo _SITE_URL; ?>warning';
	/* ]]> */
	</script>
	<![endif]-->	
</head>

<body>
<div class="container">
	<div class="header">
		<a class="menu-toggle" href="javascript:;" title="Izbornik"><img src="images/icon-menu.png" alt="Menu" /></a>
		<div class="site-info">	
			<a class="logo" href="http://adresar.net" target="_blank">
				<img src="images/icon-virtus.png" alt="" />
			</a>
			<a class="title" href="<?php echo htmlspecialchars(_SITE_URL, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" title="Pogledajte vašu stranicu">
				<span><?php echo htmlspecialchars(_SITE_TITLE, ENT_QUOTES, 'UTF-8'); ?></span>
				<img src="images/icon-visit-page.png" alt="<?php echo htmlspecialchars(_SITE_TITLE, ENT_QUOTES, 'UTF-8'); ?>" />
			</a>	
		</div>
		<div class="user-info">
			<a class="avatar" href="profile.php" title="Vaš profil">
				<?php 
				$adminId = isset($_SESSION['admin']['id']) ? (int) $_SESSION['admin']['id'] : 0;
				$avatar = Db::query_one('SELECT photo_name FROM site_photos WHERE table_name = "admin_users" AND table_id = '.$adminId.' ORDER BY orderby DESC LIMIT 1');
				if ($avatar) {
				?>
				<img src="<?php echo htmlspecialchars(_SITE_URL, ENT_QUOTES, 'UTF-8'); ?>lib/plugins/thumb.php?src=<?php echo htmlspecialchars(_SITE_URL, ENT_QUOTES, 'UTF-8'); ?>upload_data/site_photos/th_<?php echo htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8'); ?>&w=40&h=40&zc=1" alt="" />
				<?php } else { ?>
				<img src="images/user-default.png" alt="" />
				<?php } ?>
				<span><img src="images/icon-mask.png" alt="" /></span>
			</a>
			<span>Prijavljeni ste kao: <?php echo isset($_SESSION['admin']['username']) ? htmlspecialchars($_SESSION['admin']['username'], ENT_QUOTES, 'UTF-8') : ''; ?></span><br/>
			<a href="profile.php">Promjena podataka</a><span class="v-separator">|</span><a href="index.php?odjava=odjava">Odjava</a>
		</div>
		<a class="logout" href="index.php?odjava=odjava" title="Odjavite se"><img src="images/icon-logout.png" alt="Logout" /></a>
	</div>
	
	<div class="menu">
