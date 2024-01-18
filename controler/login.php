<?php
include "../model/config.php";
include "../model/database.php";
include "../model/user.php";

if (isset($_POST['login']) && !empty($_POST['login'])) {
    $login = htmlspecialchars($_POST['login']);
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = htmlspecialchars($_POST['password']);
    }
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $getUser = new User();
        $getUser->email = $login;
        $user = $getUser->getUserMail();
        if (is_object($user)) {
            if (password_verify($password, $user->pass)) {
                session_start();
                $_SESSION['email'] = $user->email;
                $_SESSION['id'] = $user->id;
                $_SESSION['role'] = $user->role;

                // Generate a new token
                $token = bin2hex(random_bytes(32));
                $_SESSION['token'] = $token;
                
                // met a jour le token dans la base de données
                $getUser->updateToken($token);

                $response['url'] = 'index.php?id=' . $_SESSION['id'] . '&role=' . $_SESSION['role'] . '&token=' . $token;
                $response['type'] = 'Success';
                $response['data'] = 'Vous allez être redirigé';
                echo json_encode($response);
            }
        } else {
            $response['type'] = 'Error';
            $response['data'] = 'Aucun utilisateur n\'existe';
            echo json_encode($response);
        }
    } else {
        $response['type'] = "ERROR";
        $response['data'] = 'Une erreur c\'est produite';
        echo json_encode($response);
    }
}
