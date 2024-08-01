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
        new Utilisateur($res["id_utilisateur"], $res["nom"], $res["prenom"], $res["email"], $res["nbr_rattrapage"], new Horaire($res["id_horaire"], $res["jour"], $res["heure"]), $res["role"])
    );

    $stmt->close();

    $time = time() - (4 * 24 * 3600);
    $stmt = $mysqli->prepare("DELETE
    FROM tokens
    WHERE date_creation <= ?");
    $stmt->bind_param("i", $time);
    $stmt->execute();
    $stmt->close();

    $mysqli->close();

    return $token;
}

function getAllCoursFromIdUtilisateur(int $id_utilisateur): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT * FROM (((SELECT c.*
    FROM utilisateur u
    JOIN cours c ON u.id_horaire=c.id_horaire
    WHERE u.id_utilisateur = ?)
    EXCEPT
    (SELECT c.*
    FROM absences a
    JOIN cours c ON c.id_cours=a.id_cours
    WHERE a.id_utilisateur=?))
    UNION
    (SELECT c.*
    FROM rattrapages r
    JOIN cours c ON c.id_cours=r.id_cours
    WHERE r.id_utilisateur=?)) c
    JOIN horaire h ON h.id_horaire=c.id_horaire
    ORDER BY c.date");
    $stmt->bind_param("iii", $id_utilisateur, $id_utilisateur, $id_utilisateur);
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
                $res[0]["nbr_rattrapage"],
                new Horaire($res[0]["id_horaire"], $res[0]["jour"], $res[0]["heure"]),
                $res[0]["role"]
            )];
        } else {
            $nbr_doublon = 0;
            $utilisateurs = [];
            foreach ($res as $ligne) {
                if (strtoupper($prenom) == strtoupper($ligne["prenom"])) {
                    $nbr_doublon += 1;
                    $utilisateurs[] = new Utilisateur(
                        $ligne["id_utilisateur"],
                        $ligne["nom"],
                        $ligne["prenom"],
                        $ligne["email"],
                        $ligne["nbr_rattrapage"],
                        new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]),
                        $ligne["role"]
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

    $stmt = $mysqli->prepare("((SELECT c.*
    FROM cours c
    JOIN utilisateur u ON u.id_horaire=c.id_horaire
    WHERE u.id_utilisateur = ? AND c.id_cours = ?)
    EXCEPT
    (SELECT c.* 
    FROM absences a
    JOIN cours c ON c.id_cours=a.id_cours
    WHERE a.id_utilisateur = ? AND a.id_cours = ?))
    UNION
    (SELECT c.* 
    FROM rattrapages r
    JOIN cours c ON c.id_cours=r.id_cours
    WHERE r.id_utilisateur = ? AND r.id_cours = ?)");
    $stmt->bind_param("iiiiii", $id_utilisateur, $id_cours, $id_utilisateur, $id_cours, $id_utilisateur, $id_cours);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $mysqli->close();

    return count($res) > 0;
}

