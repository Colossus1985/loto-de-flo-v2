<?php

namespace App\Repositories;

use Illuminate\Database\QueryException;
use Exception;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

class GroupsRepository 
{
    /**
     * récupérer tous les groups
     * @param Collection des groups
     */
    public function getGroups()
    {
        $query = Groups::query()
            ;
        $res = $query->get();

        return $res;
    }

    /**
     * récupérer un groupe en particulier
     * @param string nom de la colonne
     * @param string valeur du champs
     * @param collection du groupe
     */
    public function getGroup($champ, $valeur)
    {
        $query = Groups::query()
            ->where($champ, $valeur)
            ;
        $res = $query->first();

        return $res;
    }

    /**
     * ajouter d'un nouveau group
     */
    public function addGroup($champs) 
    {
        $message = $champs['nameGroup'] . " créé avec succès !";

        try {
            $query = Groups::query();
            $query->insert($champs);

        } catch (QueryException $e) {
            // Gestion des erreurs de base de données
            return ['erreur' => true, 'message' => 'Erreur de base de données : ' . $e->getMessage()];
        } catch (Exception $e) {
            // Gestion des autres exceptions
            return ['erreur' => true, 'message' => 'Erreur : ' . $e->getMessage()];
        }

        return ['erreur' => false, 'message' => $message];
    }


}