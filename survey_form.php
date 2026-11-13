<?php
require 'auth.php';require_admin();require 'menu.php';
$r=['title'=>'','description'=>'','status'=>'Черновик'];
if($id){$r=one($pdo,'SELECT * FROM surveys WHERE id=?',[$id]);if(!$r){http_response_code(404);exit('Опрос не найден.');}}
$original=$r;$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf();$r['title']=trim((string)($_POST['title']??''));$r['description']=trim((string)($_POST['description']??''));
 $r['status']=$id?(string)($_POST['status']??''):'Черновик';
 if($r['title']===''||mb_strlen($r['title'])>150)$error='Укажите название опроса от 1 до 150 символов.';
 elseif(!in_array($r['status'],['Черновик','Открыт','Закрыт'],true))$error='Выберите допустимый статус.';
 else{
  $pdo->beginTransaction();
  try{
   if($id){
    $current=one($pdo,'SELECT * FROM surveys WHERE id=? FOR UPDATE',[$id]);
    $count=one($pdo,'SELECT COUNT(*) AS n FROM questions WHERE survey_id=?',[$id])['n'];
    $allowed=['Черновик'=>['Черновик','Открыт'],'Открыт'=>['Открыт','Закрыт'],'Закрыт'=>['Закрыт']];
    if(!in_array($r['status'],$allowed[$current['status']],true))throw new RuntimeException('Недопустимый переход статуса.');
    if($r['status']==='Открыт' && !$count)throw new RuntimeException('Добавьте хотя бы один вопрос.');
    if($current['status']!=='Черновик' && ($r['title']!==$current['title']||$r['description']!==$current['description']))throw new RuntimeException('Текст опубликованного опроса изменять нельзя.');
    $pdo->prepare('UPDATE surveys SET title=?,description=?,status=? WHERE id=?')->execute([$r['title'],$r['description'],$r['status'],$id]);
   }else $pdo->prepare('INSERT INTO surveys (title,description,status,created_by) VALUES (?,?,?,?)')->execute([$r['title'],$r['description'],'Черновик',$_SESSION['user']['id']]);
   $pdo->commit();go('surveys.php');
  }catch(RuntimeException $e){$pdo->rollBack();$error=$e->getMessage();}
 }
}
page_start($id?'Изменение опроса':'Новый опрос'); ?>
<div class="panel form-panel"><?php notice($error); ?><form method="post"><?=csrf()?>
<?php field('title','Название опроса',$r['title']);field('description','Описание',$r['description'],'text',false);
if($id)select_field('status','Статус',array_combine(['Черновик','Открыт','Закрыт'],['Черновик','Открыт','Закрыт']),$r['status']); ?>
<p class="hint">Новый опрос сохраняется как черновик. После публикации текст и вопросы фиксируются.</p>
<button class="btn btn-primary">Сохранить</button> <a href="surveys.php" class="btn btn-outline-secondary">Отмена</a></form></div><?php page_end(); ?>
