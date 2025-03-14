<?php
namespace App;

use App\database\AnnonceLoader;
use App\database\DatabaseConnexion;
use App\Exception\NotFoundException;
use App\html\Annonce as AnnonceHtml;

class Application
{
    public function run(): Response
    {
        $config = json_decode(file_get_contents(SRC_DIR.'/../config/database.json'));
        $connexion = new DatabaseConnexion(
            $config->dsn,
            $config->username,
            $config->password);

        $reader = new UrlReader();
        try {
            $id = $reader->parse();
            $loader = new AnnonceLoader($connexion);
            $annonce = $loader->load($id);
            $result = file_get_contents(SRC_DIR.$annonce->annonce);
            //echo $annonce;
            $response = new Response($result);
        } catch (Exception $e) {
            $response = new Response('Cette page n\'existe pas', 404);
        }
        return $response;
    }
}