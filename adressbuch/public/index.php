<?php
require "inc/db.php"; //das PHP-Skript, das den DB-Zugriff realisiert, wird geladen

$rows = []; //leeres Array zur späteren Aufnahme von Tupeln
$columns = []; //leeres Array zur späteren Aufnahme von Attributen

//SELECT-STATEMENT zum Abfragen von Datensätzen
//Die Ergebnismenge der DB-Abfrage wird in einem mysqli-Objekt (Result Object) gespeichert.
$result = $db->query("SELECT * FROM kontakte") OR die($db->error);
//resultObjectAusgeben($result);

if ($result->num_rows) {
    //tupel- bzw. zeilenweise Ausgabe von Datensätzen
    //über das direkte Auslesen von Objektattributen
    while ($obj = $result->fetch_object()) {
        $rows[] = $obj;
        //echo $row->vorname . " " . $row->nachname . "<br>";
    }
    $columns = get_object_vars($rows[0]);
    $result->free(); //die Variable $result freigeben
} else {
    echo "Keine Datensätze im Abfrageergebnis.";
}
//rowsArrayAusgeben($rows);

// ====== VARIABLEN ====== ANFANG ======//

// ====== VARIABLEN ====== ENDE ======//

// ====== FUNKTIONEN zur Erzeugung von HTML-Seitenelementen ====== ANFANG ====== //
/**
 * Diese Funktion erzeugt den Beginn eines HTML-Dokuments bis hin zur H1 innerhalb des body-Elements.
 * @param $headline
 */
function writeHeaderAndHeadline($headline)
{
    echo "<!DOCTYPE html>
          <html lang=\"de\">
          <head><title>PHP-/MySQL-Übung</title></head>
          <body>
          <!-- <h1>$headline</h1> -->";
}

/**
 * Diese Funktion erzeugt den Beginn eines form-Elements.
 * @param $method
 * @param $url
 * @param $id
 */
function startForm($method, $url, $id)
{
    echo "<form method=\"$method\" action=\"$url\" id=\"$id\">";
    echo "<fieldset>";
}

/**
 * Diese Funktion erzeugt ein input-Element (einfaches Textfeld).
 * @param $label
 * @param $forNameId
 * @param $type
 * @param $value ,    enthält einen Default-Wert: ""
 */
function writeInputField($label, $forNameId, $type, $value = "")
{
    echo "<label for=\"$forNameId\">$label
          <input type=\"$type\" name=\"$forNameId\" size=\"50\" id=\"$forNameId\" value=$value></label>";
}

/**
 * Diese Funktion erzeugt ein textarea-Element (mehrzeiliges Textfeld).
 * @param $label
 * @param $forNameId
 */
function writeTextareaField($label, $forNameId, $value = "")
{
    echo "<label for=\"$forNameId\">$label
          <textarea name=\"$forNameId\" rows=\"5\" cols=\"42\" id=\"$forNameId\">$value</textarea></label>";
}

// Schaltflächen für die Formulare anzeigen
function buttonsAnzeigen($modusAendern, $id)
{
    if ($modusAendern == false) {
        writeInputField("", "aktion", "hidden", "speichern");
        echo "<label for=\"submit\"><input type=\"submit\" name=\"speichern\" id=\"speichern\" value=\"Speichern\"></label>";
        echo "<label for=\"reset\"><input type=\"reset\" name=\"reset\" id=\"reset\" value=\"Zurücksetzen\"></label>";
    } else {
        writeInputField("", "aktion", "hidden", "korrigieren");
        writeInputField("", "id", "hidden", $id);
        echo "<label for=\"submit\"><input type=\"submit\" name=\"korrigieren\" id=\"korrigieren\" value=\"Ändern\"></label>";
        echo "<label for=\"reset\"><input type=\"reset\" name=\"reset\" id=\"reset\" value=\"Abbrechen\"></label>";
    }
}

/**
 * Diese Funktion erzeugt das Ende eines form-Elements und beendet ebenfalls ein HTML-Dokument.
 * @param $buttons ,  Schaltflächen werden als Heredoc-Elemente eingebunden
 */
function closeFormAndFooter($modusAendern, $id)
{
    echo "</fieldset>";
    buttonsAnzeigen($modusAendern, $id);
    echo "</form>
          </body>
          </html>";
}
// ====== FUNKTIONEN zur Erzeugung von HTML-Seitenelementen ====== ENDE ====== //

