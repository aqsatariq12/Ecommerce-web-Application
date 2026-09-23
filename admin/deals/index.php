<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';
require_once '../../core/Deal.php';

Middleware::admin();
Session::start();

$pageTitle = "Deals";


// =========================
// FLASH MESSAGES
// =========================

$successMessage = Session::getFlash('success');
$errorMessage = Session::getFlash('error');


// =========================
// SEARCH
// =========================

$search = trim($_GET['search'] ?? '');


// =========================
// FETCH DEALS
// =========================

$sql = "SELECT
            d.id,
            d.title,
            d.subtitle,
            d.old_price,
            d.countdown_until,
            d.background_image,
            d.button_text,
            d.button_link,
            d.status,
            d.created_at,

            p.id AS product_id,
            p.name AS product_name,
            p.price AS product_price,
            p.image AS product_image

        FROM deals d

        INNER JOIN products p
            ON d.product_id = p.id

        WHERE
            d.title LIKE :search
            OR d.subtitle LIKE :search
            OR p.name LIKE :search

            OR (
                :searchStatus = 'active'
                AND d.status = 1
            )

            OR (
                :searchStatusInactive = 'inactive'
                AND d.status = 0
            )

        ORDER BY d.id DESC";


$stmt = $pdo->prepare($sql);

$searchValue = '%' . $search . '%';

$stmt->execute([
    ':search' => $searchValue,
    ':searchStatus' => strtolower($search),
    ':searchStatusInactive' => strtolower($search)
]);

$deals = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />

    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon"
        sizes="76x76"
        href="../assets/img/apple-icon.png">

    <link rel="icon"
        type="image/png"
        href="../assets/img/favicon.png">

    <title>Deals - ClothWear</title>


    <!-- Fonts -->

    <link rel="stylesheet"
        type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />


    <!-- Nucleo Icons -->

    <link href="../assets/css/nucleo-icons.css"
        rel="stylesheet" />

    <link href="../assets/css/nucleo-svg.css"
        rel="stylesheet" />


    <!-- Font Awesome -->

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- Material Icons -->

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />


    <!-- Material Dashboard -->

    <link id="pagestyle"
        href="../assets/css/material-dashboard.css?v=3.2.0"
        rel="stylesheet" />


    <style>

        /* =========================
           SEARCH
        ========================= */

        .search-wrapper {
            width: 220px;
        }


        .search-input {
            width: 100%;
        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 576px) {

            .deal-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }


            .deal-header h5 {
                font-size: 17px;
            }


            .deal-header .btn {
                width: 100%;
                text-align: center;
            }


            .deal-top-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px;
            }


            .deal-search-form {
                width: 100%;
            }


            .deal-search-form input {
                width: 100% !important;
            }

        }


        /* =========================
           SMALL SCREENS
        ========================= */

        @media (max-width: 400px) {

            .deal-search-form {
                width: 100%;
            }


            .deal-search-form input {
                font-size: 13px;
            }


            .deal-search-form button {
                font-size: 13px;
            }

        }


        /* =========================
           DEAL IMAGE
        ========================= */

        .deal-image {

            width: 55px;
            height: 55px;

            object-fit: cover;

            border-radius: 8px;

        }


        /* =========================
           PRODUCT IMAGE
        ========================= */

        .product-image {

            width: 45px;
            height: 45px;

            object-fit: cover;

            border-radius: 8px;

        }

    </style>

</head>


