<?php
require 'auth.php';
$status=(string)($_GET['status']??'');$params=[$_SESSION['user']['id']];
$sql="SELECT s.*, (SELECT COUNT(*) FROM questions q WHERE q.survey_id=s.id) AS n,
 (SELECT COUNT(*) FROM answers a JOIN questions q ON q.id=a.question_id
 WHERE q.survey_id=s.id AND a.user_id=?) AS completed FROM surveys s WHERE 1=1";
if(!admin())$sql.=" AND s.status IN ('Открыт','Закрыт')";
if($status!==''){$sql.=' AND s.status=?';$params[]=$status;}
$rows=all_rows($pdo,$sql.' ORDER BY s.id DESC',$params);
