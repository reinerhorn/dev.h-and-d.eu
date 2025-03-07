<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";  
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php")) {
    die("Fehler: config.inc.php wurde nicht gefunden.");
}

$connection=getDbConnection();
// Session-Cookie-Parameter vor session_start() setzen
$inactive = 1800; // 30 Minuten Inaktivität
session_set_cookie_params([
    'lifetime' => 0, // Cookie wird gelöscht, wenn der Browser geschlossen wird
    'path' => '/',
    'domain' => '', // Standardmäßig die aktuelle Domain
    'secure' => isset($_SERVER['HTTPS']), // Nur über HTTPS senden
    'httponly' => true, // JavaScript darf das Cookie nicht auslesen
    'samesite' => 'Strict' // Schutz gegen CSRF-Angriffe
]);

// Session nur starten, wenn sie noch nicht aktiv ist
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Prüfe, ob die Session abgelaufen ist
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: /login.php");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time(); // Aktualisiere den Zeitstempel der letzten Aktivität

 
// Setzt die Session-Lifetime auf 30 Minuten (1800 Sekunden)
$inactive = 1800; 
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactive)) {
    session_unset();    // löscht die Session-Variablen
    session_destroy();  // zerstört die Session
    if (!headers_sent()) {
        header("Location: /login.php"); // Weiterleitung zur Login-Seite
        exit();
    } else {
        echo "<script>window.location.href='/login.php';</script>";
        exit();
    }
}
$_SESSION['LAST_ACTIVITY'] = time(); // aktualisiert den Zeitstempel der letzten Aktivität

// Session soll beim Schließen des Browsers gelöscht werden
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 0);
    ini_set('session.gc_maxlifetime', $inactive);
    session_set_cookie_params(0);
    session_start();
}

/*include $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$main_db_connection = getDbConnection(); */

function session_exists(): bool {
    return !empty($_SESSION['email']);
}

function get_page_id_requested(): ?string {
    return $_REQUEST['page'] ?? null;
}

function handle_login() {
    if (get_page_id_requested() !== '1692888607') {
        return;
    }

    if (session_exists()) {
        $is_admin = !empty($_SESSION['admin_a']);
        $page_id = $is_admin ? '1695451523' : '1695651700';
        my_redirect($page_id);
    } elseif (!empty($_POST['email']) && !empty($_POST['password'])) {
        login_user($_POST['email'], $_POST['password']);
    }
}

function login_user(string $email, string $password) {
    $db_connection = getDbConnection();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return; // Ungültige E-Mail verhindern
    }

    try {
        $stmt = $db_connection->prepare("SELECT id, username, admin, email, password FROM plugin_login_users WHERE email=? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($rec = $result->fetch_assoc()) {
            if (password_verify($password, $rec['password'])) {
                $_SESSION['userid'] = $rec['id'];
                $_SESSION['name'] = htmlspecialchars($rec['username'], ENT_QUOTES, 'UTF-8');
                $_SESSION['admin_a'] = (int) $rec['admin'];
                $_SESSION['email'] = htmlspecialchars($rec['email'], ENT_QUOTES, 'UTF-8');

                session_write_close(); // Session schreiben, um Fehler mit Header-Redirect zu vermeiden
                my_redirect('1692888607');
            }
        }
    } finally {
        $stmt->close();
        
    }
}

function my_redirect(string $page_id) {
    if (!headers_sent()) {
        header('Location: /?page=' . urlencode($page_id), true, 302);
        exit;
    } else {
        echo '<script>window.location.href="?page=' . htmlspecialchars($page_id, ENT_QUOTES, 'UTF-8') . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=?page=' . htmlspecialchars($page_id, ENT_QUOTES, 'UTF-8') . '" /></noscript>';
        exit;
    }
}

handle_login();
?>