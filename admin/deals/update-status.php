<?php

require_once '../../core/Middleware.php';
require_once '../../core/Session.php';
require_once '../../core/Deal.php';

Middleware::admin();
Session::start();

$dealId = (int) ($_GET['id'] ?? 0);
$status = isset($_GET['status']) ? (int) $_GET['status'] : -1;

// Validate request
if ($dealId <= 0 || !in_array($status, [0, 1], true)) {

    Session::setFlash(
        'error',
        'Invalid deal status request.'
    );

    header('Location: index.php');
    exit;
}

try {

    // Check if deal exists
    $deal = Deal::getDealById($dealId);

    if (!$deal) {

        Session::setFlash(
            'error',
            'Deal not found.'
        );

        header('Location: index.php');
        exit;
    }

    // Update deal status
    $updated = Deal::updateStatus($dealId, $status);

    if ($updated) {

        if ($status === 1) {

            Session::setFlash(
                'success',
                'Deal activated successfully.'
            );

        } else {

            Session::setFlash(
                'success',
                'Deal deactivated successfully.'
            );
        }

    } else {

        Session::setFlash(
            'error',
            'Failed to update deal status.'
        );
    }

} catch (PDOException $e) {

    Session::setFlash(
        'error',
        'Unable to update deal status. Please try again.'
    );
}

header('Location: index.php');
exit;