<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
$main_db_connection = getDbConnection();


function session_exists() {
	return isset($_SESSION['email']);
}

function get_page_id_requested() {
	return isset($_REQUEST['page']) ? $_REQUEST['page'] : null;
}

function handle_login() {
	# 1692888607 = login form
	if(get_page_id_requested() != '1692888607') {
		return;
	}
	if(session_exists()) {
		$is_admin = isset($_SESSION['admin_a']) ? $_SESSION['admin_a'] == 1 : 0;
		# ADMIN : MEMBER
		$name = $is_admin ? 'ADMIN-START' : 'MEMBER_START';
		$page_id = $is_admin ? '1695451523' : '1741511500';
		my_redirect($page_id);
	} elseif(isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
		$db_connection = getDbConnection();
		try {
			$statement = $db_connection->prepare("SELECT * FROM plugin_login_users WHERE email=? LIMIT 1");
			$statement->bind_param('s', $_REQUEST['email']);
			$statement->execute();
			# result wird ggf. bereits genutzt, darf nicht ueberschrieben werden
			$result2 = $statement->get_result();
			if($rec = $result2->fetch_assoc()) {
				if(password_verify($_REQUEST['password'], $rec['password'])) {
					$_SESSION['userid'] = $rec['id'];
					$_SESSION['name'] = $rec['username'];
					$_SESSION['admin_a'] = $rec['admin'];
					$_SESSION['email'] = $rec['email'];
					my_redirect('1692888607'); #1692888607
				}
			}
		} finally {
			$db_connection->close();
		}
	}
}

function my_redirect($page_id) {
	# do not close main db connection here - causes STRATO problems
	if(!headers_sent()) {
		header('Status: 302 Moved Temporarily', false, 302);
		header('Location: /?page=' . $page_id);
	} else {
		# STRATO fallback
		echo '<script type="text/javascript">';
        echo 'window.location.href="?page='.$page_id.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=?page='.$page_id.'" />';
        echo '</noscript>'; exit;
	}
}
	handle_login();
?>