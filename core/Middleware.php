<?php

require_once __DIR__ . "/Auth.php";

class Middleware
{
    public static function admin()
    {
        Session::start();

        // Not logged in → Admin login
        if (!Auth::check()) {
            header("Location: /admin/login.php");
            exit;
        }

        // Customer trying to access admin → Customer home
        if (Auth::isCustomer()) {
            header("Location: /public/index.php");
            exit;
        }
    }


    public static function customer()
    {
        Session::start();

        // Not logged in → Customer login
        if (!Auth::check()) {
            header("Location: /public/login.php");
            exit;
        }

        // Admin trying to access customer page → Admin dashboard
        if (Auth::isAdmin()) {
            header("Location: /admin/index.php");
            exit;
        }
    }

    public static function guest()
    {
        Session::start();

        // Admin already logged in
        if (Auth::isAdmin()) {
            header("Location: /admin/index.php");
            exit;
        }

        // Customer already logged in
        if (Auth::isCustomer()) {
            header("Location: /public/index.php");
            exit;
        }

        // Nobody logged in → allow login/register page
    }
}
?>