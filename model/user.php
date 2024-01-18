<?php

class User extends Database
{
    // Attributs
    public $id;
    public $email;
    public $createdAt;
    public $role;
    public $pass;
    public $token;

    /**
     * Constructeur qui se connecte à la base de données.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Méthode pour récupérer les informations d'un utilisateur via son email.
     */
    public function getUserMail()
    {
        $query = 'SELECT * FROM `ajax_user` WHERE `email`=:email;';
        $fetchProfil = $this->db->prepare($query);
        $fetchProfil->bindValue(':email', $this->email, PDO::PARAM_STR);
        $fetchProfil->execute();
        return $fetchProfil->fetch(PDO::FETCH_OBJ);
    }
    /**
     * Méthode pour générer un token CSRF.
     */
    public function generateCsrfToken()
    {
        // Génération d'un token aléatoire
        return bin2hex(random_bytes(32));
    }
    public function updateToken($token) {
        $query = 'UPDATE `ajax_user` SET `token` = :token WHERE `email` = :email;';
        $update = $this->db->prepare($query);
        $update->bindValue(':token', $token, PDO::PARAM_STR);
        $update->bindValue(':email', $this->email, PDO::PARAM_STR);
        return $update->execute();
    }   
}
