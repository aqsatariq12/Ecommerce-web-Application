<?php
class Session
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        //Remove specific variable stored in the current session.
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        //Remove all variables stored in the current session.
        session_unset();
        session_destroy();
    }


    /*Check if a temporary message exists. If it exists, give it to me and immediately delete it so it can only be shown once.

    flash session stored in session like..
    So the session could look like:

SESSION
│
├── user_id → 10
│
└── _flash
    ├── success → "Product added successfully!"
    └── error   → "Something went wrong!"
    */


    public static function getFlash($key)
    {
        if (!isset($_SESSION['_flash'][$key])) {
            return null;
        }
        $message = $_SESSION['_flash'][$key];
        unset($_SESSION['_flash'][$key]);
        return $message;
    }
}
?>