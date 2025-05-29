<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Oznaczanie osób',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Wprowadź osobę lub podpis',
    'This operation can not be undone' => 'Ta operacja jest nieodwracalna',
    'You have enabled "Create links" feature' => 'Masz włączoną funkcjonalność "Utwórz powiązania"',
    'Linked individuals will not be detached from media' => 'Powiązania osób z plikiem multimedialnym nie zostaną usunięte',
    'Are you sure that want delete %s from image?' => 'Czy jesteś pewien, że chcesz usunąć %s ze zdjęcia?',
    // Config: JavaScript
    'Are you sure?' => 'Czy jesteś pewien?',
    'Read more' => 'Dowiedz się więcej',
    // Config
    'Reset filters' => 'Zresetuj filtry',
    // Config: Missed
    'Missed' => 'Brakujące',
    // 'Remove' => 'Remove',
    'Remove all records without media' => 'Usuń wszystkie rekordy bez multimediów',
    'Find' => 'Znajdź',
    'Try to find missed records by filename' => 'Spróbuj odnaleźć brakujące rekordy po nazwie pliku',
    // Config: Settings
    'Settings' => 'Ustawienia',
    'Read XMP data' => 'Czytaj metadane XMP',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Czytaj i pokazuj metadane XMP (takie jak znaczniki twarzy z Goggle Picasa) z pliku multimedialnego',
    'Create links' => 'Twórz powiązania',
    'Link individual with media when mark them on photo' => 'Powiąż osoby z plikiem multimedialnym, gdy zaznaczysz je na zdjęciu',
    'Show meta' => 'Pokaż metadane',
    'Load and show information from linked fact' => 'Załaduj i pokaż informacje z powiązanych faktów',
    'Show tab' => 'Pokaż zakładkę',
    'Show tab on individuals page' => 'Pokaż zakładkę na stronie osoby',
    // Config: Table
    // 'Media' => 'Media',
    // 'Notes' => 'Notes',
    // 'Status' => 'Status',
    'Actions' => 'Akcje',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s rekord' . I18N::PLURAL . '%s rekordów',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'został usunięty' . I18N::PLURAL . 'Zostały usunięte',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'został naprawiony' . I18N::PLURAL . 'zostały naprawione',
    // 'Enabled' => 'Enabled',
    'Disabled' => 'Wyłączony',
    'Highlight all' => 'Pokaż wszystkie',
    'Age at' => 'Wiek na zdjęciu: ',
    'Missing Birth' => 'Brakuje daty urodzenia',
    'Missing fact Date' => 'Brakuje daty faktu',
    'Estimated date range' => 'Oszacowana data: pomiędzy %s a %s',
    'Date after' => 'Po %s',
    'Date before' => 'Przed %s',
    'LBL_SZNUPA_NOT_CONFIGURED' => 'Sznupa nie została jeszcze skonfigurowana. Przejdź do ustawień modułu i wprowadź dane dostępowe',
    'LBL_SZNUPA_UNAVAILABLE' => 'Sznupa jest obecnie niedostępna',
    'LBL_SZNUPA_NOT_INDEXED' => 'Zdjęcie nie zostało jeszcze zaindeksowane w Sznupie. Oznaczenia osób pochodzą z bazy danych webtrees.',
    'LBL_SZNUPA_ERROR' => 'Wystąpił nieznany błąd podczas komunikacji z Sznupą',
    'LBL_SZNUPA_ACTIVATE' => 'Aktywuj Sznupę',
    'LBL_SZNUPA_ANALYZE' => 'Analizuj',
    'LBL_SZNUPA_ANALYZE_AGAIN' => 'Analizuj ponownie',
    'LBL_SZNUPA_ANALYZE_TITLE' => 'Analizuje zdjęcie i oznacza wykryte twarze. Nie edytuje jednak oznaczeń zapisanych w Webtrees',
    'LBL_SZNUPA_ANALYZE_QUEUED' => 'Zdjęcie zostało zlecone do ponownej analizy. Może to chwilę potrwać.',
    'LBL_SZNUPA_INVALID_CREDENTIALS' => 'Nieprawidłowe dane dostępowe do Sznupy',
    'LBL_SZNUPA_NO_DATA' => 'Zdjęcie nie ma jeszcze żadnych oznaczeń. Oznacz jakąś osobę lub kliknij przycisk "Analizuj"',
];
