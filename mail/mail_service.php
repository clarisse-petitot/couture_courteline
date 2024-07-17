<?php

require __DIR__ . '/../vendor/autoload.php';

function sendMail(Utilisateur $utilisateur, Token $token): void
{
    $env = parse_ini_file('.env');

    $api_key = $env["RESEND_API_KEY"];
    $resend = Resend::client($api_key);

    $content=str_replace('[PRENOM]',$utilisateur->getPrenom(),file_get_contents("./mail/template.html"));
    $content=str_replace('[NOM]',$utilisateur->getNom(),$content);
    $content=str_replace('[TOKEN]',$token->getToken(),$content);

    $resend->emails->send([
        'from' => 'contact@lacouturedecp.fr',
        'to' => $utilisateur->getEmail(),
        'subject' => 'Connexion au site pour la couture',
        'html' => $content,
    ]);
}