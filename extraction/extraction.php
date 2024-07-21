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
        if (preg_match($pattern_date, $ligne[0]) && preg_match($pattern_horaire, $ligne[1])) {
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
    $compte = 0;

    foreach ($lignes as $ligne) {
        if (!in_array($ligne[1], $horaires)) {
            $jour = getJour($ligne[1]);
            if ($jour == "Mardi" || $jour == "Mercredi") {
                $heure = str_replace('H', 'h', substr($ligne[1], 3));
            } else {
                $heure = str_replace('H', 'h', substr($ligne[1], 2));
            }
            $id_horaire = count($horaires) + 1;
            $stmt = $mysqli->prepare("INSERT INTO horaire (id_horaire, jour, heure)
                VALUES (?,?,?)");
            $stmt->bind_param("iss", $id_horaire, $jour, $heure);
            $stmt->execute();
            $stmt->close();
            $horaires[] = $ligne[1];
        }
        $date = getDateTime($ligne[0], $ligne[1]);
        $id_horaire = array_search($ligne[1], $horaires) + 1;
        $stmt = $mysqli->prepare("INSERT INTO cours (id_cours, date, id_horaire)
                VALUES (?,?,?)");
        $stmt->bind_param("isi", $compte, $date, $id_horaire);
        $stmt->execute();
        $stmt->close();
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
        if (filter_var($ligne[8], FILTER_VALIDATE_EMAIL)) {
            $ligneswithemail[] = $ligne;
        }
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
    if (count($ligneswithemail) == count($ligneswithcours)) {
        $mysqli = Database::connexion();

        $stmt = $mysqli->prepare("DELETE FROM utilisateur");
        $stmt->execute();
        $stmt->close();
        
        $stmt = $mysqli->prepare("DELETE FROM appel");
        $stmt->execute();
        $stmt->close();

        $mysqli->close();
        for ($i = 0; $i < count($ligneswithemail); $i++) {
            if($ligneswithcours[$i][0]==$ligneswithemail[$i][1].' '.$ligneswithemail[$i][2]){
                $nom = $ligneswithemail[$i][1];
                $prenom = $ligneswithemail[$i][2];
                $email = $ligneswithemail[$i][8];
                $id_horaire = array_search(substr($ligneswithcours[$i][8],5),$horaires);
                $id_utilisateur = createUtilisateur($nom, $prenom, $email, $id_horaire, 'user');
                $allcours = getCoursFromIdHoraire($id_horaire);
                foreach($allcours as $cours)
                {
                    createAppel($id_utilisateur, $cours->getIdCours());
                }
            }
        }
    }
}

//setUtilisateur("csv/couture_inscrits.csv", "csv/liste-avec-tps-daccueil.csv", setCoursHoraire("csv/cours.csv"));
