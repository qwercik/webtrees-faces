<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Etiquetar Fotos',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Ingresa el nombre de la persona a etiquetar o un texto descriptivo',
    'This operation can not be undone' => 'Esta operación no se puede deshacer',
    'You have enabled "Create links" feature' => 'Ha habilitado la función "Crear vinculos"',
    'Linked individuals will not be detached from media' => 'Las personas vinculadas a la imagen seguirán mostrandore en la pesñana Faces',
    'Are you sure that want delete %s from image?' => '¿Estás seguro de que quieres desetiquetar a %s de la imagen?',
    // Config: JavaScript
    'Are you sure?' => 'Estás seguro?',
    'Read more' => 'Leer más',
    // Config
    'Reset filters' => 'Restablecer filtros',
    // Config: Missed
    'Missed' => 'Omitido',
    // 'Remove' => 'Eliminar',
    'Remove all records without media' => 'Eliminar todos los registros sin multimedia',
    'Find' => 'Buscar',
    'Try to find missed records by filename' => 'Intente encontrar registros perdidos por el nombre del archivo',
    // Config: Settings
    'Settings' => 'Configuración',
    'Read XMP data' => 'Leer datos XMP',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Leer y mostrar datos XMP (como las etiquetas faciales de Goggle Picasa) del archivo multimedia',
    'Create links' => 'Crear vinculos',
    'Link individual with media when mark them on photo' => 'Vincular a la persona con el archivo multimedia cuando los etiquetes en las fotos',
    'Show meta' => 'Mostrar metadatos',
    'Load and show information from linked fact' => 'Cargar y mostrar información de hechos vinculados',
    'Show tab' => 'Mostrar pestaña',
    'Show tab on individuals page' => 'Mostrar pestaña en la pagina de la persona',
    // Config: Table
    // 'Media' => 'Media',
    // 'Notes' => 'Notas',
    // 'Status' => 'Estado',
    'Actions' => 'Acciones',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s registro' . I18N::PLURAL . '%s registros',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'ha sido eliminados' . I18N::PLURAL . 'han sido eliminados',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'ha sido reparado' . I18N::PLURAL . 'han sido reparados',
    // 'Enabled' => 'Activado',
    'Disabled' => 'Desactivado',
    'Highlight all' => 'Resaltar todo',
    'Age at' => 'Edad en la foto ',
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
