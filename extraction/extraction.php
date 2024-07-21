<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/database/Database.php";

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
    if($jour=="D "){
        return "Dimanche";
    }
}

function getDateTime($date, $horaire)
{
    $res=substr($date, 6,4).'-'.substr($date, 3,2).'-'.substr($date, 0,2).' ';
    if(getJour($horaire)=="Mardi" || getJour($horaire)=="Mercredi"){
        if($horaire[4]=="H"){
            $res=$res.substr($horaire, 3,1).':'.substr($horaire, 5,2);
        }
        else{
            $res=$res.substr($horaire, 3,2).':'.substr($horaire, 6,2);
        }
    }
    else{
        if($horaire[3]=="H"){
            $res=$res.substr($horaire, 2,1).':'.substr($horaire, 4,2);
        }
        else{
        $res=$res.substr($horaire, 2,2).':'.substr($horaire, 5,2);
        }
    }
    $res=$res.':00';
    return $res;
}

function setCoursHoraire($chemin)
{
    $lignes = [];
    $file = fopen($chemin, "r");
    while (($ligne = fgetcsv($file,null, ';')) !== false) {
        $lignes[] = $ligne;
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
    $pattern_date = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[0-2])\/\d{4}$/';
    $pattern_horaire = "/^[A-Za-z]+ \d{1,2}H\d{2}-\d{1,2}H\d{2}$/";

    foreach ($lignes as $ligne) {
        if (preg_match($pattern_date, $ligne[0]) && preg_match($pattern_horaire, $ligne[1])) {
            if (!in_array($ligne[1], $horaires)) {
                $jour = getJour($ligne[1]);
                if ($jour == "Mardi" || $jour == "Mercredi") {
                    $heure = str_replace('H', 'h', substr($ligne[1], 3));
                } else {
                    $heure = str_replace('H', 'h', substr($ligne[1], 2));
                }
                $id_horaire = count($horaires)+1;
                $stmt = $mysqli->prepare("INSERT INTO horaire (id_horaire, jour, heure)
                VALUES (?,?,?)");
                $stmt->bind_param("iss", $id_horaire, $jour,$heure);
                $stmt->execute();
                $stmt->close();
                $horaires[]=$ligne[1];
            }
            $date=getDateTime($ligne[0], $ligne[1]);
            $id_horaire=array_search($ligne[1],$horaires)+1;
            $stmt = $mysqli->prepare("INSERT INTO cours (id_cours, date, id_horaire)
                VALUES (?,?,?)");
                $stmt->bind_param("isi", $compte, $date,$id_horaire);
                $stmt->execute();
                $stmt->close();
        }
    }
}

//setCoursHoraire('cours.csv');

function setUtilisateur($cheminwithemail, $cheminwithcours)
{
    
}