<!DOCTYPE html>
<html lang="de">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name='robots' content='index, follow'>
    <meta name="google-site-verification" content="4gzDy9yrFMe0UMYb_if2V49zaDQtEtmLtx6tvJlKmgk">
    <meta name="author" content="Reiner Horn">
    <title>Agentur / Webdesign / Dienstleistung / Personal / Vermittlung / Lohn</title>
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/style.css" media="screen"> 
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/services.css" media="screen">
    <link title="H & D Dienstleistungen SRL" rel="stylesheet" type="text/css" href="/css/language_selector.css" media="screen">
    <link rel="icon" href="/images/icon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/images/icon/favicon.ico"> 
<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/config/config.inc.php";
    $main_db_connection = getDbConnection();
    include $_SERVER['DOCUMENT_ROOT'] . '/inc/.session.php';
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
          
    if (isset($_REQUEST['session']) && $_REQUEST['session'] == "destroy") {
      session_start();
      unset($_SESSION['userid']);
      unset($_SESSION['name']);
      unset($_SESSION['email']);
      session_destroy();
      setcookie('PHPSESSID', 'invalid', time() - 3600);
      header('Status: 302 Moved Temporarily', false, 302);
      header('Location: /index.php');
      die();
    }
    $_SERVER['HTTP_ACCEPT_LANGUAGE'];
?>
  <script src="/function/js/editor.js"></script>
  <script src="/function/js/language_selector.js"></script>