// ====== FUNKTIONEN zur Erzeugung von PHP-Formularen ====== ANFANG ====== //
function ergebnistabelleAusgeben($tabellenName, $rows, $columns)
{
    echo "<h3>$tabellenName</h3>";

    echo <<<tableBegin
    <table>
        <thead>
            <tr>
tableBegin;
    echo "<td></td>";
    echo "<td></td>";
    foreach ($columns as $key => $value) {
        echo "<td>$key</td>";
    }
    echo <<<tableMiddle
            </tr>
        </thead>
        <tbody>
tableMiddle;
    foreach ($rows as $content) {
        echo "<tr>";
        echo "<td><a href=\"?aktion=bearbeiten&id=$content->id\">ändern</a></td>";
        echo "<td><a href=\"?aktion=loeschen&id=$content->id\">löschen</a></td>";
        echo "<td>$content->id</td>";
        echo "<td>$content->vorname</td>";
        echo "<td>$content->nachname</td>";
        echo "<td>$content->geburtsdatum</td>";
        echo "<td>$content->anmerkungen</td>";
        echo "</tr>";
    }
    echo <<<tableEnd
        </tbody>
    </table>
tableEnd;
}
// ====== FUNKTIONEN zur Erzeugung von PHP-Formularen ====== ENDE ====== //

// ====== HILFSFUNKTIONEN ====== ANFANG ====== //
//den Inhalt des mysqli-Objekts testweise ausgeben
function resultObjectAusgeben($result)
{
    echo "<pre>";
    print_r($result);
    echo "</pre>";
}

//den Inhalt des $rows-Arrays mit den darin enthaltenen Objekten testweise ausgeben
function rowsArrayAusgeben($rows)
{
    echo "<pre>";
    print_r($rows);
    echo "</pre>";
}

//den Inhalt des $_POST-Arrays ausgeben
function postArrayAusgeben()
{
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
}
// ====== HILFSFUNKTIONEN ====== ENDE ====== //

// ====== HAUPTPROGRAMM ====== //

// === Ändern von Datensätzen === ANFANG === //
//Ausgabe der Ergebnistabelle
$modusAendern = false;
if (isset($_GET['aktion']) && $_GET['aktion'] == "bearbeiten") {
    $modusAendern = true;
}

if (!$modusAendern) {
    ergebnistabelleAusgeben("Abfrageergebnis", $rows, $columns);
} else {
    // === Ändern von Datensätzen === ANFANG === //
    if (isset($_GET['id'])) {
        $id = (int) trim($_GET['id']);
        if ($id > 0) {
            $update = $db->prepare("SELECT id, vorname, nachname, geburtsdatum, anmerkungen FROM kontakte WHERE id = ?");
            $update->bind_param("i", $id);
            $update->execute();
            $update->bind_result($id, $vorname, $nachname, $geburtsdatum, $anmerkungen);
            while ($update->fetch()) {
                echo "<h3>Daten ändern</h3>";
                //echo $id . " " . $vorname . " " . $nachname . " " . $geburtsdatum . " " . $anmerkungen;
            }
        }
    }
}

if (isset($_POST['aktion']) && $_POST['aktion'] == "korrigieren") {
    print_r($_POST);
    $id = "";
    if (isset($_POST['id'])) {
        $id = (int) trim($_POST['id']);
    }
    $vorname = "";
    if (isset($_POST['vorname'])) {
        $vorname = htmlspecialchars(trim($_POST['vorname']));
    }
    $nachname = "";
    if (isset($_POST['nachname'])) {
        $nachname = htmlspecialchars(trim($_POST['nachname']));
    }
    $geburtsdatum = "";
    if (isset($_POST['geburtsdatum'])) {
        $geburtsdatum = htmlspecialchars(trim($_POST['geburtsdatum']));
    }
    $anmerkungen = "";
    if (isset($_POST['anmerkungen'])) {
        $anmerkungen = htmlspecialchars(trim($_POST['anmerkungen']));
    }

    if ($id != "" && ($vorname != "" || $nachname != "" || $geburtsdatum != "" || $anmerkungen != "")) {
        $update = $db->prepare("UPDATE kontakte SET vorname = ?, nachname = ?, geburtsdatum = ?, anmerkungen = ? WHERE id = ? LIMIT 1");
        $update->bind_param("ssssi", $vorname, $nachname, $geburtsdatum, $anmerkungen, $id);
        if ($update->execute()) {
            header("location: index.php?aktion=datenGeaendert");
        }
    }
}

//Dem Nutzer wird bestätigt, dass der neue Datensatz eingefügt wurde.
if (isset($_GET['aktion']) && $_GET['aktion'] == "datenGeaendert") {
    echo "<em style=value:green>Daten wurden erfolgreich geändert.</em>";
}
// === Ändern von Datensätzen === ENDE === //

