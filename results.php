<?php
require 'auth.php';require_admin();require 'menu.php';
$id=(int)($_GET['id']??0);$survey=one($pdo,'SELECT * FROM surveys WHERE id=?',[$id]);if(!$survey){http_response_code(404);exit('Опрос не найден.');}
$rows=all_rows($pdo,'SELECT q.id,q.text,COUNT(a.id) AS n,ROUND(AVG(a.value),2) AS mean FROM questions q LEFT JOIN answers a ON a.question_id=q.id WHERE q.survey_id=? GROUP BY q.id,q.text ORDER BY q.id',[$id]);
$count=one($pdo,'SELECT COUNT(DISTINCT a.user_id) AS n FROM answers a JOIN questions q ON q.id=a.question_id WHERE q.survey_id=?',[$id])['n'];
page_start('Результаты опроса'); ?><div class="panel"><h2><?=h($survey['title'])?></h2><span class="stat"><?=h($count)?></span> участников</div>
<div class="table-wrap"><table class="table"><thead><tr><th>Вопрос</th><th>Ответов</th><th>Средняя оценка из 5</th></tr></thead><tbody><?php foreach($rows as $r): ?><tr><td><?=h($r['text'])?></td><td><?=h($r['n'])?></td><td><?=h($r['mean']??'Нет ответов')?></td></tr><?php endforeach; ?></tbody></table></div><p class="hint mt-3">Среднее рассчитывается только по полученным ответам. Опрос не является анонимным: повторное прохождение ограничено учётной записью.</p><?php page_end(); ?>
