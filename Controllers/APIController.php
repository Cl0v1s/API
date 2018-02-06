<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once "Applicative/IModelManager.php";

foreach (glob("Applicative/*") as $filename) {
    require_once $filename;
}

foreach (glob("Model/*") as $filename) {
    require_once $filename;
}

require_once 'vendor/autoload.php';
require_once 'Controllers/Controller.php';

/**
 * Created by PhpStorm.
 * User: clovis
 * Date: 22/01/17
 * Time: 16:15
 */
class APIController extends Controller
{

    /**
     * Rend les données reçues propres à être insérées dans la base
     *
     * @param array $params
     * @return array
     */
    public static function Sanitize(array $params)
    {
        $res = [];
        foreach($params as $key => $value)
        {
            $temp = trim($value);
            $temp = htmlentities($temp);
            $temp = mysql_escape_string($temp);
            $res[$key] = $temp;
        }
        return $res;
    }

    /**
     * Exécute la fonction appelée dans operation (getall, put, patch, get, delete)
     * @param string $class Classe du modèle à manipuler
     * @param string $operation //nature de l'action
     * @param array $params paramètres associés à la requète
     * @param Response $response 
     * @return Response
     */
    public static function Execute($class, $operation,array $params, Response $response)
    {
        $manager = $class."Manager";
        if(class_exists($manager) == false)
            return $response->withStatus(404);

        if(method_exists($manager, $operation) == false)
            return $response->withStatus(405);

        $params = APIController::Sanitize($params);
        $data = null;
        switch ($operation)
        {
            case "GetAll":
                $data = APIController::GetAll($manager, $params);
                break;
            case "GET":
            case "Get":
                $data = APIController::Get($manager,$params["id"]);
                break;
            case "PUT":
            case "Put":
                $data = APIController::Put($manager, $params);
                break;
            case "PATCH":
            case "Patch":
                APIController::Patch($manager, $params["id"], $params);
                break;
            case "DELETE":
            case "Delete":
                APIController::Delete($manager, $params["id"]);
                break;
        }
        if($data == null && $operation != "GetAll")
            return $response->withStatus(404);
        $packet = array();
        $packet["value"] = $data;
        $response = $response->getBody()->write(json_encode($packet));

        return $response;
    }

    
    /**
     * Retourne tous les items
     * @param ModelManager $manager 
     * @param array $data paramètres de la requete
     * @return array Résultats
     */

    private static function GetAll($manager, $data)
    {
        $filters = "";
        if(isset($data["\$filter"]))
        {
            $filters = $data["\$filter"];
        }

        if(isset($data["\$top"]))
        {
            if(is_numeric($data["\$top"]) == false)
                throw new Exception("L'indice de départ n'est pas valide", Errors::$BAD_ARGUMENTS);
            $top = intval($data["\$top"]);
            $filters .= " and id ge ".$top;

            if(isset($data["\$skip"]))
            {
                if(is_numeric($data["\$skip"]) == false)
                    throw new Exception("L'indice de longueur n'est pas valide", Errors::$BAD_ARGUMENTS);
                $skip = $top + intval($data["\$skip"]);
                $filters .= " and id lt ".$skip;
            }
        }
        return $manager::GetAll($filters);
    }
        
    /**
     * Selectionne l'item dont on a saisi l'id en parametre
     * @param string $manager // on saisit le manager qui nous interesse
     * @param int $id //identifiant de l'item que l'on veut selectionner
     * @return Response $response // reponse de la requete
     */

    private static function Get($manager, $id)
    {
        return $manager::Get($id);
    }

    /**
     * Supprime l'item dont on a saisi l'id en parametre dans le manager saisi en parametre
     * @param string $manager // on saisit le manager qui nous interesse
     * @param int $id //identifiant de l'item que l'on veut supprimer
     */

    private static function Delete($manager, $id)
    {
        $manager::Delete($id);
    }

    /**
     * Ajouter un item dont on a saisi l'id en parametre dans le manager que l'on a saisi en parametre
     * @param string $manager // on saisit le manager qui nous interesse
     * @param int $id //identifiant de l'item que l'on veut ajouter
     * @return Response $response // reponse de la requete
     */
    private static function Put($manager, $data)
    {
        $c = new ReflectionClass($manager);
        $f = $c->getMethod("Put");
        $params = array();
        foreach ($f->getParameters() as $param) {
            if(isset($data[$param->name]) == false && $param->isOptional() == false)
                throw new Exception("Les arguments fournis sont incorrects (".$param->name.")", 1);
            if(isset($data[$param->name]))  
                array_push($params,$data[$param->name]);
        }
        return $manager::Put(...$params);
    }
    /**
     * Editer un item dont on a saisi l'id en parametre dans le manager que l'on a saisi en parametre
     * @param string $manager // on saisit le manager qui nous interesse
     * @param int $id //identifiant de l'item que l'on veut éditer
     */
    private static function Patch($manager, $id, $data)
    {
        $c = new ReflectionClass($manager);
        $f = $c->getMethod("Patch");
        $params = array();
        foreach ($f->getParameters() as $param) {
            if(isset($data[$param->name]) == false)
                throw new Exception("Les arguments fournis sont incorrects (".$param->name.")", Errors::$BAD_ARGUMENTS);
            array_push($params,$data[$param->name]);
        }
        $manager::Patch($id, ...$params);
    }
}