<body class="g-sidenav-show bg-gray-100">


    <!-- =========================
         ADMIN HEADER
    ========================= -->

    <?php require_once '../../includes/admin-header.php'; ?>


    <!-- =========================
         ADMIN SIDEBAR
    ========================= -->

    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- =========================
         MAIN CONTENT
    ========================= -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


        <!-- =========================
             PAGE CONTENT
        ========================= -->

        <div class="container-fluid py-4">


            <!-- =========================
                 FLASH SUCCESS
            ========================= -->

            <?php if ($successMessage): ?>

                <div class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <i class="fa-solid fa-circle-check me-2"></i>

                    <?= htmlspecialchars($successMessage) ?>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =========================
                 FLASH ERROR
            ========================= -->

            <?php if ($errorMessage): ?>

                <div class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <i class="fa-solid fa-circle-exclamation me-2"></i>

                    <?= htmlspecialchars($errorMessage) ?>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <!-- =========================
                 PAGE HEADER
            ========================= -->

            <div class="deal-top-header d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">


                <!-- TITLE -->

                <div>

                    <h4 class="fw-bold text-dark mb-0">
                        Deals
                    </h4>

                </div>


                <!-- SEARCH -->

                <form method="GET"
                    class="deal-search-form d-flex align-items-center gap-2">

                    <input
                        type="text"
                        name="search"
                        class="form-control border border-radius-md ps-3"
                        placeholder="Search deals..."
                        value="<?= htmlspecialchars($search) ?>"
                        style="width: 220px;">

                    <button type="submit"
                        class="btn btn-primary mb-0">

                        Search

                    </button>

                </form>

            </div>


            <!-- =========================
                 DEAL CARD
            ========================= -->

            <div class="card">


                <!-- CARD HEADER -->

                <div class="card-header pb-0">

                    <div class="deal-header d-flex justify-content-between align-items-center">


                        <h5 class="mb-0">
                            Deal Management
                        </h5>


                        <!-- ADD DEAL -->

                        <a href="create.php"
                            class="btn bg-gradient-dark btn-sm">

                            <i class="fa-solid fa-plus me-1"></i>

                            Add Deal

                        </a>

                    </div>

                </div>


                <!-- =========================
                     DEAL TABLE
                ========================= -->

                <div class="card-body px-0 pb-2">

                    <div class="table-responsive p-0">

                        <table class="table align-items-center mb-0">


                            <!-- TABLE HEAD -->

                            <thead>

                                <tr>


                                    <!-- DEAL -->

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        Deal

                                    </th>


                                    <!-- PRODUCT -->

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        Product

                                    </th>


                                    <!-- OLD PRICE -->

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        Old Price

                                    </th>


                                    <!-- COUNTDOWN -->

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        Countdown

                                    </th>


                                    <!-- STATUS -->

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        Status

                                    </th>


                                    <!-- ACTION -->

                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end pe-4">

                                        Action

                                    </th>


                                </tr>

                            </thead>


                            <!-- =========================
                                 TABLE BODY
                            ========================= -->

                            <tbody>


                                <?php if (empty($deals)): ?>


                                    <!-- EMPTY STATE -->

                                    <tr>

                                        <td colspan="6"
                                            class="text-center py-5">

                                            <i class="fa-solid fa-tags text-secondary fs-3 mb-3"></i>

                                            <p class="text-sm text-secondary mb-0">

                                                No deals found.

                                            </p>

                                        </td>

                                    </tr>


                                <?php else: ?>


                                    <?php foreach ($deals as $deal): ?>


                                        <tr>


                                            <!-- =========================
                                                 DEAL
                                            ========================= -->

                                            <td>

                                                <div class="d-flex px-3 py-1 align-items-center">


                                                    <!-- DEAL IMAGE -->

                                                    <div class="me-3">


                                                        <?php if (!empty($deal['background_image'])): ?>


                                                            <img
                                                                src="../../public/uploads/deals/<?= htmlspecialchars($deal['background_image']) ?>"
                                                                alt="<?= htmlspecialchars($deal['title']) ?>"
                                                                class="deal-image">


                                                        <?php else: ?>


                                                            <div
                                                                class="bg-gray-100 border-radius-lg d-flex align-items-center justify-content-center"
                                                                style="width:55px;height:55px;">

                                                                <i class="fa-solid fa-image text-secondary"></i>

                                                            </div>


                                                        <?php endif; ?>


                                                    </div>


                                                    <!-- DEAL INFO -->

                                                    <div class="d-flex flex-column justify-content-center">


                                                        <h6 class="mb-0 text-sm">

                                                            <?= htmlspecialchars($deal['title']) ?>

                                                        </h6>


                                                        <?php if (!empty($deal['subtitle'])): ?>

                                                            <p class="text-xs text-secondary mb-0">

                                                                <?= htmlspecialchars($deal['subtitle']) ?>

                                                            </p>

                                                        <?php endif; ?>


                                                    </div>


                                                </div>

                                            </td>


                                            <!-- =========================
                                                 PRODUCT
                                            ========================= -->

                                            <td>


                                                <div class="d-flex align-items-center">


                                                    <?php if (!empty($deal['product_image'])): ?>

                                                        <img
                                                            src="../../public/uploads/products/<?= htmlspecialchars($deal['product_image']) ?>"
                                                            alt="<?= htmlspecialchars($deal['product_name']) ?>"
                                                            class="product-image me-3">

                                                    <?php else: ?>

                                                        <div
                                                            class="bg-gray-100 border-radius-lg d-flex align-items-center justify-content-center me-3"
                                                            style="width:45px;height:45px;">

                                                            <i class="fa-solid fa-image text-secondary"></i>

                                                        </div>

                                                    <?php endif; ?>


                                                    <div>

                                                        <p class="text-xs font-weight-bold mb-0">

                                                            <?= htmlspecialchars($deal['product_name']) ?>

                                                        </p>


                                                        <p class="text-xs text-secondary mb-0">

                                                            Current:
                                                            $<?= number_format(
                                                                (float) $deal['product_price'],
                                                                2
                                                            ) ?>

                                                        </p>

                                                    </div>


                                                </div>


                                            </td>


                                            <!-- =========================
                                                 OLD PRICE
                                            ========================= -->

                                            <td>


                                                <?php if ($deal['old_price'] !== null): ?>

                                                    <p class="text-xs font-weight-bold mb-0">

                                                        $<?= number_format(
                                                            (float) $deal['old_price'],
                                                            2
                                                        ) ?>

                                                    </p>

                                                <?php else: ?>

                                                    <p class="text-xs text-secondary mb-0">

                                                        —

                                                    </p>

                                                <?php endif; ?>


                                            </td>


                                            <!-- =========================
                                                 COUNTDOWN
                                            ========================= -->

                                            <td>


                                                <?php if (!empty($deal['countdown_until'])): ?>

                                                    <p class="text-xs font-weight-bold mb-0">

                                                        <?= htmlspecialchars(
                                                            $deal['countdown_until']
                                                        ) ?>

                                                    </p>

                                                <?php else: ?>

                                                    <p class="text-xs text-secondary mb-0">

                                                        —

                                                    </p>

                                                <?php endif; ?>


                                            </td>


                                            <!-- =========================
                                                 STATUS
                                            ========================= -->

                                            <td>


                                                <?php if ((int) $deal['status'] === 1): ?>


                                                    <span class="badge bg-gradient-success">

                                                        Active

                                                    </span>


                                                <?php else: ?>


                                                    <span class="badge bg-gradient-secondary">

                                                        Inactive

                                                    </span>


                                                <?php endif; ?>


                                            </td>


                                            <!-- =========================
                                                 ACTIONS
                                            ========================= -->

                                            <td class="text-end pe-4">


                                                <!-- EDIT -->

                                                <a
                                                    href="edit.php?id=<?= (int) $deal['id'] ?>"
                                                    class="btn btn-link text-secondary p-0 me-3"
                                                    title="Edit Deal">

                                                    <i class="fa-solid fa-pen"></i>

                                                </a>


                                                <!-- STATUS -->

                                                <a
                                                    href="update-status.php?id=<?= (int) $deal['id'] ?>&status=<?= ((int) $deal['status'] === 1) ? 0 : 1 ?>"
                                                    class="btn btn-link text-warning p-0 me-3"
                                                    title="<?= ((int) $deal['status'] === 1) ? 'Deactivate Deal' : 'Activate Deal' ?>">

                                                    <?php if ((int) $deal['status'] === 1): ?>

                                                        <i class="fa-solid fa-toggle-on"></i>

                                                    <?php else: ?>

                                                        <i class="fa-solid fa-toggle-off"></i>

                                                    <?php endif; ?>

                                                </a>


                                                <!-- DELETE -->

                                                <button
                                                    type="button"
                                                    class="btn btn-link text-danger p-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteDealModal"
                                                    data-deal-id="<?= (int) $deal['id'] ?>"
                                                    data-deal-title="<?= htmlspecialchars($deal['title']) ?>"
                                                    title="Delete Deal">

                                                    <i class="fa-solid fa-trash"></i>

                                                </button>


                                            </td>


                                        </tr>


                                    <?php endforeach; ?>


                                <?php endif; ?>


                            </tbody>


                        </table>

                    </div>

                </div>

            </div>


            <!-- =========================
                 ADMIN FOOTER
            ========================= -->

            <?php require_once '../../includes/admin-footer.php'; ?>


        </div>

    </main>


    <!-- ==================================================
         DELETE DEAL MODAL
    =================================================== -->

    <div class="modal fade"
        id="deleteDealModal"
        tabindex="-1">


        <div class="modal-dialog modal-dialog-centered modal-sm">


            <div class="modal-content text-center p-3">


                <!-- ICON -->

                <div class="text-danger fs-1 mb-2">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>


                <!-- TITLE -->

                <h6 class="fw-bold">

                    Are you sure?

                </h6>


                <!-- MESSAGE -->

                <p class="small text-muted mb-3">

                    Do you want to delete

                    <strong id="deleteDealTitle"></strong>?

                </p>


                <!-- BUTTONS -->

                <div class="d-flex justify-content-center gap-2">


                    <!-- CANCEL -->

                    <button
                        type="button"
                        class="btn btn-light btn-sm"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <!-- DELETE FORM -->

                    <form
                        method="POST"
                        action="delete.php"
                        id="deleteDealForm">


                        <input
                            type="hidden"
                            name="deal_id"
                            id="deleteDealId">


                        <button
                            type="submit"
                            class="btn btn-danger btn-sm">

                            Yes, Delete

                        </button>


                    </form>


                </div>


            </div>

        </div>

    </div>


    <!-- =========================
         DELETE MODAL SCRIPT
    ========================= -->

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            const deleteModal =
                document.getElementById('deleteDealModal');


            const deleteDealId =
                document.getElementById('deleteDealId');


            const deleteDealTitle =
                document.getElementById('deleteDealTitle');


            deleteModal.addEventListener(
                'show.bs.modal',
                function (event) {


                    const button =
                        event.relatedTarget;


                    const dealId =
                        button.getAttribute(
                            'data-deal-id'
                        );


                    const dealTitle =
                        button.getAttribute(
                            'data-deal-title'
                        );


                    deleteDealId.value =
                        dealId;


                    deleteDealTitle.textContent =
                        dealTitle;


                }
            );


        });

    </script>


    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <!-- Popper -->

    <script src="../assets/js/core/popper.min.js"></script>


    <!-- Bootstrap -->

    <script src="../assets/js/core/bootstrap.min.js"></script>


    <!-- Perfect Scrollbar -->

    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>


    <!-- Smooth Scrollbar -->

    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>


    <!-- Material Dashboard -->

    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>


</body>

</html>