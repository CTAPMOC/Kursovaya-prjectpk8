<?php
require 'auth.php';require_admin();require 'menu.php';
$id=(int)($_GET['id']??0);$r=one($pdo,'SELECT * FROM surveys WHERE id=?',[$id]);if(!$r){http_response_code(404);exit('Опрос не найден.');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();$pdo->beginTransaction();
 $r=one($pdo,'SELECT * FROM surveys WHERE id=? FOR UPDATE',[$id]);
 if($r && $r['status']==='Черновик'){
  $pdo->prepare('DELETE FROM surveys WHERE id=?')->execute([$id]);$pdo->commit();go('surveys.php');
 }
 $pdo->rollBack();$error='Удалять можно только черновики.';
}
page_start('Удаление опроса'); ?><div class="panel form-panel"><?php notice($error); ?><p>Удалить опрос «<?=h($r['title'])?>» вместе с его вопросами? Действие нельзя отменить.</p><form method="post"><?=csrf()?><button class="btn btn-danger">Удалить</button> <a class="btn btn-outline-secondary" href="surveys.php">Отмена</a></form></div><?php page_end(); ?>
