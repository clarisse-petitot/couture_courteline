<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/database/Database.php";
require_once __DIR__ . "/../fonctions.php";

function getJour($jour): string
{
    $jour = substr($jour, 0, 2);
    if ($jour == "L ") {
        return "Lundi";
    }
    if ($jour == "Ma") {
        return "Mardi";
    }
    if ($jour == "Me") {
        return "Mercredi";
    }
    if ($jour == "J ") {
        return "Jeudi";
    }
    if ($jour == "V ") {
        return "Vendredi";
    }
    if ($jour == "S ") {
        return "Samedi";
    }
    if ($jour == "D ") {
        return "Dimanche";
    }
}

function getDateTime($date, $horaire)
{
    $res = substr($date, 6, 4) . '-' . substr($date, 3, 2) . '-' . substr($date, 0, 2) . ' ';
    if (getJour($horaire) == "Mardi" || getJour($horaire) == "Mercredi") {
        if ($horaire[4] == "H") {
            $res = $res . substr($horaire, 3, 1) . ':' . substr($horaire, 5, 2);
        } else {
            $res = $res . substr($horaire, 3, 2) . ':' . substr($horaire, 6, 2);
        }
    } else {
        if ($horaire[3] == "H") {
            $res = $res . substr($horaire, 2, 1) . ':' . substr($horaire, 4, 2);
        } else {
            $res = $res . substr($horaire, 2, 2) . ':' . substr($horaire, 5, 2);
        }
    }
    $res = $res . ':00';
    return $res;
}

function setCoursHoraire($chemin)
{
    $lignes = [];
    $file = fopen($chemin, "r");
    $pattern_date = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
    $pattern_horaire = "/^[A-Za-z]+ \d{1,2}H\d{2}-\d{1,2}H\d{2}$/";
    while (($ligne = fgetcsv($file, null, ';')) !== false) {
        if (preg_match($pattern_date, $ligne[0]) && preg_match($pattern_horaire, $ligne[2])) {
            $lignes[] = $ligne;
        }
    }
    fclose($file);

    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE FROM cours");
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM horaire");
    $stmt->execute();
    $stmt->close();

    $horaires = [];

    foreach ($lignes as $ligne) {
        if (!in_array($ligne[2], $horaires)) {
            $jour = getJour($ligne[2]);
            if ($jour == "Mardi" || $jour == "Mercredi") {
                $heure = str_replace('H', 'h', substr($ligne[2], 3));
            } else {
                $heure = str_replace('H', 'h', substr($ligne[2], 2));
            }
            $id_horaire = count($horaires) + 1;
            $stmt = $mysqli->prepare("INSERT INTO horaire (id_horaire, jour, heure)
                VALUES (?,?,?)");
            $stmt->bind_param("iss", $id_horaire, $jour, $heure);
            $stmt->execute();
            $stmt->close();
            $horaires[] = $ligne[2];
        }
        $date = getDateTime($ligne[0], $ligne[2]);
        $id_horaire = array_search($ligne[2], $horaires) + 1;
        createCours($date, $id_horaire);
    }
    $mysqli->close();
    return $horaires;
}

//setCoursHoraire('csv/cours.csv');

function setUtilisateur($cheminwithemail, $cheminwithcours, $horaires)
{
    $ligneswithemail = [];
    $file = fopen($cheminwithemail, "r");
    while (($ligne = fgetcsv($file, null, ';')) !== false) {
        $ligneswithemail[] = $ligne;
    }
    fclose($file);
    $ligneswithcours = [];
    $file = fopen($cheminwithcours, "r");
    $pattern_horaire = '/^[A-Za-z]{3} :[A-Za-z]+ \d{1,2}H\d{2}-\d{1,2}H\d{2}$/';
    while (($ligne = fgetcsv($file, null, ';')) !== false) {
        if (preg_match($pattern_horaire, $ligne[8])) {
            $ligneswithcours[] = $ligne;
        }
    }
    fclose($file);
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE FROM utilisateur
        WHERE role = 'user' ");
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM rattrapages");
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM absences");
    $stmt->execute();
    $stmt->close();

    $mysqli->close();
    $j = 0;
    for ($i = 0; $i < count($ligneswithcours); $i++) {
        while ($ligneswithcours[$i][0] != $ligneswithemail[$j][1] . ' ' . $ligneswithemail[$j][4]) {
            if ($j != count($ligneswithemail) - 1) {
                $j += 1;
            }
        }
        $nom = ucfirst(strtolower($ligneswithemail[$j][1]));
        $prenom = ucfirst(strtolower($ligneswithemail[$j][4]));
        $email = $ligneswithemail[$j][9];
        $id_horaire = array_search(substr($ligneswithcours[$i][8], 5), $horaires) + 1;
        createUtilisateur($nom, $prenom, $email, $id_horaire, 'user');
        $j += 1;
    }
}

//setUtilisateur("csv/couture_inscrits.csv", "csv/liste-avec-tps-daccueil.csv", setCoursHoraire("csv/cours.csv"));
