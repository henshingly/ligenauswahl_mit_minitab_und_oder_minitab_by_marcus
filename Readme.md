# minitabs – Ligenauswahl

Wenn im LMO mehrere Ligen vorhanden sind (z. B. Herren, Damen, Jugend), lassen sie sich mit diesem Skript
auf engstem Raum darstellen: ein Dropdown schaltet zwischen den Ligen um, darunter erscheinen
**minitab** (Tabellenauszug) und/oder **mininext** (nächstes Spiel mit Bilanz gegen den Gegner).

Idee und Erstversion: Forum des BC Erlbach (Download „Ligenauswahl mit minitab und/oder mininext“).
Hier überarbeitet für PHP 8 und die aktuelle LMO-Version.

## Voraussetzungen

- LMO (PHP-8-Version) mit den Addons `addon/mini/lmo-minitab.php` und `addon/mini/lmo-mininext.php`
- Optional: das Paket `mininext_mehr_php8.zip` (Template `mininext_all.tpl.php`, Archiv-Spiele ein-/ausklappbar)

## Installation

1. `minitabs_ligenauswahl.php` in das **LMO-Hauptverzeichnis** kopieren (dorthin, wo `init.php` liegt).
2. Die Liga-Liste oben in der Datei anpassen:

   ```php
   $ligen_tmp[] = ['dateiname' => 'akl1112', 'bez' => 'Herren', 'value' => 'herren'];
   ```

   | Feld        | Bedeutung                                                         |
   |-------------|-------------------------------------------------------------------|
   | `dateiname` | Name der Ligendatei **ohne** `.l98` (Ordner: Ligenordner des LMO) |
   | `bez`       | Text im Dropdown                                                  |
   | `value`     | Kurzname für die URL (`?mannschaft=herren`), nur Buchstaben/Zahlen |

   Es dürfen beliebig viele Einträge stehen. Ligen, deren Datei nicht existiert, werden automatisch
   ausgelassen. Die Beispieleinträge (`…1112`) stammen aus der Saison 2011/12 und müssen angepasst werden.
3. Seite im Browser aufrufen: `https://deine-domain/lmo/minitabs_ligenauswahl.php`

Ohne Auswahl wird die erste vorhandene Liga angezeigt. Eine unbekannte oder manipulierte Eingabe
in `?mannschaft=` führt ebenfalls zur ersten Liga. Sind gar keine Ligendateien vorhanden, erscheint nur das
leere Dropdown.

## Einstellungen

Im unteren Teil der Datei:

```php
$mini_ueber = 14;                       // Zeilen über der Mannschaft in minitab
$mini_unter = 14;                       // Zeilen unter der Mannschaft in minitab
$mini_template = 'standard';            // Template für minitab
$mini_template = 'mininext_all.tpl.php'; // Template für mininext
```

- Nur **minitab** gewünscht: den mininext-Block (zweite `<tr>`) entfernen.
- Nur **mininext** gewünscht: den minitab-Block (erste `<tr>` mit `lmo-minitab.php`) entfernen.
- Archiv für mininext: optional `$folder = 'archiv/auswahl';` vor dem mininext-Include setzen.
- Anzahl der zuerst sichtbaren Archiv-Spiele (nur mit dem Paket `mininext_mehr_php8.zip`):
  `$mini_anzahlSpiele = 4;` vor dem mininext-Include.

## In eine bestehende Seite einbinden

Die Seite ist eigenständig. Zum Einbetten z. B. per `<iframe>`:

```html
<iframe src="/lmo/minitabs_ligenauswahl.php" width="220" height="700" frameborder="0"></iframe>
```

Bei sehr schmalen Boxen lässt sich das Aussehen im `<style>`-Block der Datei anpassen
(Farben `#889CB0` / `#D8E4EC`, Schriftgrößen).

## Änderungen gegenüber der Vorlage

- PHP 8: kein Zugriff mehr auf nicht gesetzte `$_GET`-Werte, klare Klammerung der Auswahlbedingung
- Eingabe wird nur mit der Ligenliste verglichen und nie ausgegeben; alle Ausgaben mit `htmlspecialchars`
- Ligenordner über `PATH_TO_LMO` und `$dirliga` statt festem `ligen/`
- `init.php` wird selbst geladen (startet die Session), eigenes `session_start()` entfällt
- Includes über `__DIR__`, unabhängig vom Arbeitsverzeichnis
- HTML5 mit UTF-8 statt XHTML 1.0 / iso-8859-1, `border-radius` statt `-moz-border-radius`
- Keine Aufrufe von minitab/mininext, wenn keine Liga vorhanden ist
- PSR-12-Formatierung (php-cs-fixer), UTF-8, LF

## Lizenz

Wie der LMO: GNU General Public License, Version 2 oder höher. Die Urheberhinweise der LMO-Addons
(`lmo-minitab.php`, `lmo-mininext.php`) dürfen nicht entfernt oder geändert werden.