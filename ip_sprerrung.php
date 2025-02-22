<?php // Beispiel, wie man eine IP sperrt
$blocked_ip = '192.168.1.1';
$block_time = (new DateTime())->format('Y-m-d H:i:s');

// Sperre die IP für einen bestimmten Zeitraum
$stmt = $conn->prepare("INSERT INTO ip_blocklist (ip, blocked_at) VALUES (?, ?)");
$stmt->bind_param("ss", $blocked_ip, $block_time);
$stmt->execute();
$stmt->close();
?>
