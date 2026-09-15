<?php

require_once '../core/Session.php';

Session::start();

Session::set('user_id', 2);
Session::set('user_role', 'admin');

header("Location: /admin/index.php");
exit;