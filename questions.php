<?php
require 'auth.php';require_admin();require 'menu.php';
$id=(int)($_GET['id']??0);$survey=one($pdo,'SELECT * FROM surveys WHERE id=?',[$id]);
if(!$survey){http_response_code(404);exit('Опрос не найден.');}$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();$pdo->beginTransaction();
 try{
  $locked=one($pdo,'SELECT * FROM surveys WHERE id=? FOR UPDATE',[$id]);
  if($locked['status']!=='Черновик')throw new RuntimeException('Вопросы можно менять только в черновике.');
  if(isset($_POST['delete_id']))$pdo->prepare('DELETE FROM questions WHERE id=? AND survey_id=?')->execute([(int)$_POST['delete_id'],$id]);
  else{
   $text=trim((string)($_POST['text']??''));if($text===''||mb_strlen($text)>250)throw new RuntimeException('Введите вопрос от 1 до 250 символов.');
   if(!empty($_POST['question_id']))$pdo->prepare('UPDATE questions SET text=? WHERE id=? AND survey_id=?')->execute([$text,(int)$_POST['question_id'],$id]);
   else $pdo->prepare('INSERT INTO questions (survey_id,text) VALUES (?,?)')->execute([$id,$text]);
  }
  $pdo->commit();go('questions.php?id='.$id);
 }catch(RuntimeException $e){$pdo->rollBack();$error=$e->getMessage();}
}
$rows=all_rows($pdo,'SELECT * FROM questions WHERE survey_id=? ORDER BY id',[$id]);page_start('Вопросы опроса'); ?>
<p><?=h($survey['title'])?> · <span class="status"><?=h($survey['status'])?></span></p><?php notice($error); ?>
<div class="panel"><p class="hint">Единая шкала: 1 — очень плохо, 2 — плохо, 3 — удовлетворительно, 4 — хорошо, 5 — отлично.</p>
<?php foreach($rows as $r): ?><div class="question"><form method="post"><?=csrf()?><input type="hidden" name="question_id" value="<?=$r['id']?>"><?php field('text','Вопрос № '.$r['id'],$r['text']);if($survey['status']==='Черновик'): ?><button class="btn btn-outline-primary btn-sm">Изменить</button> <button class="btn btn-outline-danger btn-sm" name="delete_id" value="<?=$r['id']?>">Удалить вопрос</button><?php endif; ?></form></div><?php endforeach; ?>
<?php if($survey['status']==='Черновик'): ?><h2>Новый вопрос</h2><form method="post"><?=csrf()?><?php field('text','Текст вопроса'); ?><button class="btn btn-primary">Добавить вопрос</button></form><?php endif; ?></div><a href="surveys.php">← К списку опросов</a><?php page_end(); ?>
