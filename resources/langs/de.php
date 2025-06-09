<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Faces',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Gib die ID der Person oder sonstiges ein',
    'This operation can not be undone' => 'Dies kann nicht rückgängig gemacht werden',
    'You have enabled "Create links" feature' => 'Du hast das Merkmal "Erzeuge Links" aktiviert',
    'Linked individuals will not be detached from media' => 'Verknüpfte Personen werden nicht von den Medien getrennt',
    'Are you sure that want delete %s from image?' => 'Möchtest Du %s wirklich vom Bild löschen?',
    // Config: JavaScript
    'Are you sure?' => 'Bist Du sicher?',
    'Read more' => 'Weiterlesen',
    // Config
    'Reset filters' => 'Filter zurücksetzen',
    // Config: Missed
    'Missed' => 'Fehlend',
    // 'Remove' => 'Entfernen',
    'Remove all records without media' => 'Entferne alle Einträge ohne Bild',
    'Find' => 'Finde',
    'Try to find missed records by filename' => 'Versuche fehlende Einträge anhand des Dateinamens zu finden',
    // Config: Settings
    'Settings' => 'Parameter',
    'Read XMP data' => 'Lese XMP Daten',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Lese und zeige XMP Daten (wie Goggle Picasa Gesichts Tag) aus der Bilddatei',
    'Create links' => 'Erzeuge Verknüpfungen',
    'Link individual with media when mark them on photo' => 'Verknüpfe die Person mit dem Bild wenn sie auf dem Foto markiert ist',
    'Show meta' => 'Zeige Meta-Daten',
    'Load and show information from linked fact' => 'Lade und zeige die Information vom verlinkten Eintrag',
    'Show tab' => 'Zeige Reiter',
    'Show tab on individuals page' => 'Reiter auf der Personenseite anzeigen',
    // Config: Table
    // 'Media' => 'Media',
    // 'Notes' => 'Notizen',
    // 'Status' => 'Status',
    'Actions' => 'Aktionen',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s Datensatz' . I18N::PLURAL . '%s Datensätze',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'wurde gelöscht' . I18N::PLURAL . 'wurden gelöscht',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'wurde repariert' . I18N::PLURAL . 'wurden repariert',
    // 'Enabled' => 'Freigeschaltet',
    'Disabled' => 'Gesperrt',
    'Highlight all' => 'Alle hervorheben',
    'Age at' => 'Alter auf dem Foto ',
    'Missing Birth' => 'Geburtstag fehlt',
    'Missing fact Date' => 'Faktdatum fehlt',
    'Estimated date range' => 'geschätzte Zeitspanne: von %s zu %s',
    'Date after' => 'nach %s',
    'Date before' => 'vor %s',
    'LBL_SZNUPA_NOT_CONFIGURED' => 'Sznupa ist nicht konfiguriert. Bitte lege die Anmeldeinformationen in den Moduleinstellungen fest',
    'LBL_SZNUPA_UNAVAILABLE' => 'Sznupa ist nicht verfügbar',
    'LBL_SZNUPA_NOT_INDEXED' => 'Das Bild wurde in Sznupa noch nicht indiziert. Die Personen wurden stattdessen aus der Webtrees-Datenbank zurückgegeben',
    'LBL_SZNUPA_ERROR' => 'Ein unbekannter Fehler ist während der Kommunikation mit Sznupa aufgetreten',
    'LBL_SZNUPA_ACTIVATE' => 'Aktiviere Sznupa',
    'LBL_SZNUPA_ANALYZE' => 'Analysieren',
    'LBL_SZNUPA_ANALYZE_AGAIN' => 'Erneut analysieren',
    'LBL_SZNUPA_ANALYZE_TITLE' => 'Es wird das Foto erneut analysiert und entdeckte Gesichter markiert. Es werden jedoch nicht die in Webtrees gespeicherten Markierungen bearbeitet',
    'LBL_SZNUPA_ANALYZE_QUEUED' => 'Das Bild wurde zur Analyse in die Warteschlange gestellt',
    'LBL_SZNUPA_INVALID_CREDENTIALS' => 'Ungültige Sznupa Anmeldeinformationen',
    'LBL_SZNUPA_NO_DATA' => 'Das Bild hat noch keine Markierungen. Bitte markiere einige Personen oder klicke auf die Schaltfläche "Analysieren"',
];
