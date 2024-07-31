<?php

namespace App\Repositories;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

use App\Repositories\GroupsRepository;

use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Hash;

class ParticipantRepository 
{

    public function __construct(
        GroupsRepository $groups
        )
    {
        $this->groups           = $groups;
    }

    /**
     * récupérer tous les participants
     * @param string nom colonne
     * @param string valeur du champ
     * @return collection des participants
     */
    public function getParticipants($champ = '', $value = '')
    {
        if ($champ == '') {
            $query = Participants::query()
                ->where('actif', 1) 
                ;
        } else {
            $query = Participants::query()
                ->where($champ, $value)
                ->where('actif', 1) 
                ;
        }
        $res = $query->get();
        
        return $res;
    }

    /**
     * récupérer un participant en particulier
     * @param string nom colonne
     * @param string valeur du champ
     * @return collection du participant
     */
    public function getParticipant($champ, $value, $actif = 1)
    {
        $query = Participants::query()
            ->where($champ, $value)
            ->where('actif', $actif) 
            ;
        $res = $query->first();
        return $res;
    }

    /**
     * récupérer la liste des participants rednu inactif
     */
    public function getParticipantsDeleted()
    {
        $query = Participants::query()
            ->where('actif', 0) 
            ;
        $res = $query->get();
        return $res;
    }

    /**
     * ajout d'un participant 
     * table User
     * table Participants
     * table Money
     */
    public function addParticipant($request) 
    {
        $group      = $this->groups->getGroup('nameGroup', $request->input('inputNameGroup'));
        $erreur     = true;
        $message    = "$request->pseudo ajouté avec succès !";

        try {
            // Création d'un nouveau participant
            $participant = new Participants();
            $participant->firstName = $request->input('inputFirstName');
            $participant->lastName = $request->input('inputLastName');
            $participant->nameGroup = $request->input('inputNameGroup');
            $participant->groupId = $group->id;
            $participant->pseudo = $request->input('inputPseudo');
            $participant->email = $request->input('inputEmail');
            $participant->tel = $request->input('inputTel');
            $participant->save();

        } catch (QueryException $e) {
            // Gestion des erreurs de base de données
            return ['erreur' => $erreur, 'message' => 'Erreur de base de données : ' . $e->getMessage()];
        } catch (Exception $e) {
            // Gestion des autres exceptions
            return ['erreur' => $erreur, 'message' => 'Erreur : ' . $e->getMessage()];
        }

        try {
            $participant = $this->getParticipant('pseudo', $request->pseudo);
            $id_pseudo = $participant->id;
            $money = new Money();
            $money->pseudo = $request->inputPseudo;
            $money->id_pseudo = $id_pseudo;
        } catch (QueryException $e) {
            // Gestion des erreurs de base de données
            return ['erreur' => $erreur, 'message' => 'Erreur de base de données : ' . $e->getMessage()];
        } catch (Exception $e) {
            // Gestion des autres exceptions
            return ['erreur' => $erreur, 'message' => 'Erreur : ' . $e->getMessage()];
        }

        return ['erreur', $erreur, 'message' => $message];

    }

    /**
     * update participant 
     * @param array champ => value
     * @param int id du participant
     */
    public function updateParticipant($array, $id_participant)
    {
        try {
            // Récupérer le participant par son ID
            $participant = Participants::findOrFail($id_participant);

            // Mettre à jour les champs avec les valeurs fournies dans le tableau $array
            foreach ($array as $key => $value) {
                $participant->$key = $value;
            }
            $participant->save();

            return ['erreur' => false, 'message' => 'Participant mis à jour avec succès !'];
        } catch (Exception $e) {
            // Gestion des erreurs, par exemple, si le participant n'est pas trouvé ou si une autre erreur se produit
            return ['erreur' => true, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()];
        }
    }

    public function groupsDisponible() 
    {
        $groupsDispo = Groups::join(
                'participants', 'groups.id', '=', 'participants.id_group')
            ->select('groups.nameGroup')
            ->groupByRaw('groups.nameGroup')
            ->get();

        return $groupsDispo;
    }

}