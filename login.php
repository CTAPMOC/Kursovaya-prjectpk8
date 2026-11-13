<?php
require 'helpers.php'; require 'db.php'; require 'menu.php';
if (!empty($_SESSION['user'])) go($cfg['home']);
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $login=trim((string)($_POST['login']??''));
    $user=one($pdo,'SELECT * FROM '.$cfg['users'].' WHERE login=?',[$login]);
    if ($user && password_verify((string)($_POST['password']??''),$user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user']=['id'=>$user['id'],'name'=>$user['name'],'role'=>$user['role']];
        go($cfg['home']);
    }
    $error='Неверный логин или пароль';
}
page_start('Вход в систему'); ?>
<div class="panel login-panel"><h2>Добро пожаловать</h2><p class="text-muted">Введите данные вашей учётной записи.</p>
<?php notice($error); ?><form method="post"><?=csrf()?>
<?php field('login','Логин',$_POST['login']??''); field('password','Пароль','','password'); ?>
<button class="btn btn-primary w-100">Войти</button></form></div>
<?php page_end(); ?>
