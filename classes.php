<?php

class Horaire{

    private $id_horaire;
    private $jour;
    private $heure;
    
    public function __construct(int $id_horaire, string $jour, string $heure) {
        $this->id_horaire = $id_horaire;
        $this->jour = $jour;
        $this->heure = $heure;

    }

    public function getIdHoraire():int{
        return $this->id_horaire;
    }
    public function getJour():string{
        return $this->jour;
    }
    public function getHeure():string{
        return $this->heure;
    }
}

class Utilisateur{

    private $id_utilisateur;
    private $nom;
    private $prenom;
    private $email;
    private $rattrapage;
    private $horaire;
    private $role;
    
    public function __construct(int $id_utilisateur, string $nom, string $prenom, string $email, int $rattrapage, Horaire $horaire, string $role) {
        $this->id_utilisateur = $id_utilisateur;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->rattrapage = $rattrapage;
        $this->horaire = $horaire;
        $this->role = $role;

    }

    public function getIdUtilisateur():int{
        return $this->id_utilisateur;
    }
    public function getNom():string{
        return $this->nom;
    }
    public function getPrenom():string{
        return $this->prenom;
    }
    public function getEmail():string{
        return $this->email;
    }
    public function getRattrapage():int{
        return $this->rattrapage;
    }
    public function getHoraire():Horaire{
        return $this->horaire;
    }
    public function getRole():string{
        return $this->role;
    }
}

class Cours{

    private $id_cours;
    private $date;
    private $horaire;
    
    public function __construct(int $id_cours, string $date, Horaire $horaire) {
        $this->id_cours = $id_cours;
        $this->date = $date;
        $this->horaire = $horaire;

    }

    public function getIdCours():int{
        return $this->id_cours;
    }
    public function getDate():string{
        return $this->date;
    }
    public function getHoraire():Horaire{
        return $this->horaire;
    }
}

class Creation{

    private $id_creation;
    private $nom;
    private $description;
    private $tissu;
    private $surface_tissu;
    private $patron;
    private $images;
    private $categories;

    public function __construct(int $id_creation, string $nom, string $description, string $tissu, string $surface_tissu, string $patron, array $images, array $categories) {
        $this->id_creation = $id_creation;
        $this->nom = $nom;
        $this->description = $description;
        $this->tissu = $tissu;
        $this->surface_tissu = $surface_tissu;
        $this->patron = $patron;
        $this->images = $images;
        $this->categories = $categories;

    }

    public function getIdCreation():int{
        return $this->id_creation;
    }
    public function getNom():string{
        return $this->nom;
    }
    public function getDescription():string{
        return $this->description;
    }
    public function getTissu():string{
        return $this->tissu;
    }
    public function getSurfaceTissu():string{
        return $this->surface_tissu;
    }
    public function getPatron():string{
        return $this->patron;
    }
    public function getImages():array{
        return $this->images;
    }
    public function getCategories():array{
        return $this->categories;
    }
}

class Image{

    private $id_image;
    private $lien;
    private $utilisateur;
    
    public function __construct(int $id_image, string $lien, Utilisateur $utilisateur) {
        $this->id_image = $id_image;
        $this->lien = $lien;
        $this->utilisateur = $utilisateur;

    }

    public function getIdImage():int{
        return $this->id_image;
    }
    public function getLien():string{
        return $this->lien;
    }
    public function getUtilisateur():Utilisateur{
        return $this->utilisateur;
    }
}

class Categorie{

    private $id_categorie;
    private $nom;
    
    public function __construct(int $id_categorie, string $nom) {
        $this->id_categorie = $id_categorie;
        $this->nom = $nom;

    }

    public function getIdCategorie():int{
        return $this->id_categorie;
    }
    public function getNom():string{
        return $this->nom;
    }
}

class Token{

    private $token;
    private $utilisateur;
    private $date_creation;
    
    public function __construct(string $token, string $date_creation, Utilisateur $utilisateur) {
        $this->token = $token;
        $this->date_creation = $date_creation;
        $this->utilisateur = $utilisateur;

    }

    public function getToken():int{
        return $this->token;
    }
    public function getDateCreation():string{
        return $this->date_creation;
    }
    public function getUtilisateur():Utilisateur{
        return $this->utilisateur;
    }
}