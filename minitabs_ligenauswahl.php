<?php

/**
 * minitabs - Ligenauswahl
 *
 * Umschaltung zwischen mehreren Ligen mit minitab und/oder mininext.
 * Diese Datei gehört in das LMO-Hauptverzeichnis (neben init.php).
 */

require_once __DIR__ . '/init.php';

// Eingabe nur zum Vergleichen mit der Ligenliste verwenden (wird nie ausgegeben)
$mannschaft = isset($_GET['mannschaft']) && is_string($_GET['mannschaft'])
    ? trim(strip_tags($_GET['mannschaft']))
    : '';

$ligenpfad = PATH_TO_LMO . '/' . $dirliga;
$ligen_tmp = [];
$ligen = [];

// Vorauswahl, damit nicht jedes Mal im Quelltext geändert werden muss.
// Es wird anschließend geprüft, welche Ligendateien vorhanden sind.
$ligen_tmp[] = ['dateiname' => 'akl1112', 'bez' => 'Herren', 'value' => 'herren'];
$ligen_tmp[] = ['dateiname' => 'bdamen1112', 'bez' => 'Damen', 'value' => 'damen'];
$ligen_tmp[] = ['dateiname' => 'zbjugend1112', 'bez' => 'B-Jugend', 'value' => 'bjugend'];
$ligen_tmp[] = ['dateiname' => 'zcjugend1112', 'bez' => 'C-Jugend', 'value' => 'cjugend'];
$ligen_tmp[] = ['dateiname' => 'zdjugend1112', 'bez' => 'D-Jugend', 'value' => 'djugend'];
$ligen_tmp[] = ['dateiname' => 'zejugend1112', 'bez' => 'E-Jugend', 'value' => 'ejugend'];
$ligen_tmp[] = ['dateiname' => 'zfjugend1112', 'bez' => 'F-Jugend', 'value' => 'fjugend'];

// Prüfen, welche Ligendatei wirklich vorhanden ist -> in neues Array speichern
foreach ($ligen_tmp as $liga_tmp) {
    if (file_exists($ligenpfad . $liga_tmp['dateiname'] . '.l98')) {
        $ligen[] = $liga_tmp;
    }
}
unset($ligen_tmp, $liga_tmp);

// Gewählte Liga bestimmen; ohne (gültige) Auswahl wird der erste Eintrag genommen
$auswahl = 0;
foreach ($ligen as $i => $liga_eintrag) {
    if ($mannschaft === $liga_eintrag['value']) {
        $auswahl = $i;
        break;
    }
}
$file = null;
if ($ligen) {
    // für minitabs + mininext
    $file = $mini_liga = $ligen[$auswahl]['dateiname'] . '.l98';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <title>minitabs - Ligenauswahl</title>

  <style type="text/css">
    body {
      margin: 0;
      margin-left: 11px;
    }

    /* für lmo minitabs size 12 */
    table.minitabs {
      font-size: 12pt;
      margin-top: 0.4em;
    }

    acronym {
      border-bottom: 0;
    }

    option {
      color: #D8E4EC;
      background-color: #889CB0;
      font-size: 11px;
    }

    input, select {
      font-family: Verdana, sans-serif;
      font-size: 10px;
      color: #000;
      background-color: #B9C4CC;
      border: 1px dotted #000;
    }

    th.minitabs {
      color: #D8E4EC;
      background-color: #889CB0;
      text-align: center;
      font-size: 90%;
      padding: 3px 0;
      border-radius: 0.3em;
    }

    form { display: inline; }

    .nextgame caption, .nextgame td, .nextgame th { font-family: sans-serif; font-size: 8pt; }
    .nextgame table { border: none; border-collapse: collapse; }
    .nextgame .lost { color: #900; }
    li.lost, li.win, li.draw { font-size: 8pt; }
    .nextgame .win { color: #090; }
    .nextgame .draw { color: #999; }
    .nextgame .noResult { color: #000; }
    .nextgame .result { font-weight: bold; font-size: 100%; }
    .nextgame ul { margin: 0.1em 0; padding: 0; list-style: none; }
    .nextgame caption { color: #D8E4EC; background: #889CB0; font-weight: bold; padding: 0.2em; margin-top: 6px; border-radius: 0.3em; }
    .nextgame acronym { cursor: help; border-bottom: 1px dotted; }
    .nextgame small { font-size: 11px; }

    div {
      padding: 0;
      margin: 0;
    }
  </style>
</head>

<body>

<table class="minitabs" border="0">
  <tr>
    <th class="minitabs" valign="middle">Aktuelle Tabelle</th>
  </tr>
  <tr>
    <th class="minitabs" valign="middle">
      <form method="get" name="dl_jumpbox" action="<?= htmlspecialchars(basename($_SERVER['PHP_SELF']), ENT_QUOTES, 'UTF-8') ?>">
        <select name="mannschaft" onchange="this.form.submit()">
<?php
// Dropdown mit vorhandenen Ligen füllen
foreach ($ligen as $i => $liga_eintrag) {
    echo '          <option' . ($i === $auswahl ? ' selected="selected"' : '')
        . ' value="' . htmlspecialchars($liga_eintrag['value'], ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($liga_eintrag['bez'], ENT_QUOTES, 'UTF-8') . "</option>\n";
}
?>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Los" class="liteoption">
      </form>
    </th>
  </tr>
<?php if ($file !== null) { ?>

  <tr>
    <td colspan="2">
<?php
        $mini_ueber = 14;
    $mini_unter = 14;
    $mini_template = 'standard';
    include __DIR__ . '/addon/mini/lmo-minitab.php';
    ?>
    </td>
  </tr>

  <tr>
    <td colspan="2" align="center">
<?php
            $mini_template = 'mininext_all.tpl.php';
    include __DIR__ . '/addon/mini/lmo-mininext.php';
    ?>
    </td>
  </tr>
<?php } ?>
</table>

</body>
</html>