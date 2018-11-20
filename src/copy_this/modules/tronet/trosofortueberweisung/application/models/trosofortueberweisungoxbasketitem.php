<?php
/**
 * @link          http://www.tro.net
 * @copyright (c) tronet GmbH 2018
 * @author        tronet GmbH
 *
 * @since         7.0.4
 * @version       7.0.6
 */
class trosofortueberweisungoxbasketitem extends trosofortueberweisungoxbasketitem_parent
{
        // Klasse wird im Modul nicht mehr benötigt, allerdings gibt es einen Bug im Oxid.
        // Wird das Modul beim Updaten nicht gelöscht und danach die Tabelle oxconfig bereinigt,
        // wird die Klasse weiterhin überladen, auch wenn sie aus der metadata.php entfernt wurde.
        // Auch Modul Deaktivieren/Aktivieren führt zu keinem Ergebnis.
        // Erst wenn man die Datei manuell löscht und dann im Backend bestätigt,
        // dass eine ungültige Datei aus der Tabelle oxconfig entfernt wird,
        // wird diese nicht mehr überladen.
        // Um Probleme zu vermeiden mit Shop-Betreibern, die einfach nur die neuen Modul-Dateien hochladen,
        // wird diese leere Datei zur Verfügung gestellt.
}
