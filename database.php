<?php
require 'auth.php';require_admin();require 'menu.php';
page_start('Структура базы данных'); ?>
<p class="text-muted">База <?=h($config['database'])?> · кодировка utf8mb4 · таблицы InnoDB</p>
<div class="table-wrap"><table class="table"><thead><tr><th>Таблица</th><th>Строк</th><th>Поля</th></tr></thead><tbody>
<?php foreach($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN) as $table):
$count=$pdo->query('SELECT COUNT(*) FROM `'.$table.'`')->fetchColumn();
$columns=$pdo->query('SHOW COLUMNS FROM `'.$table.'`')->fetchAll(); ?>
<tr><td><strong><?=h($table)?></strong></td><td><?=h($count)?></td><td><?=h(implode(', ',array_column($columns,'Field')))?></td></tr>
<?php endforeach; ?></tbody></table></div><?php page_end(); ?>
