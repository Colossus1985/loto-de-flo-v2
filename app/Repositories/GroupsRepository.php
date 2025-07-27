<?php

namespace App\Repositories;

use Illuminate\Database\QueryException;
use Exception;
use DB;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

use function Laravel\Prompts\select;

class GroupsRepository 
{
    /**
     * récupérer tous les groups
     * @param string champ actif
     * @param bool champ condition
     * @param bool récupérer tous les groups
     * @return Collection des groups
     */
    public function getGroups($champ = 'actif', $bool = 1, $tous = false)
    {
        $query = Groups::query();
        if (!$tous) {
            $query = $query->where($champ, $bool);
        }
        $res = $query->get();

        return $res;
    }

    /**
     * récupérer un groupe en particulier
     * @param string nom de la colonne
     * @param array valeurs du champs
     * @return collection du groupe
     */
    public function getGroup($champ, $valeur)
    {
        if ($champ && $valeur) {
            $query = Groups::query()
                ->whereIn($champ, $valeur)
                ;
            $res = $query->get();

            return $res;
        } else {
            return false;
        }

        
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

    /**
     * récuperer l'ensembles des infos des groupes
     * les groups actifs 
     * les groups inactifs
     * @return array les deux groups
     */
    public function getVueGroupe()
    {
        $query = Groups::query()
            ->where('actif', 1);
        $groups = $query->get();
        foreach ($groups as $group) {
            // Récupère le total des gains pour le groupe
            $total_gain_group = Gains::query()
                ->select(DB::raw('SUM(amount) as total_gain_group'))
                ->where('nameGroup', $group->nameGroup)
                ->groupBy('nameGroup')
                ->first();

            // Si un total de gains est trouvé, on l'ajoute à l'objet group
            $group->total_gain_group = $total_gain_group ? $total_gain_group->total_gain_group : 0;

            // Récupère les participants actifs pour le groupe
            $participants_group = Participants::query()
                ->where('nameGroup', $group->nameGroup)
                ->where('actif', 1)
                ->get();

            // On ajoute la liste des participants à l'objet group
            $group->participants_group = $participants_group;
        }

        $query = Groups::query()
            ->where('actif', 0);
        $groups_inactif = $query->get();

        foreach ($groups_inactif as $group) {
            // Récupère le total des gains pour le groupe
            $total_gain_group = Gains::query()
                ->select(DB::raw('SUM(amount) as total_gain_group'))
                ->where('nameGroup', $group->nameGroup)
                ->groupBy('nameGroup')
                ->first();

            // Si un total de gains est trouvé, on l'ajoute à l'objet group
            $group->total_gain_group = $total_gain_group ? $total_gain_group->total_gain_group : 0;

            // Récupère les participants actifs pour le groupe
            $participants_group = Participants::query()
                ->where('nameGroup', $group->nameGroup)
                ->where('actif', 1)
                ->get();

            // On ajoute la liste des participants à l'objet group
            $group->participants_group = $participants_group;
        }
        return [$groups, $groups_inactif];
    }
    
    /**
     * supprimer un group
     * enlever le group des personne qui sont dans le groupe
     */
    public function deleteGroup($group_id, $nameGroup)
    {
        $erreur  = false;
        $message = "";
        // Désactiver le groupe
        $query = Groups::query()->where('id', $group_id);
        $res_delete_group = $query->update(['actif' => 0]);

        // Enlever le groupe des participants s'il a été désactivé
        $res_delete_group = true;
        if ($res_delete_group) {
            $participants = Participants::all();  // Récupérer tous les participants

            foreach ($participants as $participant) {
                // Décoder les valeurs JSON
                $group_ids   = json_decode($participant->group_id, true);
                $group_names = json_decode($participant->nameGroup, true);

                // Vérifier si $group_id est présent dans $group_ids
                if (is_array($group_ids) && in_array($group_id, $group_ids)) {
                    // Retirer l'ID du tableau $group_ids
                    $group_ids = array_values(array_filter($group_ids, function ($id) use ($group_id) {
                        return $id != $group_id;
                    }));

                    // Retirer le nom du groupe du tableau $group_names
                    $group_names = array_values(array_filter($group_names, function ($name) use ($nameGroup) {
                        return $name !== $nameGroup;
                    }));

                    // Reconvertir les tableaux en JSON
                    $participant->group_id = json_encode($group_ids);
                    $participant->nameGroup = json_encode($group_names, JSON_UNESCAPED_UNICODE);

                    // Mettre à jour la ligne dans la base de données
                    if (!$participant->save()) {
                        $erreur = true;
                        $message = "Un problème est survenu lors de la mise à jour du participant {$participant->id}.";
                        break;
                    }
                }
            }
        } else {
            $erreur = true;
            $message = "Un problème est survenu lors de la suppression du groupe.";
        }

        return ['erreur' => $erreur, 'message' => $message];
    }


    /**
     * Reactiver un groupe
     * @param int id du groupe
     */
    public function groupRallume($id_group)
    {
        $query = Groups::query()
            ->where('id', $id_group)
            ;
        $res = $query->update(['actif' => 1]);
        if ($res) {
            return ['erreur' => false, 'message' => "Le groupe a bien été reactivé."];
        } else {
            return ['erreur' => true, 'message' => "Le groupe n'a pas pu être reactivé."];
        }
    }

}