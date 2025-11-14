<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Лица',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Введите id персоны или что-нибудь еще',
    'This operation can not be undone' => 'Эту операцию нельзя отменить',
    'You have enabled "Create links" feature' => 'У вас включена опция "Создавать связи"',
    'Linked individuals will not be detached from media' => 'Связанные персоны не будут отвязаны от медиа файла',
    'Are you sure that want delete %s from image?' => 'Вы действительно хотите удалить %s с изображения?',
    // Config: JavaScript
    'Are you sure?' => 'Вы уверены?',
    'Read more' => 'Подробнее',
    // Config
    'Reset filters' => 'Сбросить фильтры',
    // Config: Missed
    'Missed' => 'Потерянные',
    // 'Remove' => 'Удалить',
    'Remove all records without media' => 'Удалить все записи без медиа',
    'Find' => 'Найти',
    'Try to find missed records by filename' => 'Попытаться найти потерянные записи по имени',
    // Config: Settings
    'Settings' => 'Настройки',
    'Read XMP data' => 'Читать XMP данные',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Читать и показывать XMP данные (например отметки лиц в Goggle Picasa) из медиа файла',
    'Create links' => 'Создавать связи',
    'Link individual with media when mark them on photo' => 'Связывать персону с медиа при добавлении ее на фото',
    'Show meta' => 'Показывать мету',
    'Load and show information from linked fact' => 'Загружать и показывать информацию из связанного факта',
    'Show tab' => 'Показывать вкладку',
    'Show tab on individuals page' => 'Показывать вкладку на странице персоны',
    // Config: Table
    // 'Media' => 'Медиа',
    // 'Notes' => 'Примечания',
    // 'Status' => 'Статус',
    'Actions' => 'Действия',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s запись' . I18N::PLURAL . '%s записи' . I18N::PLURAL . '%s записей',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'была удалена' . I18N::PLURAL . 'было удалено' . I18N::PLURAL . 'было удалено',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'была восстановлена' . I18N::PLURAL . 'было восстановлено'. 'было восстановлено',
    // 'Enabled' => 'Включено',
    'Disabled' => 'Выключено',
    'Highlight all' => 'Подсветить всех',
    'Age at' => 'Возраст на фото ',
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