function createAbsence(int $id_utilisateur, int $id_cours): void
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("INSERT INTO absences (id_cours, id_utilisateur)
    VALUES (?,?)");
    $stmt->bind_param("ii", $id_cours, $id_utilisateur);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getAllRattrapagesFromIdUtilisateur(int $id_utilisateur): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT c.*,h.*,COUNT(DISTINCT a.id_utilisateur) AS total_absences,COUNT(DISTINCT r.id_utilisateur) AS total_rattrapages,COUNT(DISTINCT u.id_utilisateur) AS nbr_inscrit
    FROM cours c
    JOIN horaire h ON h.id_horaire = c.id_horaire
    LEFT JOIN utilisateur u ON c.id_horaire = u.id_horaire
    LEFT JOIN absences a ON c.id_cours = a.id_cours
    LEFT JOIN rattrapages r ON c.id_cours = r.id_cours
    GROUP BY c.id_cours
    HAVING COUNT(DISTINCT u.id_utilisateur) + COUNT(DISTINCT r.id_utilisateur) - COUNT(DISTINCT a.id_utilisateur) < 12
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



function createRattrapage(int $id_utilisateur, int $id_cours): void
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("INSERT INTO rattrapages (id_cours, id_utilisateur)
    VALUES (?,?) ");
    $stmt->bind_param("ii", $id_cours, $id_utilisateur);
    $stmt->execute();
    $stmt->close();
}

function changeNbrRattrapage(int $id_utilisateur, int $nbr): void
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("UPDATE utilisateur
    SET nbr_rattrapage = ?
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

function createUtilisateur(string $nom, string $prenom, string $email, int $id_horaire, string $role)
{
    $nbr_rattrapage = 0;
    $split_espace = explode(' ', $nom);
    $nom = "";
    foreach ($split_espace as $tmp_nom) {
        $split_tiret = explode('-', $tmp_nom);
        foreach ($split_tiret as $split) {
            $nom = $nom . ucfirst(strtolower($split)) . '-';
        }
        $nom = substr($nom, 0, -1);
        $nom = $nom . ' ';
    }
    $nom = substr($nom, 0, -1);
    $split_espace = explode(' ', $prenom);
    $prenom = "";
    foreach ($split_espace as $tmp_prenom) {
        $split_tiret = explode('-', $tmp_prenom);
        foreach ($split_tiret as $split) {
            $prenom = $prenom . ucfirst(strtolower($split)) . '-';
        }
        $prenom = substr($prenom, 0, -1);
        $prenom = $prenom . ' ';
    }
    $prenom = substr($prenom, 0, -1);

    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO utilisateur (nom, prenom, email, role, nbr_rattrapage, id_horaire)
                VALUES (?,?,?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $nom, $prenom, $email, $role, $nbr_rattrapage, $id_horaire);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
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
    $mysqli->close();

    $liste = [];

    foreach ($res as $ligne) {
        $date = new DateTime($ligne["date"]);
        if ($date->getTimestamp() > time()) {
            $liste[] = new Cours($ligne["id_cours"], $date, new Horaire($ligne["id_horaire"], $ligne["jour"], $ligne["heure"]));
        }
    };
    return $liste;
}

function deleteAbsence($id_utilisateur, $id_cours)
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE
    FROM absences
    WHERE id_utilisateur = ? AND id_cours = ?");
    $stmt->bind_param("ii", $id_utilisateur, $id_cours);
    $stmt->execute();
    $mysqli->close();
}

function isAbsent($id_utilisateur, $id_cours)
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM absences
    WHERE id_utilisateur = ? AND id_cours = ?");
    $stmt->bind_param("ii", $id_utilisateur, $id_cours);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();
    return count($res) > 0;
}

function deleteRattrapage($id_utilisateur, $id_cours)
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE
    FROM rattrapages
    WHERE id_utilisateur = ? AND id_cours = ?");
    $stmt->bind_param("ii", $id_utilisateur, $id_cours);
    $stmt->execute();
    $mysqli->close();
}

function isRattrapage($id_utilisateur, $id_cours)
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM rattrapages
    WHERE id_utilisateur = ? AND id_cours = ?");
    $stmt->bind_param("ii", $id_utilisateur, $id_cours);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();
    return count($res) > 0;
}

function getCategoriesFromIdCreation($id_creation): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM type t
    JOIN categorie c ON c.id_categorie=t.id_categorie
    WHERE t.id_creation = ?");
    $stmt->bind_param("i", $id_creation);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    $liste = [];

    foreach ($res as $ligne) {
        $liste[] = new Categorie($ligne["id_categorie"], $ligne["nom"]);
    }

    return $liste;
}

