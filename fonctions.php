<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/database/Database.php";
require_once __DIR__ . "/classes.php";

function getToken(string $token): Token | null

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

function getAllCours(int $id_utilisateur): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM appel a
    JOIN cours c ON a.id_cours=c.id_cours
    JOIN horaire h ON c.id_horaire=h.id_horaire
    WHERE a.id_utilisateur = ?
    ORDER BY c.date");
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $date = new DateTime($ligne["date"]);
        if ($date->getTimestamp() > time()) {
            $liste[] = new Cours($ligne["id_cours"], $date, new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]));
        }
    };

    return $liste;
}

function estInscrit(string $email, string $nom, string $prenom): array
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

    if (count($res) == 0) {
        return [1];
    } else {
        if (count($res) == 1) {
            return [0, new Utilisateur(
                $res[0]["id_utilisateur"],
                $res[0]["nom"],
                $res[0]["prenom"],
                $res[0]["email"],
                $res[0]["rattrapage"],
                new Horaire($res[0]["id_horaire"], $res[0]["jour"], $res[0]["heure"]),
                $res[0]["role"]
            )];
        } else {
            $nbr_doublon = 0;
            $utilisateurs = [];
            foreach ($res as $ligne) {
                if (strtoupper($prenom) == strtoupper($res["prenom"])) {
                    $nbr_doublon += 1;
                    $utilisateurs[] = new Utilisateur(
                        $res[0]["id_utilisateur"],
                        $res[0]["nom"],
                        $res[0]["prenom"],
                        $res[0]["email"],
                        $res[0]["rattrapage"],
                        new Horaire($res[0]["id_horaire"], $res[0]["jour"], $res[0]["heure"]),
                        $res[0]["role"]
                    );
                }
            }
            if ($nbr_doublon == 1) {
                return [0, $utilisateurs[0]];
            } else {
                if ($nbr_doublon > 1) {
                    foreach ($utilisateurs as $utilisateur) {
                        if (strtoupper($utilisateur->getNom()) == strtoupper($nom)) {
                            return [0, $utilisateur];
                        }
                    }
                }
                return [2];
            }
        }
    }
}


/* fonction de la documentation de php */

function uniqidReal(int $length = 32): string
{
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($length / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $length);
}

function createToken(Token $token)
{
    $t = $token->getToken();
    $id = $token->getUtilisateur()->getIdUtilisateur();
    $date = $token->getDateCreation();

    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("INSERT INTO tokens (token, id_utilisateur, date_creation)
    VALUES (?,?,?) ");
    $stmt->bind_param("sii", $t, $id, $date);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getTraduction(DateTime $date): string
{
    $monthinEnglish = $date->format('F');
    if ($monthinEnglish == "January") {
        return "Janvier";
    }
    if ($monthinEnglish == "February") {
        return "Février";
    }
    if ($monthinEnglish == "March") {
        return "Mars";
    }
    if ($monthinEnglish == "April") {
        return "Avril";
    }
    if ($monthinEnglish == "May") {
        return "Mai";
    }
    if ($monthinEnglish == "June") {
        return "Juin";
    }
    if ($monthinEnglish == "July") {
        return "Juillet";
    }
    if ($monthinEnglish == "August") {
        return "Août";
    }
    if ($monthinEnglish == "September") {
        return "Septembre";
    }
    if ($monthinEnglish == "October") {
        return "Octobre";
    }
    if ($monthinEnglish == "November") {
        return "Novembre";
    }
    if ($monthinEnglish == "December") {
        return "Decembre";
    }
    return "Not found";
}

function getCours(int $id_cours): Cours
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM cours c
    JOIN horaire h ON c.id_horaire=h.id_horaire
    WHERE c.id_cours = ?");
    $stmt->bind_param("i", $id_cours);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $res = $res[0];
    $cours = new Cours(
        $res["id_cours"],
        new DateTime($res["date"]),
        new Horaire($res["id_horaire"], $res["jour"], $res["heure"])
    );
    return $cours;
}

function appartient(int $id_utilisateur, int $id_cours): bool
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM appel
    WHERE id_utilisateur = ? AND id_cours = ?");
    $stmt->bind_param("ii", $id_utilisateur, $id_cours);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $mysqli->close();

    return count($res) > 0;
}

function deleteAppel(int $id_utilisateur, int $id_cours): void
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE FROM appel
    WHERE id_cours=? AND id_utilisateur=? ");
    $stmt->bind_param("ii", $id_cours, $id_utilisateur);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getAllRattrapages(int $id_utilisateur): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT c.id_cours, c.date, h.id_horaire, h.jour, h.heure
    FROM cours c
    JOIN horaire h ON c.id_horaire=h.id_horaire
    LEFT OUTER JOIN appel a ON c.id_cours=a.id_cours
    GROUP BY c.id_cours 
    HAVING count(*)<12
    ORDER BY c.date");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $date = new DateTime($ligne["date"]);
        if (!appartient($id_utilisateur, $ligne["id_cours"]) && $date->getTimestamp() > time()) {
            $liste[] = new Cours($ligne["id_cours"], new DateTime($ligne["date"]), new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]));
        }
    };

    return $liste;
}

function createAppel(int $id_utilisateur, int $id_cours): void
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("INSERT INTO appel (id_cours, id_utilisateur)
    VALUES (?,?) ");
    $stmt->bind_param("ii", $id_cours, $id_utilisateur);
    $stmt->execute();
    $stmt->close();
}

function changeRattrapage(int $id_utilisateur, int $nbr): void
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("UPDATE utilisateur
    SET rattrapage = ?
    WHERE id_utilisateur = ?");
    $stmt->bind_param("ii", $nbr, $id_utilisateur);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getHeures(): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT DISTINCT heure
    FROM horaire");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste_fin = [];

    foreach ($res as $ligne) {
        $liste_fin[] = $ligne["heure"];
    }

    return $liste_fin;
}

function getJours(): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT DISTINCT jour
    FROM horaire");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste_fin = [];

    foreach ($res as $ligne) {
        $liste_fin[] = $ligne["jour"];
    }

    return $liste_fin;
}

function getFiltresRattrapages(): array
{
    $filtres = ["jours" => [], "heures" => []];
    foreach ($_GET as $key => $value) {
        if (str_starts_with($key, "jour-")) {
            $filtres["jours"][] = substr($key, 5);
        } else if (str_starts_with($key, "heure-")) {
            $filtres["heures"][] = substr($key, 6);
        }
    }
    return $filtres;
}

function createUtilisateur(string $nom, string $prenom, string $email, int $id_horaire, string $role):int
{
    $nbr_rattrapage = 0;
    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO utilisateur (nom, prenom, email, role, rattrapage, id_horaire)
                VALUES (?,?,?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $nom, $prenom, $email, $role, $nbr_rattrapage, $id_horaire);
    $stmt->execute();
    $stmt->close();
    $id_utilisateur=$mysqli->insert_id;
    $mysqli->close();
    return $id_utilisateur;
}

function getCoursFromIdHoraire(int $id_horaire): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM cours c
    JOIN horaire h ON c.id_horaire=h.id_horaire
    WHERE h.id_horaire = ?");
    $stmt->bind_param("i", $id_horaire);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $date = new DateTime($ligne["date"]);
        if ($date->getTimestamp() > time()) {
            $liste[] = new Cours($ligne["id_cours"], $date, new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]));
        }
    };
    return $liste;
}