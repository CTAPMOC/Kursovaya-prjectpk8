<?php
require_once __DIR__.'/helpers.php';
if (empty($_SESSION['user'])) go('login.php');
require_once __DIR__.'/db.php';