function getImagesFromIdCreation($id_creation): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM associe a
    JOIN image i ON i.id_image=a.id_image
    JOIN utilisateur u ON i.id_utilisateur=u.id_utilisateur
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE a.id_creation = ?");
    $stmt->bind_param("i", $id_creation);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $mysqli->close();

    $liste = [];

    foreach ($res as $ligne) {
        $liste[] = new Image(
            $ligne["id_image"],
            $ligne["fichier"],
            new Utilisateur(
                $ligne["id_utilisateur"],
                $ligne["nom"],
                $ligne["prenom"],
                $ligne["email"],
                $ligne["nbr_rattrapage"],
                new Horaire(
                    $ligne['id_horaire'],
                    $ligne["jour"],
                    $ligne['heure']
                ),
                $ligne['role']
            )
        );
    }

    return $liste;
}

function getCreations(): array
/*
    Renvoie la liste de toutes les creations
*/
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM creation
    ORDER BY id_creation DESC");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $creations = [];

    foreach ($res as $ligne) {
        $creations[] = new Creation(
            $ligne["id_creation"],
            $ligne["nom"],
            $ligne["description"],
            $ligne["tissu"],
            $ligne["surface_tissu"],
            $ligne["patron"],
            getImagesFromIdCreation($ligne["id_creation"]),
            getCategoriesFromIdCreation($ligne["id_creation"])
        );
    }
    $stmt->close();
    $mysqli->close();

    return $creations;
}

function getCategories(): array
/*
    Renvoie la liste de toutes les catégories
*/
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM categorie");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste_fin = [];

    foreach ($res as $ligne) {
        $liste_fin[] = new Categorie($ligne["id_categorie"], $ligne["nom"]);
    }

    return $liste_fin;
}

function getCategorieFromId(int $id_categorie): Categorie | null
/*
    Renvoie la catégorie d'une creation en fonction de son id
*/
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM categorie
    WHERE id_categorie=?");
    $stmt->bind_param("i", $id_categorie);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) == 0) {
        return null;
    }

    $res = $res[0];

    return new Categorie($res["id_categorie"], $res["nom"]);
}

function getFiltres(): array
/*
    Renvoie la liste de tous les filtres
*/
{
    $filtres = [];
    foreach ($_GET as $key => $value) {
        if (str_starts_with($key, "categorie-")) {
            $filtres[] = getCategorieFromId(intval(substr($key, 10)));
        }
    }
    return $filtres;
}

function getAllCours(): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM cours c
    JOIN horaire h ON h.id_horaire=c.id_horaire
    ORDER BY c.date");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $date = new DateTime($ligne["date"]);
        if ($date->getTimestamp() > time()) {
            $liste[] = new Cours(
                $ligne["id_cours"],
                $date,
                new Horaire(
                    $ligne["id_horaire"],
                    $ligne["jour"],
                    $ligne["heure"]
                )
            );
        }
    };

    return $liste;
}

function getUtilisateurFromCours($cours): array
{
    $id_cours = $cours->getIdCours();

    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT * FROM (((SELECT u.*
    FROM utilisateur u
    JOIN cours c ON u.id_horaire=c.id_horaire
    WHERE c.id_cours = ?)
    EXCEPT
    (SELECT u.*
    FROM absences a
    JOIN utilisateur u ON u.id_utilisateur=a.id_utilisateur
    WHERE a.id_cours=?))
    UNION
    (SELECT u.*
    FROM rattrapages r
    JOIN utilisateur u ON u.id_utilisateur=r.id_utilisateur
    WHERE r.id_cours=?)) u
    JOIN horaire h ON h.id_horaire=u.id_horaire
    WHERE u.role='user' ");
    $stmt->bind_param("iii", $id_cours, $id_cours, $id_cours);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = ["inscrits" => [], "rattrapages" => []];

    foreach ($res as $ligne) {
        if ($cours->getHoraire()->getIdHoraire() == $ligne["id_horaire"]) {
            $liste["inscrits"][] = new Utilisateur(
                $ligne["id_utilisateur"],
                $ligne["nom"],
                $ligne["prenom"],
                $ligne["email"],
                $ligne["nbr_rattrapage"],
                new Horaire(
                    $ligne["id_horaire"],
                    $ligne["jour"],
                    $ligne["heure"]
                ),
                $ligne["role"]
            );
        } else {
            $liste["rattrapages"][] = new Utilisateur(
                $ligne["id_utilisateur"],
                $ligne["nom"],
                $ligne["prenom"],
                $ligne["email"],
                $ligne["nbr_rattrapage"],
                new Horaire(
                    $ligne["id_horaire"],
                    $ligne["jour"],
                    $ligne["heure"]
                ),
                $ligne["role"]
            );
        }
    };

    return $liste;
}

