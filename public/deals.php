<?php

require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Deal.php';

$deals = Deal::getActiveDeals();

include '../includes/header.php';

?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/index-4.html  22 Nov 2019 09:53:08 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clothwear - Deals</title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - Bootstrap eCommerce Template">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery.countdown.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/skins/skin-demo-4.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">

<style>
    .deal-bottom {
    margin-top: 20px;
    margin-left: 50px !important;
}

.deal-countdown {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Individual countdown box */
.countdown-box {
    min-width: 58px;
    height: 58px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    background: rgba(255, 255, 255, 0.95);

    border-radius: 8px;

    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);

    padding: 5px 7px;
}

/* Large number */
.countdown-number {
    display: block;

    font-size: 22px;
    font-weight: 700;
    line-height: 1;

    color: #222;
}

/* Small label */
.countdown-label {
    display: block;

    margin-top: 5px;

    font-size: 9px;
    font-weight: 600;

    text-transform: uppercase;
    letter-spacing: 0.5px;

    color: #777;
}

/* : separators */
.countdown-separator {
    font-size: 22px;
    font-weight: 700;

    color: #fff;

    margin-top: -8px;
}

/* Seconds slightly highlighted */
.countdown-seconds {
    animation: countdownPulse 1.5s ease-in-out infinite;
}

@keyframes countdownPulse {

    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.04);
    }

    100% {
        transform: scale(1);
    }

}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 575px) {

    .deal-countdown {
        gap: 5px;
    }

    .countdown-box {
        min-width: 48px;
        height: 50px;
        border-radius: 6px;
    }

    .countdown-number {
        font-size: 18px;
    }

    .countdown-label {
        font-size: 8px;
        margin-top: 4px;
    }

    .countdown-separator {
        font-size: 18px;
    }

}
</style>
</head>

<body>
    <div class="page-wrapper">
        <main class="main">

            <div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">

                <div class="container">

                    <h1 class="page-title">
                        Deals & Outlet
                    </h1>

                </div>

            </div>


            <div class="page-content">

                <div class="container">

                    <div class="heading text-center mb-4 mt-4">

                        <h2 class="title">
                            All Deals & Outlet
                        </h2>

                        <p class="title-desc">
                            Explore all our latest deals and offers
                        </p>

                    </div>


                    <div class="row">

                        <?php if (!empty($deals)): ?>

                            <?php foreach ($deals as $deal): ?>

                                <div class="col-lg-6 deal-col">

                                    <div class="deal"
                                        style="background-image: url('uploads/deals/<?= htmlspecialchars($deal['background_image']) ?>');">


                                        <!-- DEAL TOP -->

                                        <div class="deal-top">

                                            <h2>
                                                <?= htmlspecialchars($deal['title']) ?>
                                            </h2>

                                            <h4>
                                                <?= htmlspecialchars($deal['subtitle']) ?>
                                            </h4>

                                        </div>


                                        <!-- DEAL CONTENT -->

                                        <div class="deal-content">

                                            <h3 class="product-title">

                                                <a href="product-detail.php?id=<?= (int) $deal['product_id'] ?>">

                                                    <?= htmlspecialchars($deal['product_name']) ?>

                                                </a>

                                            </h3>


                                            <div class="product-price">

                                                <span class="new-price">

                                                    $<?= number_format((float) $deal['new_price'], 2) ?>


                                                </span>


                                                <?php if (!empty($deal['old_price'])): ?>

                                                    <span class="old-price">

                                                        Was $<?= number_format($deal['old_price'], 2) ?>

                                                    </span>

                                                <?php endif; ?>

                                            </div>


                                            <a href="product-detail.php?id=<?= (int) $deal['product_id'] ?>"
                                                class="btn btn-link">

                                                <span>
                                                    <?= htmlspecialchars($deal['button_text']) ?>
                                                </span>

                                                <i class="icon-long-arrow-right"></i>

                                            </a>

                                        </div>


                                        <!-- DEAL BOTTOM -->

                                        <div class="deal-bottom">

                                    <?php if (!empty($deal['countdown_until'])): ?>

                                        <div class="deal-countdown"
                                            data-until="<?= date('Y-m-d\TH:i:s', strtotime($deal['countdown_until'])) ?>">
                                        </div>

                                    <?php endif; ?>

                                </div>

                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="col-12">

                                <div class="text-center py-5">

                                    <h3>
                                        No deals available right now.
                                    </h3>

                                    <p>
                                        Please check back later for new offers.
                                    </p>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </main>
        <?php include '../includes/footer.php'; ?>
    </div>
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.plugin.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>

    <script src="assets/js/jquery.countdown.min.js"></script>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/demos/demo-4.js"></script>


<script>
        $(document).ready(function () {

            $('.deal-countdown').each(function () {

                const countdownElement = $(this);
                const until = countdownElement.data('until');

                if (!until) {
                    return;
                }

                const targetDate = new Date(until);

                countdownElement.countdown({
                    until: targetDate,
                    format: 'DHMS',
                    layout:
                        '<div class="countdown-box">' +
                        '<span class="countdown-number">{dn}</span>' +
                        '<span class="countdown-label">Days</span>' +
                        '</div>' +

                        '<div class="countdown-separator">:</div>' +

                        '<div class="countdown-box">' +
                        '<span class="countdown-number">{hn}</span>' +
                        '<span class="countdown-label">Hours</span>' +
                        '</div>' +

                        '<div class="countdown-separator">:</div>' +

                        '<div class="countdown-box">' +
                        '<span class="countdown-number">{mn}</span>' +
                        '<span class="countdown-label">Minutes</span>' +
                        '</div>' +

                        '<div class="countdown-separator">:</div>' +

                        '<div class="countdown-box countdown-seconds">' +
                        '<span class="countdown-number">{sn}</span>' +
                        '<span class="countdown-label">Seconds</span>' +
                        '</div>'
                });

            });

        });
    </script>
</body>




</html>