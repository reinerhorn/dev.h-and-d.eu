<!--  ===================================================================
	  Urheberrechtshinweis / Copyright

	  Die Gestaltung, Inhalte und Programmierung dieser Seiten
	  unterliegen dem Urheberrecht. Urheber ist Reiner Horn
	  Eine Verwendung der Inhalte außerhalb der vom Urheber betriebenen
	  Domains ist nicht gestattet. Ein Verstoß gegen diese Bestimmungen
	  wird als Urheberrechtsverletzung betrachtet und bei Bekanntwerdung 
	  unter Einsatz von Rechtsmitteln geahndet.
    Verwndung von der leeren datenbank und code muss eine genehmigung
    des Urhebers eingeholt werden.
    Die Datenbank und der Code sind urheberrechtlich geschützt.
    Die Verwendung der Datenbank und des Codes ist nur mit
    ausdrücklicher Genehmigung des Urhebers gestattet.
    Die Datenbank und der Code dürfen nicht ohne Genehmigung
    des Urhebers kopiert, verbreitet oder veröffentlicht werden.

	 Reiner Horn
	 Huaptstr. 8
	 40597 Düsseldorf
   horm.it@t-online.de
===================================================================  -->

<?php 
 
  $stmt = $db_connection->prepare(
    "SELECT * FROM p_content_diashow WHERE id=?"
  );    
  $stmt->bind_param('s', $plugin_content_id);
  $stmt->execute();
  $result = $stmt->get_result();
  if($record = $result->fetch_assoc()) {
    echo $pfad=include $_SERVER['DOCUMENT_ROOT'].$record['folder'].'.'.'php';   
  }
?>