<?php

require_once '../core/Auth.php';

Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /admin/index.php");
    exit;
}

if (!Auth::check()) {
    header("Location: /admin/login.php");
    exit;
}

$isAdmin = Auth::isAdmin();

Auth::logout();

if ($isAdmin) {
    header("Location: /admin/login.php");
    exit;
}

header("Location: /public/login.php");
exit;