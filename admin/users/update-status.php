<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Session::setFlash('error', 'Invalid request.');
    header('Location: index.php');
    exit;
}

$userId = (int) ($_POST['user_id'] ?? 0);
$isActive = isset($_POST['is_active']) ? (int) $_POST['is_active'] : -1;

if ($userId <= 0) {
    Session::setFlash('error', 'Invalid user ID.');
    header('Location: index.php');
    exit;
}

// Only allow 0 or 1
if (!in_array($isActive, [0, 1], true)) {
    Session::setFlash('error', 'Invalid user status.');
    header('Location: index.php');
    exit;
}

// Check user exists
$sql = "SELECT id, role
        FROM users
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $userId
]);

$user = $stmt->fetch();

if (!$user) {
    Session::setFlash('error', 'User not found.');
    header('Location: index.php');
    exit;
}

// Do not allow admin to deactivate an admin account
if ($user['role'] === 'admin' && $isActive === 0) {
    Session::setFlash(
        'error',
        'Admin account cannot be deactivated.'
    );

    header('Location: index.php');
    exit;
}

// Update status
$sql = "UPDATE users
        SET is_active = :is_active
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':is_active' => $isActive,
    ':id' => $userId
]);

Session::setFlash(
    'success',
    'User status updated successfully.'
);

header('Location: index.php');
exit;