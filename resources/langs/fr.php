<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Faces',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Donnez le n° d`ID de la personne ou un autre texte',
    'This operation can not be undone' => 'Ceci est irreversible',
    'You have enabled "Create links" feature' => 'You have enabled "Create links" feature',
    'Linked individuals will not be detached from media' => 'Linked individuals will not be detached from media',
    'Are you sure that want delete %s from image?' => 'Voulez vous vraiment effacer %s de la photo ?',
    // Config: JavaScript
    'Are you sure?' => 'Êtes-vous sur ?',
    'Read more' => 'Lisez la suite',
    // Config
    'Reset filters' => 'Reset filters',
    // Config: Missed
    'Missed' => 'Manqué',
    // 'Remove' => 'Entfernen',
    'Remove all records without media' => 'Supprimez tous les enregistrements sans média',
    'Find' => 'Trouver',
    'Try to find missed records by filename' => 'Essayez de trouver les enregistrements manqués par nom de fichier',
    // Config: Settings
    'Settings' => 'Paramètres',
    'Read XMP data' => 'Lire les données XMP',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Lire et afficher les données XMP (telles que les balises de visage Goggle Picasa) à partir d`un fichier multimédia',
    'Create links' => 'Créer des liens',
    'Link individual with media when mark them on photo' => 'Lier un individu aux médias lorsque vous les marquez sur la photo',
    'Show meta' => 'Afficher les données méta',
    'Load and show information from linked fact' => 'Charger et afficher des informations à partir de faits liés',
    'Show tab' => 'Show tab',
    'Show tab on individuals page' => 'Show tab on individuals page',
    // Config: Table
    // 'Media' => 'Media',
    // 'Notes' => 'Notizes',
    // 'Status' => 'Status',
    'Actions' => 'Actions',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s Enregistrement' . I18N::PLURAL . '%s Enregistrements',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'a été supprimé' . I18N::PLURAL . 'ont été supprimé',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'a été réparé' . I18N::PLURAL . 'ont été réparé',
    // 'Enabled' => 'Freigeschaltet',
    'Disabled' => 'Désactivé',
    'Highlight all' => 'Tout mettre en surbrillance',
    'Age at photo' => 'Âge à la photo ',
    'Missing Birth' => 'Missing Birth Date',
    'Missing fact Date' => 'Missing Fact Date',
    'Estimated date range' => 'Estimated date range: from %s to %s',
    'Date after' => 'After %s',
    'Date before' => 'Before %s',
    'LBL_SZNUPA_NOT_CONFIGURED' => 'Sznupa is not configured. Please set credentials in module settings',
    'LBL_SZNUPA_UNAVAILABLE' => 'Sznupa is unavailable',
    'LBL_SZNUPA_NOT_INDEXED' => 'The image has not been indexed in Sznupa yet. People marks were returned from webtrees database instead',
    'LBL_SZNUPA_ERROR' => 'An unknown error ocurred while communicating with Sznupa',
    'LBL_SZNUPA_ACTIVATE' => 'Activate Sznupa',
    'LBL_SZNUPA_ANALYZE' => 'Analyze',
    'LBL_SZNUPA_ANALYZE_AGAIN' => 'Analyze again',
    'LBL_SZNUPA_ANALYZE_TITLE' => 'Analyzing the photo again and mark detected faces. However, it does not edit the markings saved in Webtrees',
    'LBL_SZNUPA_ANALYZE_QUEUED' => 'The image has been queued for analyzing',
    'LBL_SZNUPA_INVALID_CREDENTIALS' => 'Invalid Sznupa credentials',
    'LBL_SZNUPA_NO_DATA' => 'The image has no marks yet. Please mark some individuals or click "Analyze" button',
];
