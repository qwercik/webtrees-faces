<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Andlit',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Sláðu inn persónuauðkenni eða eitthvað annað',
    'This operation can not be undone' => 'Ekki er hægt að afturkalla þessa aðgerð',
    'You have enabled "Create links" feature' => 'Þú hefur virkjað eiginleikann „Búa til tengla“',
    'Linked individuals will not be detached from media' => 'Tengdir einstaklingar verða ekki aðskildir frá gögnum',
    'Are you sure that want delete %s from image?' => 'Ertu viss um að þú viljir eyða %s frá mynd?',
    // Config: JavaScript
    'Are you sure?' => 'Ertu viss?',
    'Read more' => 'Lesa meira',
    // Config
    'Reset filters' => 'Endurstilla síur',
    // Config: Missed
    'Missed' => 'Tapað',
    // 'Remove' => 'Remove',
    'Remove all records without media' => 'Fjarlægðu allar skrár án gagna',
    'Find' => 'Find',
    'Try to find missed records by filename' => 'Reyndu að finna töpuð gögn eftir skráarnafni',
    // Config: Settings
    'Settings' => 'Stillingar',
    'Read XMP data' => 'Lesa XMP lýsigögn',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Lestu og sýndu XMP lýsigögn (eins og Goggle Picasa andlitsmerki) úr gagnaskrá',
    'Create links' => 'Búðu til tengla',
    'Link individual with media when mark them on photo' => 'Tengdu einstakling við gögn þegar þú merkir hann á mynd',
    'Show meta' => 'Sýna lýsigögn',
    'Load and show information from linked fact' => 'Hlaða og sýna upplýsingar frá tengdri staðreynd',
    'Show tab' => 'Sýna flipa',
    'Show tab on individuals page' => 'Sýna flipa á síðu einstaklinga',
    // Config: Table
    // 'Media' => 'Media',
    // 'Notes' => 'Notes',
    // 'Status' => 'Status',
    'Actions' => 'Aðgerðir',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s færsla' . I18N::PLURAL . '%s færslur',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'hefur verið eytt' . I18N::PLURAL . 'hefur verið eytt',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'hefur verið lagfærð' . I18N::PLURAL . 'hafa verið lagfærðar',
    // 'Enabled' => 'Enabled',
    'Disabled' => 'Afvirkjað',
    'Highlight all' => 'Áherslumerktu allt',
    'Age at' => 'Age at photo ',
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
