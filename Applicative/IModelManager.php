<?php
/**
 * Created by PhpStorm.
 * User: Clovis
 * Date: 01/11/2017
 * Time: 13:31
 */

 /**
   * Les ModelManager permettent de manipuler les classes du modèles associées et leur liens avec la base de données
   **/ 
interface IModelManager
{
    /**
     * Selectionne tous les items avec une restriction
     * @param string $filters //restriction exigée par l'utilisateur
     */
    public static function GetAll($filters);
    /**
     * Selectionne l'item dont on a saisi l'id en parametre
     * @param int $id //identifiant de l'item que l'on veut selectionner
     */
    public static function Get($id);
    /**
     * Supprime l'item dont on a saisi l'id en parametre
     * @param $int id //identifiant de l'item
     */
    public static function Delete($id);
}