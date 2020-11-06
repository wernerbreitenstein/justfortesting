<?php
require "inc/db.php"; //Das PHP-Skript, das den DB-Zugriff realisiert, wird geladen oder das Programm sofort beendet.

if (isset($_GET['suchbegriff'])) {
    $suchbegriff = htmlspecialchars(trim($_GET['suchbegriff']));
    echo "Der Suchbegriff '<b>$suchbegriff</b>' ist in den folgenden Datensätzen enthalten:<br>";
}

$suchbegriff = "%{$suchbegriff}%";
$suche = $db->prepare("SELECT id, vorname, nachname, geburtsdatum, anmerkungen FROM kontakte WHERE id = ? || vorname LIKE ? || nachname LIKE ? || geburtsdatum LIKE ? || anmerkungen LIKE ?");
$suche->bind_param("issss", $suchbegriff, $suchbegriff, $suchbegriff, $suchbegriff, $suchbegriff);
$suche->execute();
$suche->bind_result($id, $vorname, $nachname, $geburtsdatum, $anmerkungen);

while($suche->fetch()) {
    echo $id . " " . $vorname . " " . $nachname . " " . $geburtsdatum . " " . $anmerkungen . "<br>";
}

?>