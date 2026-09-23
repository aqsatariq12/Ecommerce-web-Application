<?php

require_once '../../core/Middleware.php';
require_once '../../core/Session.php';
require_once '../../core/Deal.php';

Middleware::admin();
Session::start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$dealId = (int) ($_POST['deal_id'] ?? 0);

if ($dealId <= 0) {
    Session::setFlash('error', 'Invalid deal ID.');
    header('Location: index.php');
    exit;
}

try {

    // Get deal before deleting it
    $deal = Deal::getDealById($dealId);

    if (!$deal) {
        Session::setFlash('error', 'Deal not found.');
        header('Location: index.php');
        exit;
    }

    // Delete deal from database
    $deleted = Deal::delete($dealId);

    if ($deleted) {

        // Delete deal background image
        if (!empty($deal['background_image'])) {

            $imagePath = '../../public/uploads/deals/' . $deal['background_image'];

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        Session::setFlash('success', 'Deal deleted successfully.');

    } else {
        Session::setFlash('error', 'Failed to delete deal.');
    }

} catch (PDOException $e) {

    Session::setFlash(
        'error',
        'Unable to delete deal. Please try again.'
    );
}

header('Location: index.php');
exit;