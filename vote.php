<?php
require 'auth.php';require 'menu.php';
if(admin()){http_response_code(403);exit('Для прохождения войдите как студент.');}
$id=(int)($_GET['id']??0);$survey=one($pdo,'SELECT * FROM surveys WHERE id=?',[$id]);
if(!$survey){http_response_code(404);exit('Опрос не найден.');}
$questions=all_rows($pdo,'SELECT * FROM questions WHERE survey_id=? ORDER BY id',[$id]);$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();$pdo->beginTransaction();
 try{
  $current=one($pdo,'SELECT * FROM surveys WHERE id=? FOR UPDATE',[$id]);
  if($current['status']!=='Открыт')throw new RuntimeException('Этот опрос закрыт.');
  if(one($pdo,'SELECT a.id FROM answers a JOIN questions q ON q.id=a.question_id WHERE q.survey_id=? AND a.user_id=? LIMIT 1',[$id,$_SESSION['user']['id']]))throw new RuntimeException('Вы уже прошли этот опрос.');
  if(!$questions)throw new RuntimeException('В опросе нет вопросов.');
  $values=[];
  foreach($questions as $q){
   $v=filter_var($_POST['answer'][$q['id']]??null,FILTER_VALIDATE_INT);
   if($v===false||$v<1||$v>5)throw new RuntimeException('Ответьте на все вопросы: оценка от 1 до 5.');
   $values[]=[$q['id'],$_SESSION['user']['id'],$v];
  }
  $stmt=$pdo->prepare('INSERT INTO answers (question_id,user_id,value) VALUES (?,?,?)');
  foreach($values as $v)$stmt->execute($v);
  $pdo->commit();go('surveys.php');
 }catch(RuntimeException $e){$pdo->rollBack();$error=$e->getMessage();}
}
page_start('Прохождение опроса'); ?>
<div class="panel form-panel"><h2><?=h($survey['title'])?></h2><p><?=h($survey['description'])?></p><p class="hint">1 — очень плохо · 2 — плохо · 3 — удовлетворительно · 4 — хорошо · 5 — отлично</p><?php notice($error); ?>
<form method="post"><?=csrf()?><?php foreach($questions as $i=>$q): ?><div class="question"><strong><?=($i+1)?>. <?=h($q['text'])?></strong><div class="rating"><?php for($v=1;$v<=5;$v++): ?><label><input type="radio" name="answer[<?=$q['id']?>]" value="<?=$v?>" required <?=((string)($_POST['answer'][$q['id']]??''))===(string)$v?'checked':''?>> <?=$v?></label><?php endfor; ?></div></div><?php endforeach; ?>
<button class="btn btn-primary">Отправить ответы</button> <a class="btn btn-outline-secondary" href="surveys.php">Отмена</a></form></div><?php page_end(); ?>
