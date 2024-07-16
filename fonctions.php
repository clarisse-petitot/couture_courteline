<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/database/Database.php";

function getToken($token): Token | null

{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM token t
    JOIN utilisateur u ON t.id_utilisateur=u.id_utilisateur
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE t.token = ?");
    $stmt->bind_param("i", $token);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) == 0) {
        return null;
    }

    $res = $res[0];

    $token = new Token(
        $res["token"],
        $res["date_creation"],
        new Utilisateur($res["id_utilisateur"], $res["nom"], $res["prenom"], $res["email"], $res["rattrapage"], new Horaire($res["id_horaire"], $res["jour"], $res["heure"]), $res["role"])
    );

    $stmt->close();
    $mysqli->close();

    return $token;
}

function getAllCours($id_utilisateur): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM appel a
    JOIN cours c ON a.id_cours=c.id_cours
    JOIN horaire h ON c.id_horaire=h.id_horaire
    WHERE a.id_utilisateur = ?");
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $liste[] = new Cours($ligne["id_cours"], $ligne("date"), new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]));
    };

    return $liste;
}
