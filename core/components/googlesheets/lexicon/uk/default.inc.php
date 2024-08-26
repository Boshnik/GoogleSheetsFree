<?php

$_lang['googlesheets'] = 'GoogleSheets';
$_lang['gs_menu_desc'] = 'Робота з Google таблицями';
$_lang['gs_intro_msg_error'] = 'Ви не авторизовані! Перейдіть за <a href="[[+authurl]]">посиланням</a> для авторизації.';
$_lang['gs_intro_msg_success'] = 'Ви авторизовані! Ви можете <a href="/assets/components/googlesheets/resetauth.php?authCode=[[+authCode]]">скинути авторизацію.</a>';
$_lang['gs_grid_search'] = 'Пошук';
$_lang['gs_grid_actions'] = 'Дії';
$_lang['gs_status'] = 'Статус';
$_lang['gs_sheets_link'] = 'Посилання';
$_lang['gs_docs'] = 'Документація';

$_lang['gs_row_create'] = 'Створити';
$_lang['gs_row_update'] = 'Оновити';
$_lang['gs_row_copy'] = 'Копіювати';
$_lang['gs_row_copy_confirm'] = 'Are you sure you want to copy the item?';
$_lang['gs_row_enable'] = 'Увімкнути';
$_lang['gs_rows_enable'] = 'Увімкнути вибрані елементи';
$_lang['gs_row_disable'] = 'Вимкнути';
$_lang['gs_rows_disable'] = 'Вимкнути вибрані елементи';
$_lang['gs_row_remove'] = 'Видалити';
$_lang['gs_rows_remove'] = 'Видалити вибрані елементи';
$_lang['gs_row_remove_title'] = 'Видалення';
$_lang['gs_row_remove_confirm'] = 'Ви впевнені, що хочете видалити цей елемент?';
$_lang['gs_rows_remove_confirm'] = 'Ви впевнені, що хочете видалити ці елементи?';
$_lang['gs_row_published'] = 'Опублікований';

$_lang['gs_field_id'] = 'Id';
$_lang['gs_field_desc'] = 'Опис';
$_lang['gs_field_spreadsheet'] = 'Посилання на електронну таблицю Google';
$_lang['gs_field_range'] = 'Назва вкладки';
$_lang['gs_range_desc'] = 'Приклад: MyList || MyList!A1:C3';
$_lang['gs_field_sheet_id'] = 'Sheet ID';
$_lang['gs_field_export_type'] = 'Тип експорту';
$_lang['gs_field_model_class'] = 'Клас моделі';
$_lang['gs_field_where'] = 'Умови';
$_lang['gs_field_where_desc'] = 'Приклад: [{"published":1,"deleted":0}]';
$_lang['gs_field_fields'] = 'Поля';
$_lang['gs_field_fields_desc'] = 'Приклад: id,pagetitle,content. Або з заголовками: id==ID,pagetitle==Title,content==Content';
$_lang['gs_field_fields_empty_id'] = 'Ви повинні додати поле id';

$_lang['gs_export'] = 'Експорт';
$_lang['gs_export_confirm'] = 'Ви впевнені, що хочете запустити експорт?';
$_lang['gs_exported_success'] = 'Всього: [[+total]] </br> Експортовано: [[+exported]]';

$_lang['gs_object_err_nf'] = 'Об\'єкт не знайдено.';
$_lang['gs_object_err_ns'] = 'Об\'єкт не вказано.';
$_lang['gs_object_err_remove'] = 'Помилка під час видалення об\'єкта.';
$_lang['gs_object_err_save'] = 'Помилка при збереженні об\'єкта.';