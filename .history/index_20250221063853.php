<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/session.php"; 
include_once $_SERVER['DOCUMENT_ROOT'] . "/inc/web_besucher.php";

?>
<!DOCTYPE html>
<html lang="de">
<head> 
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name='robots' content='index, follow'>
<meta name="google-site-verification" content="4gzDy9yrFMe0UMYb_if2V49zaDQtEtmLtx6tvJlKmgk">
<meta name="author" content="Reiner Horn">
<meta name="description" content="Wir verstehen uns nicht nur als Dienstleister, sondern als langfristiger Partner und Wegbegleiter unserer Kunden und Kandidaten. In allen Berechen.">
<meta name="keywords" content="Personal, Webdesign, Lohn, Websiten, Gehalt, SEO , SEA, Suchen, Finden">
<meta name="revisit-after" content="30 days">
<meta name="title" content="Wir haben das! Was Sie suchen!">
<meta property="og:title" content="H&amp;D Dienstleistungen SRL - Webdesign /Websiten/ SEO & SEA">
<meta property="og:site_name" content="H & D Dienstleistung">
<meta property="og:url" content="montagedienst.goip.de">
<meta property="og:type" content="website">
<meta property="og:image" content="/images/hd-logo.svg">
<meta property="og:image:width" content="200">
<meta property="og:image:height" content="200">
<meta property="og:image:type" content="images/icon/ico">
<meta property="og:image:type" content="images/handwerk/webp">ßhh
<meta property="og:image:type" content="images/svg">
<meta name="theme-color" content="#ff0000">
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@service85659784">
<meta name="twitter:url" content="<@service85659784>">
<meta name="twitter:title" content="<H & D Dienstleistung>">
    <title>Agentur / Webdesign / Dienstleistung / Personal / Vermittlung / Lohn</title>
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/style.css" media="screen"> 
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/services.css" media="screen">
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/checkbox.css" media="screen">
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/login.css" media="screen">
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/language_selector.css" media="screen">
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/card.css" media="screen">
    <link rel="icon" href="/images/icon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/images/icon/favicon.ico">
<?php
    if(isset($_REQUEST['language'])) {
      # Vorrang - das ist das Sprachwahlmenue
      $language = $_REQUEST['language'];
    } elseif(isset($_SESSION['language'])){
      $language = $_SESSION['language'];
    } elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      # das hier ist die Sprache, die der Browser vorrangig unterstuetzt
      $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    } else {
      # allerletzte Moeglichkeit, wenn gar nichts greift, dann immer deutsch
      $language = "de";
    }
    $_SESSION['language'] = $language;
?>
  <script src="/function/js/editor.js"></script>
  <script src="/function/js/language_selector.js"></script>
</head>
<body>
  <header>
    <?php
  $page="";
  $stmt = $connection->prepare('SELECT * FROM header LIMIT 1');
  $stmt->execute();
  $header_result = $stmt->get_result();
  if($rec = $header_result->fetch_assoc()) {
    $text = $rec['text'];
    $link = $rec['link'];
    $images = $rec['images'];
    $label = $rec['label'];
    $css = $rec['css'];
  }
  echo '<div class="' . htmlspecialchars($css, ENT_QUOTES, 'UTF-8') . '"><a title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '"><img class="logo" src="' . htmlspecialchars($images, ENT_QUOTES, 'UTF-8') . '" alt="logo"></a><a class="companyname" title="' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars($link, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</a></div>';
  /*$stmt->close();*/
?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/function/php/language_selector.inc.php' ?> 
<navi>
    <div id="Navigation">
        <?php
        include $_SERVER['DOCUMENT_ROOT'] . '/function/php/navi.inc.php';
        ?>
    </div>
</nav>
</header>
<main> 
  <div class="content">
    <?php  
if (!isset($_REQUEST['page'])) {
    $startseite_result = $connection->query(
        'SELECT UNIX_TIMESTAMP(id) AS ts FROM page WHERE fk_translation_placeholder="PAGE_START_LABEL" LIMIT 1'
    );
    if ($page = $startseite_result->fetch_assoc()) {
        $_REQUEST['page'] = (int) $page['ts'];  // Explizite Typensicherheit
    } else {
        die("Fehler: Startseite konnte nicht geladen werden.");
    }
}

$page_id = (int) $_REQUEST['page'];  // Sicherstellen, dass page_id eine Zahl ist
$page_output_all = [];
$plugin_content_id = "";
$cardstack_id = "";

$stmt = $connection->prepare("
    SELECT *, plugin.name AS plugin_label, UNIX_TIMESTAMP(page.id) AS page_id, page.id AS page_raw_id
    FROM page_config
    JOIN page ON page_config.fk_page_id = page.id
    JOIN plugin ON page_config.fk_plugin_id = plugin.id
    WHERE UNIX_TIMESTAMP(page.id) = ?
    ORDER BY page_config.idx ASC
");

if (!$stmt) {
    die("SQL-Fehler: " . $connection->error);
}

$stmt->bind_param('i', $page_id);
$stmt->execute();
$result = $stmt->get_result();

while ($record = $result->fetch_assoc()) {
    $print_all = false;

    if ($record['print_all'] == 1) {
        $page_plugin = $record['page_id'] . '_' . $record['plugin_label'];
        if (in_array($page_plugin, $page_output_all)) {
            continue;
        }
        $page_output_all[] = $page_plugin;
        $print_all = true;
    }

    $plugin_content_id = $record['plugin_content_id'];

    // **Unterstützung für mehrere Plugin-Verzeichnisse**
    $plugin_dirs = [
        $_SERVER['DOCUMENT_ROOT'] . '/plugin/',
        $_SERVER['DOCUMENT_ROOT'] . '/global_plugin/',
        $_SERVER['DOCUMENT_ROOT'] . '/modules/'
    ];

    $plugin_found = false;
    
    foreach ($plugin_dirs as $dir) {
        $plugin_path = $dir . 'plugin_' . $record['plugin_label'] . '.php';
        if (file_exists($plugin_path)) {
            include $plugin_path;
            $plugin_found = true;
            break;
        }
    }

    if (!$plugin_found) {
        error_log("WARNUNG: Plugin-Datei nicht gefunden für: " . $record['plugin_label']);
    }
}
?>
<footer class="footer">
<?php
if (!isset($connection)) {
    die('<p>Fehler: Datenbankverbindung nicht gesetzt.</p>');
}

$stmt = $connection->prepare('SELECT headline, link, language, label, version FROM footer LIMIT 1');
if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($headline, $link, $language, $label, $version);

    if (!$stmt->fetch()) {
        $headline = 'Kein Eintrag gefunden';
        $link = '#';
        $label = 'Kein Eintrag';
        $version = '';
    }
    if ($stmt) {
        $stmt->close();
    }
} else {
    $headline = 'Fehler bei der Datenbankabfrage';
    $link = '#';
    $label = 'Fehler';
    $version = '';
}
// XSS-Schutz
$headline = htmlspecialchars($headline, ENT_QUOTES, 'UTF-8');
$link = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
$label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
$version = htmlspecialchars($version, ENT_QUOTES, 'UTF-8');

echo '© 2020 - ' . date("Y") . ' <a title="' . $headline . '" href="' . $link . '">' . $label . ' ' . $version . '</a>';
?>
</footer>
</div>
</main>
</body>
</html>