function getAllHoraires(): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM horaire");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $liste[] = new Horaire(
            $ligne["id_horaire"],
            $ligne["jour"],
            $ligne["heure"]
        );
    };

    return $liste;
}

function getUtilisateurFromIdHoraire($id_horaire): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM utilisateur u
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE u.id_horaire=? and u.role='user'
    ORDER BY u.nom, u.prenom");
    $stmt->bind_param("i", $id_horaire);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $liste[] = new Utilisateur(
            $ligne["id_utilisateur"],
            $ligne["nom"],
            $ligne["prenom"],
            $ligne["email"],
            $ligne["nbr_rattrapage"],
            new Horaire(
                $ligne["id_horaire"],
                $ligne["jour"],
                $ligne["heure"]
            ),
            $ligne["role"]
        );
    };

    return $liste;
}

function getUtilisateurFromId($id_utilisateur): Utilisateur
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM utilisateur u
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE u.id_utilisateur=?");
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $ligne = $res[0];

    $utilisateur = new Utilisateur(
        $ligne["id_utilisateur"],
        $ligne["nom"],
        $ligne["prenom"],
        $ligne["email"],
        $ligne["nbr_rattrapage"],
        new Horaire(
            $ligne["id_horaire"],
            $ligne["jour"],
            $ligne["heure"]
        ),
        $ligne["role"]
    );

    return $utilisateur;
}

function getAllRattrapagesFromIdUtilisateurIdHoraire(int $id_utilisateur, int $id_horaire): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT c.id_cours, c.date, h.*, COUNT(DISTINCT a.id_utilisateur) AS total_absences, COUNT(DISTINCT r.id_utilisateur) AS total_rattrapages,COUNT(DISTINCT u.id_utilisateur) AS nbr_inscrit
    FROM cours c
    JOIN horaire h ON h.id_horaire = c.id_horaire
    LEFT JOIN utilisateur u ON c.id_horaire = u.id_horaire
    LEFT JOIN absences a ON c.id_cours = a.id_cours
    LEFT JOIN rattrapages r ON c.id_cours = r.id_cours  
    WHERE c.id_horaire = ?
    GROUP BY c.id_cours, h.id_horaire
    HAVING nbr_inscrit + total_rattrapages - total_absences < 12
    ORDER BY c.date");
    $stmt->bind_param("i", $id_horaire);
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

function createCours(string $date, int $id_horaire)
{
    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO cours (date, id_horaire)
                VALUES (?,?)");
    $stmt->bind_param("si", $date, $id_horaire);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function deleteCours($id_cours)
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE
    FROM cours
    WHERE id_cours = ?");
    $stmt->bind_param("i", $id_cours);
    $stmt->execute();
    $mysqli->close();
}

function getHoraireFromId($id_horaire): Horaire
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM horaire
    WHERE id_horaire=?");
    $stmt->bind_param("i", $id_horaire);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $ligne = $res[0];

    $utilisateur = new Horaire(
        $ligne["id_horaire"],
        $ligne["jour"],
        $ligne["heure"]
    );

    return $utilisateur;
}