// === Einfügen von Datensätzen === ANFANG === //
if (isset($_POST['aktion']) && $_POST['aktion'] == "speichern") {
    $vorname = "";
    if (isset($_POST['vorname'])) {
        $vorname = htmlspecialchars(trim($_POST['vorname']));
    }
    $nachname = "";
    if (isset($_POST['nachname'])) {
        $nachname = htmlspecialchars(trim($_POST['nachname']));
    }
    $geburtsdatum = "";
    if (isset($_POST['geburtsdatum'])) {
        $geburtsdatum = htmlspecialchars(trim($_POST['geburtsdatum']));
    }
    $anmerkungen = "";
    if (isset($_POST['anmerkungen'])) {
        $anmerkungen = htmlspecialchars(trim($_POST['anmerkungen']));
    }

    if ($vorname != "" && $nachname != "") {
        //INSERT-STATEMENT zum Einfügen von Datensätzen
        $insert = $db->prepare("INSERT INTO kontakte(vorname, nachname, geburtsdatum, anmerkungen) VALUES(?, ?, ?, ?)");
        $insert->bind_param("ssss", $vorname, $nachname, $geburtsdatum, $anmerkungen);
        //das PHP-Skript wird erneut aufgerufen, so dass der neue Datensatz sofort erscheint
        //zur Weiterverarbeitung wird beim Aufruf in der Variable 'aktion' ein Wert mitgegeben
        if ($insert->execute()) {
            header("location: index.php?aktion=neueDatenGespeichert");
        } else {
            header("location: index.php?aktion=keineDatenGespeichert");
        }
    } else {
        echo "<em style=value:red>Es wurden keine Daten eingegeben.</em>";
    }
}

//Dem Nutzer wird bestätigt, dass der neue Datensatz eingefügt wurde.
if (isset($_GET['aktion']) && $_GET['aktion'] == "neueDatenGespeichert") {
    echo "<em style=value:green>Es wurden neue Daten gespeichert.</em>";
}

//Dem Nutzer wird gemeldet, dass der neue Datensatz NICHT eingefügt wurde.
if (isset($_GET['aktion']) && $_GET['aktion'] == "keineDatenGespeichert") {
    echo "<em style=value:red>Es wurden keine Daten gespeichert.</em>";
}
// === Einfügen von Datensätzen === ENDE === //

// === Löschen von Datensätzen === Anfang === //
if (isset($_GET['aktion']) && $_GET['aktion'] == "loeschen") {
    if (isset($_GET['id'])) {
        $id = (int) trim($_GET['id']);
        if ($id > 0) {
            //DELETE-STATEMENT zum Löschen von Datensätzen
            $delete = $db->prepare("DELETE FROM kontakte WHERE id = ? LIMIT 1");
            $delete->bind_param("i", $id);
            //das PHP-Skript wird erneut aufgerufen, so dass der alte Datensatz sofort verschwindet
            //zur Weiterverarbeitung wird beim Aufruf in der Variable 'aktion' ein Wert mitgegeben
            if ($delete->execute()) {
                header("location: index.php?aktion=datenGeloescht");
                //echo "<em style=value:green>Es wurde der Datensatz $id gelöscht.</em>";
            } else {
                header("location: index.php?aktion=keinedatenGeloescht");
                //echo "<em style=value:red>Es wurden keine Daten gelöscht.</em>";
            }
        }
    }
}

//Dem Nutzer wird bestätigt, dass der alte Datensatz gelöscht wurde.
if (isset($_GET['aktion']) && $_GET['aktion'] == "datenGeloescht") {
    echo "<em style=value:green>Es wurden Daten gelöscht.</em>";
}

//Dem Nutzer wird gemeldet, dass der alte Datensatz NICHT gelöscht wurde.
if (isset($_GET['aktion']) && $_GET['aktion'] == "keinedatenGeloescht") {
    echo "<em style=value:red>Es wurden keine Daten gelöscht.</em>";
}
// === Löschen von Datensätzen === ENDE === //

// === Ausgabe des Eingabeformulars === ANFANG === //
if (!isset($id)) {
    $id = "";
}
if (!isset($vorname)) {
    $vorname = "";
}
if (!isset($nachname)) {
    $nachname = "";
}
if (!isset($geburtsdatum)) {
    $geburtsdatum = "";
}
if (!isset($anmerkungen)) {
    $anmerkungen = "";
}
writeHeaderAndHeadline("PHP-/MySQL-Übung");
startForm("post", htmlspecialchars($_SERVER["PHP_SELF"]), "formular");
writeInputField("Vorname", "vorname", "text", $vorname);
writeInputField("Nachname", "nachname", "text", $nachname);
writeInputField("Geburtsdatum", "geburtsdatum", "text", $geburtsdatum);
writeTextareaField("Anmerkungen", "anmerkungen", $anmerkungen);
closeFormAndFooter($modusAendern, $id);
// === Ausgabe des Eingabeformulars === ENDE === //

//Beenden der Datenbankverbindung
$db->close();

?>

<!-- CSS-Ruleset -->
<style>
    fieldset {
        border: none;
    }

    input {
        display: block;
        height: 1.5rem;
        margin: 0.25rem 0 0.5rem 0;
    }

    textarea {
        display: block;
        margin: 0.25rem 0 0.5rem 0;
    }

    input[type="submit"], input[type="reset"] {
        display: inline-block;
        margin-right: 1.0rem;
    }
</style>
