<?php

namespace App\Repositories;

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


}