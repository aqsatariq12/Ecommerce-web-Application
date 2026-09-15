<?php
require_once __DIR__ . "/Session.php";
require_once __DIR__ . "/../config/database.php";

class Auth
{
    public static function login($email, $password)
    {
        Session::start();
        global $pdo;

        $sql = "SELECT * from users
                WHERE email = :email
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        //:email is a placeholder.
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch();
        if (!$user) {
            return [
                'success' => false,
                'message' => "Invalid email or Password."
            ];
        }

        if (!$user['is_active']) {
            return [
                'success' => false,
                'message' => 'Your account has been disabled'
            ];
        }

        if (!password_verify($password, $user['password'])) {
            return [
                'success' => false,
                'message' => "Invalid Email or password"
            ];
        }

        //give the user a new session ID after successful login
        session_regenerate_id(true);
        Session::set("user_id", $user["id"]);
        Session::set("user_name", $user["name"]);
        Session::set("user_email", $user["email"]);
        Session::set("user_role", $user["role"]);

        return [
            'success' => true,
            'user' => $user
        ];
    }
    public static function logout()
    {
        Session::start();
        Session::destroy();
    }

    public static function check()
    {
        Session::start();
        return Session::has('user_id');
    }

    public static function user()
    {
        Session::start();
        //SELF:: MEANS->Call another method from the same class.
        if (!self::check()) {
            return null;
        }
        return [
            'id' => Session::get('user_id'),
            'name' => Session::get('user_name'),
            'email' => Session::get('user_email'),
            'role' => Session::get('user_role'),
        ];
    }

    public static function isAdmin()
    {
        return self::check()
            && Session::get('user_role') === 'admin';
    }

    public static function isCustomer()
    {
        return self::check()
            && Session::get('user_role') === 'customer';
    }

    public static function register($name, $email, $password){
        Session::start();
        global $pdo;
        $sql = "SELECT * from users
                WHERE email= :email
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email'=> $email
        ]);

        if($stmt->fetch()){
            return[
            'success' => false,
            'message' => "An account with this email already exists."
            ];
        }

        //Hash Password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role, is_active)
        VALUES (:name, :email, :password,'customer',1)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([":name" => $name, ":email" =>$email, ":password"=> $hashedPassword]);

        return [
            'success' => true,
            'message' => "Account created Successfully"
        ];
    }
}
?>