<?php
/**
 * Created by PhpStorm.
 * User: Clovis
 * Date: 31/10/2017
 * Time: 12:34
 */

class ErrorLogManager implements IModelManager
{

    /**
     * Selectionne tous les items avec une restriction
     * @param string $filters //restriction exigée par l'utilisateur
     * @return Response $response // reponse de la requete
     */
    public static function GetAll($filters)
    {
        return ModelManager::GetAll("ErrorLog", $filters);
    }
    /**
     * Selectionne l'item dont on a saisi l'id en parametre
     * @param int $id //identifiant de l'item que l'on veut selectionner
     * @return Response $response // reponse de la requete
     */
    public static function Get($id)
    {
        return ModelManager::Get("ErrorLog", $id);
    }
    /**
     * Ajoute un item dont on a saisi le nom en parametre
     * @param string StorageItem $item //Item à stocker
     * @return Response $response // reponse de la requete
     */

    public static function Put($datetime, $project, $message, $stacktrace, $details = null)
    {
        $item = new ErrorLog(null);
        $item->setDatetime($datetime);
        $item->setProject($project);
        $item->setMessage($message);
        $item->setStackTrace($stacktrace);
        if($details != null)
            $item->setDetails($details);  
        return ModelManager::Put($item);      
    }

    /**
     * Supprime l'item dont on a saisi l'id en parametre
     * @param $int id //identifiant de l'item
     * @param $class //classe de l'item
     * @return Response $response // reponse de la requete
     */
    public static function Delete($id)
    {
        ModelManager::Delete("ErrorLog", $id);
    }
}