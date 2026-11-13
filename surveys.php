<?php
require 'records.php';
require 'menu.php';
page_start('Опросы студентов'); ?>
<div class="toolbar"><?php if(admin()): ?><a href="add.php" class="btn btn-primary">+ Новый опрос</a><?php endif; ?><span class="muted">Найдено: <?=count($rows)?></span><form><select class="form-select" name="status"><option value="">Все статусы</option><?php foreach(['Черновик','Открыт','Закрыт'] as $s): ?><option <?=$status===$s?'selected':''?>><?=h($s)?></option><?php endforeach; ?></select><button class="btn btn-outline-secondary">Показать</button></form></div>
<div class="table-wrap"><table class="table"><thead><tr><th>№</th><th>Название опроса</th><th>Вопросов</th><th>Статус</th><th>Действия</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?=h($r['id'])?></td><td><strong><?=h($r['title'])?></strong><div class="hint"><?=h($r['description'])?></div></td><td><?=h($r['n'])?></td><td><span class="status"><?=h($r['status'])?></span></td><td>
<?php if(admin()): ?><div class="actions"><a href="edit.php?id=<?=$r['id']?>">Изменить</a><a href="questions.php?id=<?=$r['id']?>">Вопросы</a><a href="results.php?id=<?=$r['id']?>">Итоги</a><?php if($r['status']==='Черновик'): ?><a href="delete.php?id=<?=$r['id']?>">Удалить</a><?php endif; ?></div>
<?php elseif($r['completed']): ?>Ответы приняты<?php elseif($r['status']==='Открыт'): ?><a href="vote.php?id=<?=$r['id']?>">Пройти опрос</a><?php else: ?>Завершён<?php endif; ?></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5">Опросов пока нет.</td></tr><?php endif; ?></tbody></table></div><?php page_end(); ?>
