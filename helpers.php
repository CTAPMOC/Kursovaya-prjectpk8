<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('cw_' . basename(__DIR__));
    session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax']);
    session_start();
}
function h($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function go(string $url): never { header('Location: '.$url); exit; }
function csrf(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf" value="'.h($_SESSION['csrf']).'">';
}
function check_csrf(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? '')) || empty($_SESSION['csrf'])) {
        http_response_code(403); exit('Форма устарела. Обновите страницу.');
    }
}
function admin(): bool { return ($_SESSION['user']['role'] ?? '') === 'admin'; }
function require_admin(): void {
    if (!admin()) { http_response_code(403); exit('Недостаточно прав.'); }
}
function one(PDO $pdo, string $sql, array $params=[]): array|false {
    $stmt=$pdo->prepare($sql); $stmt->execute($params); return $stmt->fetch();
}
function all_rows(PDO $pdo, string $sql, array $params=[]): array {
    $stmt=$pdo->prepare($sql); $stmt->execute($params); return $stmt->fetchAll();
}
function field(string $name, string $label, $value='', string $type='text', bool $required=true): void {
    echo '<div class="mb-3"><label class="form-label" for="'.h($name).'">'.h($label).'</label>';
    echo '<input class="form-control" id="'.h($name).'" name="'.h($name).'" type="'.h($type).'" value="'.h($value).'"'.($required?' required':'').'></div>';
}
function select_field(string $name,string $label,array $options,$value=''): void {
    echo '<div class="mb-3"><label class="form-label" for="'.h($name).'">'.h($label).'</label><select class="form-select" id="'.h($name).'" name="'.h($name).'">';
    foreach($options as $key=>$title) echo '<option value="'.h($key).'"'.((string)$key===(string)$value?' selected':'').'>'.h($title).'</option>';
    echo '</select></div>';
}
function notice(string $message): void {
    if($message!=='') echo '<div class="alert alert-danger" role="alert">'.h($message).'</div>';
}