function getUtilisateurFromRole($role): array
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT *
    FROM utilisateur u
    JOIN horaire h ON u.id_horaire=h.id_horaire
    WHERE u.role=?
    ORDER BY u.nom, u.prenom");
    $stmt->bind_param("s", $role);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $liste = [];

    foreach ($res as $ligne) {
        $liste[] = new Utilisateur(
            $ligne["id_utilisateur"],
            $ligne["nom"],
            $ligne["prenom"],
            $ligne["email"],
            $ligne["nbr_rattrapage"],
            new Horaire(
                $ligne["id_horaire"],
                $ligne["jour"],
                $ligne["heure"]
            ),
            $ligne["role"]
        );
    };

    return $liste;
}

function deleteUtilisateur($id_utilisateur)
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("DELETE
    FROM utilisateur
    WHERE id_utilisateur = ?");
    $stmt->bind_param("i", $id_utilisateur);
    $stmt->execute();
    $mysqli->close();
}

function getDernierIdCreation()
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT max(id_creation)
    FROM creation");
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    if ($res[0]["max(id_creation)"] == null) {
        return 0;
    } else {
        return $res[0]["max(id_creation)"];
    }
}

function createCreation(int $id_creation, string $nom, string $description, string $tissu, string $surface_tissu, string $patron)
{
    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO creation (id_creation, nom, description, tissu, surface_tissu, patron)
                VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("isssss", $id_creation, $nom, $description, $tissu, $surface_tissu, $patron);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function createImage(string $fichier, int $id_utilisateur)
{
    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO image (fichier, id_utilisateur)
                VALUES (?,?)");
    $stmt->bind_param("si", $fichier, $id_utilisateur);
    $stmt->execute();
    $id_image = $mysqli->insert_id;
    $stmt->close();
    $mysqli->close();
    return $id_image;
}

function createAssocie(int $id_creation, int $id_image)
{
    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO associe (id_creation, id_image)
                VALUES (?,?)");
    $stmt->bind_param("ii", $id_creation, $id_image);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function createType(int $id_creation, int $id_categorie)
{
    $mysqli = Database::connexion();
    $stmt = $mysqli->prepare("INSERT INTO type (id_creation, id_categorie)
                VALUES (?,?)");
    $stmt->bind_param("ii", $id_creation, $id_categorie);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function deleteCreation($id_creation)
{
    $images = getImagesFromIdCreation($id_creation);
    $mysqli = Database::connexion();

    foreach ($images as $image) {
        $id = $image->getIdImage();
        $link = __DIR__ . $image->getLien();
        if (file_exists($link)) {
            unlink($link);
        }
        $stmt = $mysqli->prepare("DELETE 
        FROM image
        WHERE id_image = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("DELETE
        FROM associe
        WHERE id_creation = ? and id_image=?");
        $stmt->bind_param("ii", $id_creation, $id);
        $stmt->execute();
        $stmt->close();
    }

    $link = __DIR__ . "/patrons/" . $id_creation . ".pdf";
    if (file_exists($link)) {
        unlink($link);
    }
    $stmt = $mysqli->prepare("DELETE
    FROM creation
    WHERE id_creation = ?");
    $stmt->bind_param("i", $id_creation);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function getCreation(int $id_creation): Creation | null
/*
    Renvoie la création sous forme de sa classe en fonction de son id
*/
{
    $mysqli = Database::connexion();

    $stmt = $mysqli->prepare("SELECT c.*
    FROM creation c
    WHERE id_creation = ?");
    $stmt->bind_param("i", $id_creation);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (count($res) == 0) {
        return null;
    }

    $ligne = $res[0];

    $creation = new Creation(
        $ligne["id_creation"],
        $ligne["nom"],
        $ligne["description"],
        $ligne["tissu"],
        $ligne["surface_tissu"],
        $ligne["patron"],
        getImagesFromIdCreation($ligne["id_creation"]),
        getCategoriesFromIdCreation($ligne["id_creation"])
    );

    $stmt->close();
    $mysqli->close();

    return $creation;
}
