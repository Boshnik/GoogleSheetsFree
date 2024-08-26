<?php

$_lang['googlesheets'] = 'GoogleSheets';
$_lang['gs_menu_desc'] = 'Работа с гугл таблицей';
$_lang['gs_intro_msg_error'] = 'Вы не авторизованы! Перейдите по <a href="[[+authurl]]">ссылке</a> для авторизации.';
$_lang['gs_intro_msg_success'] = 'Вы авторизованы! Вы можете  <a href="/assets/components/googlesheets/resetauth.php?authCode=[[+authCode]]">сбросить авторизацию</a>';
$_lang['gs_grid_search'] = 'Поиск';
$_lang['gs_grid_actions'] = 'Действия';
$_lang['gs_status'] = 'Статус';
$_lang['gs_sheets_link'] = 'Ссылка';
$_lang['gs_docs'] = 'Документация';

$_lang['gs_row_create'] = 'Создать';
$_lang['gs_row_update'] = 'Обновить';
$_lang['gs_row_copy'] = 'Копировать';
$_lang['gs_row_copy_confirm'] = 'Вы уверены, что хотите скопировать элемент?';
$_lang['gs_row_enable'] = 'Включить';
$_lang['gs_rows_enable'] = 'Включить выбранные элементы';
$_lang['gs_row_disable'] = 'Отключить';
$_lang['gs_rows_disable'] = 'Отключить выбранные элементы';
$_lang['gs_row_remove'] = 'Удалить';
$_lang['gs_rows_remove'] = 'Удалить выбранные элементы';
$_lang['gs_row_remove_title'] = 'Удаление';
$_lang['gs_row_remove_confirm'] = 'Вы уверены, что хотите удалить этот элемент?';
$_lang['gs_rows_remove_confirm'] = 'Вы уверены, что хотите удалить эти элементы?';
$_lang['gs_row_published'] = 'Опубликованный';

$_lang['gs_field_id'] = 'Id';
$_lang['gs_field_desc'] = 'Описание';
$_lang['gs_field_spreadsheet'] = 'Ссылка на электронную таблицу google';
$_lang['gs_field_range'] = 'Название листа';
$_lang['gs_range_desc'] = 'Пример: MyList || MyList!A1:C3';
$_lang['gs_field_sheet_id'] = 'Sheet ID';
$_lang['gs_field_export_type'] = 'Тип экспорта';
$_lang['gs_field_model_class'] = 'Класс модели';
$_lang['gs_field_where'] = 'Условия';
$_lang['gs_field_where_desc'] = 'Пример: [{"published":1,"deleted":0}]';
$_lang['gs_field_fields'] = 'Поля';
$_lang['gs_field_fields_desc'] = 'Пример: id,pagetitle,content. Или с заголовками: id==ID,pagetitle==Title,content==Content';
$_lang['gs_field_fields_empty_id'] = 'Вы должны добавить поле id';

$_lang['gs_export'] = 'Экспорт';
$_lang['gs_export_confirm'] = 'Вы уверены, что хотите запустить экспорт?';
$_lang['gs_exported_success'] = 'Всего: [[+total]] </br> Экспортировано: [[+exported]]';

$_lang['gs_object_err_nf'] = 'Объект не найден.';
$_lang['gs_object_err_ns'] = 'Объект не указан.';
$_lang['gs_object_err_remove'] = 'Ошибка при удалении объекта.';
$_lang['gs_object_err_save'] = 'Ошибка при сохранении объекта.';