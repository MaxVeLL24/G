<?php

// Заголовки на странице
define('LISTING_PAGE_HEADER', 'Manufacturers');
define('CREATE_PAGE_HEADER', 'Create manufacturer');
define('EDIT_PAGE_HEADER', 'Edit manufacturer #%d "%s"');
define('UPLOADED_IMAGE_HEADER', 'Manufacturer\'s logo');
define('IMAGE_UPLOAD_HEADER', 'Upload image');

// Имена колонорк в таблице со списком производителей
define('LISTING_TABLE_ID_COLUMN_HEADING', '#');
define('LISTING_TABLE_NAME_COLUMN_HEADING', 'Name');
define('LISTING_TABLE_DATE_ADDED_COLUMN_HEADING', 'Date created');
define('LISTING_TABLE_DATE_MODIFIED_COLUMN_HEADING', 'Date modified');
define('LISTING_TABLE_STATUS_COLUMN_HEADING', 'State');
define('LISTING_TABLE_ACTION_COLUMN_HEADING', 'Action');

// Статусы
define('STATUS_ENABLED', 'Enabled');
define('STATUS_DISABLED', 'Disabled');

// Сменить статус
define('ENABLE', 'Enable');
define('DISABLE', 'Disable');

// Действия
define('EDIT', 'Edit');
define('DELETE', 'Delete');
define('DELETE_IMAGE', 'Remove image');
define('DELETE_CONFIRMATION_REQUEST', 'Please, conirm removal.');
define('SEND', 'Send');
define('CREATE', 'Create');

// Сообщения
define('MESSAGE_MANUFACTURER_DELETED', 'The manufacturer was successfully deleted.');
define('MESSAGE_MANUFACTURER_DOES_NOT_EXISTS', 'Manufacturer does not exists.');
define('MESSAGE_NO_MANUFACTURERS', 'There are no manufacturers yet.');
define('MESSAGE_SAVED', 'Successfully saved.');
define('MESSAGE_SAVE_TO_UPLOAD_IMAGE', 'Image uploading will be available after saving.');
define('MESSAGE_IMAGE_NOT_UPLOADED', 'Image not uploaded yet.');
define('MESSAGE_IMAGE_DELETED', 'The image was successfully deleted.');
define('MESSAGE_IMAGE_NOT_UPLOADED', 'Image not uploaded.');
define('MESSAGE_IMAGE_UPLOADING_ERROR', 'An error occurred while uploading an image.');
define('MESSAGE_BAD_IMAGE_ERROR', 'Uploaded file is not correct JPEG, PNG or GIF image.');
define('MESSAGE_WAS_UPLOADED_SUCCESSFULLY', 'Image was uploaded successfully.');

// Название полей формы редактирования/создания производителя
define('NAME_LABEL', 'Name');
define('NAME_HEADER', 'Header');
define('STATUS_LABEL', 'State');
define('STATUS_YES', 'Enabled');
define('STATUS_NO', 'Disabled');
define('TITLE_LABEL', 'Browser page title');
define('KEYWORDS_LABEL', 'Keywords');
define('META_DESCRIPTION_LABEL', 'Meta description');
define('DESCRIPTION_LABEL', 'Description<br><small><i>(Text that displays on manufacturer\'s page)</i></small>');
define('SAVE_BUTTON', 'Save');
define('LINK_CANCEL', 'Cancel');

// Информация о изображении
define('PIXELS', 'px.');
define('BYTES', ' byte');
define('KILOBYTES', ' KiB');
define('MEGABYTES', ' MiB');
define('GIGABYTES', ' GiB');

// Пагинация
define('PAGINATION', 'Page <b>%d</b> from <b>%d</b>');
define('PREV', 'Prev.');
define('NEXT', 'Next.');