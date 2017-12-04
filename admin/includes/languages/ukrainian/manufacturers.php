<?php

// Заголовки на странице
define('LISTING_PAGE_HEADER', 'Производители');
define('CREATE_PAGE_HEADER', 'Добавить производителя');
define('EDIT_PAGE_HEADER', 'Редактировать производителя %d "%s"');
define('UPLOADED_IMAGE_HEADER', 'Логотип производителя');
define('IMAGE_UPLOAD_HEADER', 'Загрузить изображение');

// Имена колонорк в таблице со списком производителей
define('LISTING_TABLE_ID_COLUMN_HEADING', '#');
define('LISTING_TABLE_NAME_COLUMN_HEADING', 'Название');
define('LISTING_TABLE_DATE_ADDED_COLUMN_HEADING', 'Создано');
define('LISTING_TABLE_DATE_MODIFIED_COLUMN_HEADING', 'Изменено');
define('LISTING_TABLE_STATUS_COLUMN_HEADING', 'Статус');
define('LISTING_TABLE_ACTION_COLUMN_HEADING', 'Действие');

// Статусы
define('STATUS_ENABLED', 'Включено');
define('STATUS_DISABLED', 'Выключено');

// Сменить статус
define('ENABLE', 'Включить');
define('DISABLE', 'Выключить');

// Действия
define('EDIT', 'Редактировать');
define('DELETE', 'Удалить');
define('DELETE_IMAGE', 'Удалить изображение');
define('DELETE_CONFIRMATION_REQUEST', 'Подтвердите удаление');
define('SEND', 'Отправить');
define('CREATE', 'Создать');

// Сообщения
define('MESSAGE_MANUFACTURER_DELETED', 'Производитель был успешно удалён.');
define('MESSAGE_MANUFACTURER_DOES_NOT_EXISTS', 'Производитель не найден.');
define('MESSAGE_NO_MANUFACTURERS', 'Нет производителей.');
define('MESSAGE_SAVED', 'Сохранено успешно.');
define('MESSAGE_SAVE_TO_UPLOAD_IMAGE', 'Загрузка изображения станет доступна после сохранения.');
define('MESSAGE_IMAGE_NOT_UPLOADED', 'Изображение пока не загружено.');
define('MESSAGE_IMAGE_DELETED', 'Изображение было успешно удалено.');
define('MESSAGE_IMAGE_NOT_UPLOADED', 'Изображение не загружено.');
define('MESSAGE_IMAGE_UPLOADING_ERROR', 'Во время загрузки изображения произошла ошибка.');
define('MESSAGE_BAD_IMAGE_ERROR', 'Загруженный файл не является корректным изображением JPEG, PNG или GIF.');
define('MESSAGE_WAS_UPLOADED_SUCCESSFULLY', 'Изображение было загружено успешно');

// Название полей формы редактирования/создания производителя
define('NAME_LABEL', 'Название');
define('NAME_HEADER', 'Заголовок');
define('STATUS_LABEL', 'Включено');
define('STATUS_YES', 'Да');
define('STATUS_NO', 'Нет');
define('TITLE_LABEL', 'Заголовок страницы в браузере');
define('KEYWORDS_LABEL', 'Ключевые слова');
define('META_DESCRIPTION_LABEL', 'Метаописание');
define('DESCRIPTION_LABEL', 'Описание<br><small><i>(Текст, который выводится на странице производителя)</i></small>');
define('SAVE_BUTTON', 'Сохранить');
define('LINK_CANCEL', 'Отмена');

// Информация о изображении
define('PIXELS', 'пикс.');
define('BYTES', ' байт');
define('KILOBYTES', ' КБ');
define('MEGABYTES', ' МБ');
define('GIGABYTES', ' ГБ');

// Пагинация
define('PAGINATION', 'Страница <b>%d</b> из <b>%d</b>');
define('PREV', 'Пред.');
define('NEXT', 'След.');