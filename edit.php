<?php
$id=(int)($_GET['id']??0);if($id<1){http_response_code(404);exit('Опрос не найден.');}require 'survey_form.php';
