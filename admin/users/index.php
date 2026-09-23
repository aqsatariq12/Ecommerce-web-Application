<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Users";


// =========================
// FETCH USERS
// =========================

$sql = "SELECT
            id,
            name,
            email,
            role,
            is_active,
            created_at
        FROM users
        ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$users = $stmt->fetchAll();


// =========================
// USER COUNT
// =========================

$totalUsers = count($users);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon"
          sizes="76x76"
          href="../assets/img/apple-icon.png">

    <link rel="icon"
          type="image/png"
          href="../assets/img/favicon.png">

    <title>Users - ClothWear Admin</title>


    <!-- Fonts -->
    <link
        rel="stylesheet"
        type="text/css"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"
    >


    <!-- Nucleo Icons -->
    <link
        href="../assets/css/nucleo-icons.css"
        rel="stylesheet"
    >

    <link
        href="../assets/css/nucleo-svg.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <!-- Material Icons -->
    <link
        rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
    >


    <!-- Material Dashboard -->
    <link
        id="pagestyle"
        href="../assets/css/material-dashboard.css?v=3.2.0"
        rel="stylesheet"
    >


    <style>

        /* =====================================================
           PAGE HEADER
        ===================================================== */

        .users-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        .users-page-title {
            margin-bottom: 4px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #344767;
        }

        .users-page-subtitle {
            margin-bottom: 0;
            color: #8392ab;
            font-size: 0.875rem;
        }


        /* =====================================================
           USER COUNT CARD
        ===================================================== */

        .users-count-card {
            min-width: 150px;
            padding: 12px 18px;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            background: #fff;
        }

        .users-count-label {
            display: block;
            margin-bottom: 3px;
            color: #8392ab;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .users-count-value {
            color: #344767;
            font-size: 1.1rem;
            font-weight: 700;
        }


        /* =====================================================
           USERS CARD
        ===================================================== */

        .users-card {
            overflow: hidden;
            border-radius: 14px;
        }

        .users-card-header {
            padding: 20px 24px !important;
            border-bottom: 1px solid #f0f2f5;
        }

        .users-card-title {
            margin-bottom: 3px;
            color: #344767;
            font-size: 1rem;
            font-weight: 700;
        }

        .users-card-subtitle {
            margin-bottom: 0;
            color: #8392ab;
            font-size: 0.8rem;
        }


        /* =====================================================
           USER AVATAR
        ===================================================== */

        .user-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: linear-gradient(
                135deg,
                #e9efff,
                #f3f5ff
            );

            color: #5e72e4;
            font-size: 14px;
            font-weight: 700;

            text-transform: uppercase;
        }

        .user-name {
            margin-bottom: 2px;
            color: #344767;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .user-id {
            margin-bottom: 0;
            color: #8392ab;
            font-size: 0.7rem;
        }


        /* =====================================================
           EMAIL
        ===================================================== */

        .user-email {
            max-width: 250px;
            margin-bottom: 0;

            color: #67748e;
            font-size: 0.82rem;

            overflow-wrap: anywhere;
            word-break: break-word;
        }


        /* =====================================================
           ROLE BADGES
        ===================================================== */

        .user-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 6px 10px;

            border-radius: 7px;

            font-size: 0.7rem;
            font-weight: 600;
        }

        .user-role-admin {
            background: #eef0f3;
            color: #344767;
        }

        .user-role-customer {
            background: #e8f8ff;
            color: #1171ef;
        }

        .role-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }


        /* =====================================================
           STATUS SELECT
        ===================================================== */

        .user-status-select {
            min-width: 105px;

            padding: 7px 30px 7px 11px;

            border: 1px solid #e9ecef;
            border-radius: 8px;

            background-color: #f8f9fa;

            color: #344767;

            font-size: 0.72rem;
            font-weight: 600;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .user-status-select:hover {
            border-color: #adb5bd;
            background-color: #fff;
        }

        .user-status-select:focus {
            border-color: #5e72e4;
            box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.1);
        }

        .user-status-select:disabled {
            cursor: not-allowed;
            opacity: 0.65;
        }


        /* =====================================================
           SAVE BUTTON
        ===================================================== */

        .save-user-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            min-width: 75px;
            padding: 7px 12px;

            border-radius: 8px !important;

            font-size: 0.7rem !important;
            font-weight: 600 !important;

            box-shadow: none !important;
        }


        /* =====================================================
           JOINED DATE
        ===================================================== */

        .joined-date {
            color: #67748e;
            font-size: 0.78rem;
            font-weight: 500;
            white-space: nowrap;
        }


        /* =====================================================
           TABLE
        ===================================================== */

        .users-table thead th {
            padding: 14px 18px;
            background: #fafbfc;

            color: #8392ab;

            font-size: 0.65rem;
            font-weight: 700;

            text-transform: uppercase;
            letter-spacing: 0.04em;

            border-bottom: 1px solid #edf0f2;
        }

        .users-table tbody td {
            padding: 16px 18px;
            vertical-align: middle;

            border-bottom: 1px solid #f0f2f5;
        }

        .users-table tbody tr:last-child td {
            border-bottom: none;
        }

        .users-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .users-table tbody tr:hover {
            background-color: #fafbfc;
        }


        /* =====================================================
           EMPTY STATE
        ===================================================== */

        .users-empty-state {
            padding: 55px 20px !important;
            text-align: center;
        }

        .users-empty-icon {
            width: 55px;
            height: 55px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin: 0 auto 15px;

            border-radius: 50%;
            background: #f1f3f5;

            color: #8392ab;
        }

        .users-empty-title {
            margin-bottom: 5px;
            color: #344767;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .users-empty-text {
            margin-bottom: 0;
            color: #8392ab;
            font-size: 0.78rem;
        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 767.98px) {

            .users-page-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .users-count-card {
                width: 100%;
            }

            .users-page-title {
                font-size: 1.3rem;
            }

            .users-card-header {
                padding: 18px !important;
            }

            .users-table {
                min-width: 700px;
            }

            .users-table thead th,
            .users-table tbody td {
                padding-left: 14px;
                padding-right: 14px;
            }

        }


        @media (max-width: 575.98px) {

            .users-page-title {
                font-size: 1.2rem;
            }

            .users-page-subtitle {
                font-size: 0.78rem;
            }

            .users-count-card {
                padding: 10px 14px;
            }

            .users-card-header {
                padding: 16px !important;
            }

            .users-card-subtitle {
                line-height: 1.4;
            }

            .user-avatar {
                width: 38px;
                height: 38px;
                min-width: 38px;
                font-size: 12px;
            }

        }


        @media (max-width: 375px) {

            .container-fluid {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .users-page-title {
                font-size: 1.1rem;
            }

            .users-card-title {
                font-size: 0.9rem;
            }

            .users-card-subtitle {
                font-size: 0.74rem;
            }

        }

    </style>

</head>


<body class="g-sidenav-show bg-gray-100">


    <!-- =====================================================
         ADMIN HEADER
    ====================================================== -->

    <?php require_once '../../includes/admin-header.php'; ?>


    <!-- =====================================================
         ADMIN SIDEBAR
    ====================================================== -->

    <?php require_once '../../includes/admin-sidenavbar.php'; ?>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">

        <div class="container-fluid py-4">


            <!-- =================================================
                 PAGE HEADER
            ================================================== -->

            <div class="users-page-header">

                <div>

                    <h4 class="users-page-title">
                        Users Management
                    </h4>

                    <p class="users-page-subtitle">
                        Manage registered customers and administrators.
                    </p>

                </div>


                <!-- User Count -->

                <div class="users-count-card">

                    <span class="users-count-label">
                        Total Users
                    </span>

                    <span class="users-count-value">
                        <?= number_format($totalUsers) ?>
                    </span>

                </div>

            </div>


            <!-- =================================================
                 USERS CARD
            ================================================== -->

            <div class="card users-card mb-4">


                <!-- Card Header -->

                <div class="card-header users-card-header">

                    <div>

                        <h6 class="users-card-title">
                            All Users
                        </h6>

                        <p class="users-card-subtitle">
                            Review user accounts, roles and account status.
                        </p>

                    </div>

                </div>


                <!-- =================================================
                     TABLE
                ================================================== -->

                <div class="card-body px-0 pt-0 pb-0">

                    <div class="table-responsive">

                        <table class="table users-table align-items-center mb-0">


                            <!-- Table Header -->

                            <thead>

                                <tr>

                                    <th>
                                        User
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Role
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Joined
                                    </th>

                                    <th class="text-center">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <!-- Table Body -->

                            <tbody>


                            <?php if (empty($users)): ?>


                                <!-- Empty State -->

                                <tr>

                                    <td
                                        colspan="6"
                                        class="users-empty-state"
                                    >

                                        <div class="users-empty-icon">

                                            <i class="fa-solid fa-users"></i>

                                        </div>

                                        <h6 class="users-empty-title">
                                            No users found
                                        </h6>

                                        <p class="users-empty-text">
                                            There are currently no registered users.
                                        </p>

                                    </td>

                                </tr>


                            <?php else: ?>


                                <?php foreach ($users as $user): ?>


                                    <tr>


                                        <!-- =========================
                                             USER
                                        ========================== -->

                                        <td>

                                            <div class="d-flex align-items-center px-2">


                                                <!-- Avatar -->

                                                <div class="user-avatar me-3">

                                                    <?= htmlspecialchars(
                                                        strtoupper(
                                                            substr(
                                                                trim($user['name']),
                                                                0,
                                                                1
                                                            )
                                                        )
                                                    ) ?>

                                                </div>


                                                <!-- Name -->

                                                <div>

                                                    <h6 class="user-name">

                                                        <?= htmlspecialchars(
                                                            $user['name']
                                                        ) ?>

                                                    </h6>

                                                    <p class="user-id">

                                                        User #<?= (int) $user['id'] ?>

                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        <!-- =========================
                                             EMAIL
                                        ========================== -->

                                        <td>

                                            <p class="user-email">

                                                <?= htmlspecialchars(
                                                    $user['email']
                                                ) ?>

                                            </p>

                                        </td>


                                        <!-- =========================
                                             ROLE
                                        ========================== -->

                                        <td>

                                            <?php if ($user['role'] === 'admin'): ?>

                                                <span class="user-role-badge user-role-admin">

                                                    <span class="role-dot"></span>

                                                    Admin

                                                </span>

                                            <?php else: ?>

                                                <span class="user-role-badge user-role-customer">

                                                    <span class="role-dot"></span>

                                                    Customer

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- =========================
                                             STATUS
                                        ========================== -->

                                        <td>

                                            <select
                                                name="is_active"
                                                class="form-select user-status-select"
                                                form="user-form-<?= (int) $user['id'] ?>"
                                                <?= $user['role'] === 'admin' ? 'disabled' : '' ?>
                                            >

                                                <option
                                                    value="1"
                                                    <?= (int) $user['is_active'] === 1 ? 'selected' : '' ?>
                                                >
                                                    Active
                                                </option>

                                                <option
                                                    value="0"
                                                    <?= (int) $user['is_active'] === 0 ? 'selected' : '' ?>
                                                >
                                                    Inactive
                                                </option>

                                            </select>

                                        </td>


                                        <!-- =========================
                                             JOINED
                                        ========================== -->

                                        <td>

                                            <span class="joined-date">

                                                <?= date(
                                                    'd M Y',
                                                    strtotime($user['created_at'])
                                                ) ?>

                                            </span>

                                        </td>


                                        <!-- =========================
                                             ACTION
                                        ========================== -->

                                        <td class="text-center">

                                            <?php if ($user['role'] !== 'admin'): ?>


                                                <form
                                                    method="POST"
                                                    action="update-status.php"
                                                    id="user-form-<?= (int) $user['id'] ?>"
                                                    class="d-inline"
                                                >

                                                    <input
                                                        type="hidden"
                                                        name="user_id"
                                                        value="<?= (int) $user['id'] ?>"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="btn bg-gradient-dark save-user-btn mb-0"
                                                        title="Save user status"
                                                    >

                                                        <i class="fa-solid fa-check"></i>

                                                        Save

                                                    </button>

                                                </form>


                                            <?php else: ?>

                                                <span
                                                    class="text-xs text-secondary"
                                                    title="Admin account cannot be disabled"
                                                >

                                                    <i class="fa-solid fa-lock me-1"></i>

                                                    Protected

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                    </tr>


                                <?php endforeach; ?>


                            <?php endif; ?>


                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </div>


        <!-- =====================================================
             ADMIN FOOTER
        ====================================================== -->

        <?php require_once '../../includes/admin-footer.php'; ?>


    </main>


    <!-- =====================================================
         SCRIPTS
    ====================================================== -->

    <script src="../assets/js/core/popper.min.js"></script>

    <script src="../assets/js/core/bootstrap.min.js"></script>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>

    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>
