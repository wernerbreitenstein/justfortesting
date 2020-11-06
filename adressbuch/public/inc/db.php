<?php
//Warn- und Fehlermeldungen des Systems während des Entwicklungsbetriebs einschalten
//error_reporting(E_ALL);
//Warn- und Fehlermeldungen des Systems während des produktiven Betriebs ausschalten
//error_reporting(0);

$datetime = date("d. F Y, H:i:s") . " Uhr: "; //Datum und Uhrzeit ermitteln und speichern

//objektorientierten Datenbankzugriff herstellen
$db = new mysqli("localhost", "root", "root", "adressbuch");
//utf8-Zeichensatz bei der Datenbankabfrage verwenden, um u.a. Umlaute darstellen zu können
$db->set_charset('utf8');

//Datenbankverbindung prüfen und Fehler-/Erfolgsmeldungen ausgeben
if ($db->connect_errno) {
    echo "<em>$datetime</em>";
    die("<strong><em>Fehler " . $db->connect_errno . ", " . $db->connect_error . "</em></strong>");
} else {
    $result = $db->query("SELECT DATABASE()");
    $row = $result->fetch_row();
    //echo "<em>$datetime</em>";
    //echo "<strong><em>Die Verbindung zur Datenbank " . "'$row[0]'" . " wurde erfolgreich aufgebaut.</em></strong><br><br>";
}
?>
