<?php

// Note: Commented keys already exist in webtrees translations.

use Fisharebest\Webtrees\I18N;

return [
    'Faces' => 'Faces',
    // Frontend: Modals
    'Enter individuals id or something else' => 'Введіть id особи, або щось інше',
    'This operation can not be undone' => 'Цю операцію не можна скасувати',
    'You have enabled "Create links" feature' => 'Ви ввімкнули функцію "Створювати зв’язки"',
    'Linked individuals will not be detached from media' => 'Пов’язаних осіб не буде відірвано від медіа файлів',
    'Are you sure that want delete %s from image?' => 'Ви насправді хочете видалити %s з зображення?',
    // Config: JavaScript
    'Are you sure?' => 'Ви впевнені?',
    'Read more' => 'Читати ще',
    // Config
    'Reset filters' => 'Скинути фільтри',
    // Config: Missed
    'Missed' => 'Втрачені',
    // 'Remove' => 'Видалити',
    'Remove all records without media' => 'Видалити всі записи без медіа',
    'Find' => 'Знайти',
    'Try to find missed records by filename' => 'Спробувати знайти втрачені записи за іменем файлу',
    // Config: Settings
    'Settings' => 'Налаштування',
    'Read XMP data' => 'Читати XMP дані',
    'Read and show XMP data (such as Goggle Picasa face tags) from media file' => 'Читати і показувати XMP дані (наприклад, відмітки обличчя в Goggle Picasa) із медіа файлу',
    'Create links' => 'Створити зв’язки',
    'Link individual with media when mark them on photo' => 'Пов’язувати особу з медіа при додаванні її на фото',
    'Show meta' => 'Відображати мета-дані',
    'Load and show information from linked fact' => 'Завантажувати та відображати інформацію з пов’язаного факту',
    'Show tab' => 'Відображати вкладку',
    'Show tab on individuals page' => 'Відображати вкладку на сторінці особи',
    // Config: Table
    // 'Media' => 'Медіа',
    // 'Notes' => 'Примітки',
    // 'Status' => 'Статус',
    'Actions' => 'Дії',
    // Config: Messages
    '%s record' . I18N::PLURAL . '%s records' => '%s запис' . I18N::PLURAL . '%s запису' . I18N::PLURAL . '%s записів',
    'has been deleted' . I18N::PLURAL . 'have been deleted' => 'була видалена' . I18N::PLURAL . 'було видалене' . I18N::PLURAL . 'було видалене',
    'has been repaired' . I18N::PLURAL . 'have been repaired' => 'була відновлена' . I18N::PLURAL . 'було відновлене'. 'було відновлене',
    // 'Enabled' => 'Увімкнено',
    'Disabled' => 'Вимкнено',
    'Highlight all' => 'Виділіть усі',
    'Age at' => 'Вік на фото ',
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
