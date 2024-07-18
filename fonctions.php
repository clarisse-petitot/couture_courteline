<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/database/Database.php";

function getToken($token): Token | null

{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM tokens t
    JOIN utilisateur u ON t.id_utilisateur=u.id_utilisateur
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE t.token = ?
    ORDER BY t.date_creation DESC");
    $stmt->bind_param("s", $token);
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
        $liste[] = new Cours($ligne["id_cours"], new DateTimeImmutable($ligne["date"]), new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]));
    };

    return $liste;
}

function estInscrit($email, $nom, $prenom): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM utilisateur u
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE u.email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $mysqli->close();

    if (count($res)==0) {
        return [1];
    }
    else{
        if(count($res)==1) {
            return [0, new Utilisateur(
                $res[0]["id_utilisateur"],
                $res[0]["nom"],
                $res[0]["prenom"],
                $res[0]["email"],
                $res[0]["rattrapage"],
                new Horaire($res[0]["id_horaire"], $res[0]["jour"],$res[0]["heure"]),
                $res[0]["role"]
            )];
        }
        else{
            $nbr_doublon=0;
            $utilisateurs=[];
            foreach ($res as $ligne){
                if($prenom==$res["prenom"]){
                    $nbr_doublon+=1;
                    $utilisateurs[]=new Utilisateur(
                        $res[0]["id_utilisateur"],
                        $res[0]["nom"],
                        $res[0]["prenom"],
                        $res[0]["email"],
                        $res[0]["rattrapage"],
                        new Horaire($res[0]["id_horaire"], $res[0]["jour"],$res[0]["heure"]),
                        $res[0]["role"]
                    );
                }
            }
            if($nbr_doublon==1){
                return [0,$utilisateurs[0]];
            }
            else{
                if($nbr_doublon>1){
                    foreach($utilisateurs as $utilisateur){
                        if($utilisateur->getNom()==$nom){
                            return [0,$utilisateur];
                        }
                    }
                }
                return [2];
            }
        }
    }
}


/* fonction de la documentation de php */

function uniqidReal($lenght = 32) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

function createToken($token) {
    $t=$token->getToken();
    $id=$token->getUtilisateur()->getIdUtilisateur();
    $date=$token->getDateCreation();

    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("INSERT INTO tokens (token, id_utilisateur, date_creation)
    VALUES (?,?,?) ");
    $stmt->bind_param("sii", $t, $id, $date);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getTraduction(string $date):string 
{
    $res=substr($date, 0, 3);
    if(substr($date, 3)=="January"){
        $res=$res."Janvier";
    }
    if(substr($date, 3)=="February"){
        $res=$res."Février";
    }
    if(substr($date, 3)=="March"){
        $res=$res."Mars";
    }
    if(substr($date, 3)=="April"){
        $res=$res."Avril";
    }
    if(substr($date, 3)=="May"){
        $res=$res."Mai";
    }
    if(substr($date, 3)=="June"){
        $res=$res."Juin";
    }
    if(substr($date, 3)=="July"){
        $res=$res."Juillet";
    }
    if(substr($date, 3)=="August"){
        $res=$res."Août";
    }
    if(substr($date, 3)=="September"){
        $res=$res."Septembre";
    }
    if(substr($date, 3)=="October"){
        $res=$res."Octobre";
    }
    if(substr($date, 3)=="November"){
        $res=$res."Novembre";
    }
    if(substr($date, 3)=="December"){
        $res=$res."Decembre";
    }
    return $res;

}