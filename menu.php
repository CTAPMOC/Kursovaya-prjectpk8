<?php
require_once __DIR__.'/helpers.php';
$cfg=require __DIR__.'/app.php';
function page_start(string $title): void {
    global $cfg;
    ?><!doctype html><html lang="ru"><head><meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=h($title)?> — <?=h($cfg['brand'])?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css"></head><body>
    <header><nav class="container d-flex align-items-center gap-4 py-3">
    <strong class="brand"><?=h($cfg['brand'])?></strong>
    <?php if(!empty($_SESSION['user'])): foreach($cfg['nav'] as $href=>$label): ?>
    <a href="<?=h($href)?>"><?=h($label)?></a>
    <?php endforeach; ?><span class="ms-auto small"><?=h($_SESSION['user']['name'])?></span>
    <form method="post" action="logout.php" class="m-0"><?=csrf()?><button class="btn btn-sm btn-outline-light">Выйти</button></form>
    <?php endif; ?></nav></header>
    <main class="container py-4"><div class="eyebrow"><?=h($cfg['subtitle'])?></div><h1><?=h($title)?></h1>
    <?php
}
function page_end(): void { echo '</main><footer class="container py-3">Учебный проект · МДК.09.01 · 2026</footer></body></html>'; }
