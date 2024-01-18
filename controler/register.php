<?php
include "model/config.php";
include "model/database.php";
include "model/user.php";

if (isset($_POST['login']) && !empty($_POST['login'])) {
    $login = htmlspecialchars($_POST['login']);
    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = htmlspecialchars($_POST['password']);
        $passHash = password_hash($password, PASSWORD_BCRYPT);
    }
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        session_start();
        $response['type'] = 'Success';
        $response['data'] = 'Vous allez être redirigé';
        $response['url'] = 'index.php';
        $_SESSION['email'] = $login;
        $_SESSION['password'] = $passHash;

        $db = new PDO('mysql:host=localhost;dbname=ajax', 'root', '');
        $db->exec("INSERT INTO ajax_user(`email` ,`pass`,`role`) VALUES('$login', '$passHash', 'admin')");

        //$getUser = new User();
        //$getUser->email = $login;
        //$user = $getUser->getUserMail();
        //$_SESSION['email'] = $user->email;
        //$_SESSION['password'] = $user->pass;
        //$_SESSION['id'] = $user->id;
        //$_SESSION['role'] = $user->role;
        //$response['url'] = 'index.php?id=' . $_SESSION['id'] . '&role=' . $_SESSION['role'];
        echo json_encode($response);
    } else {
        $response['type'] = "ERROR";
        $response['data'] = 'Une erreur c\'est produite';
        echo json_encode($response);
    }
}