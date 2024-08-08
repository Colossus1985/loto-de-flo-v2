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
     * récupérer tous les participants actifs
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
        $group          = $this->groups->getGroup('nameGroup', $request->input('inputNameGroup'));
        $group_ids      = [];
        $group_names    = [];
        foreach ($group as $data) {
            $group_ids[]    = $data->id;
            $group_names[]  = $data->nameGroup;
        }
        $group_ids_json     = json_encode($group_ids);
        $group_names_json   = json_encode($group_names);

        $message    = "$request->pseudo ajouté avec succès !";

        try {
            // Création d'un nouveau participant
            $participant = new Participants();
            $participant->firstName = $request->input('inputFirstName');
            $participant->lastName = $request->input('inputLastName');
            $participant->nameGroup = $group_names_json;
            $participant->groupId = $group_ids_json;
            $participant->pseudo = $request->input('inputPseudo');
            $participant->email = $request->input('inputEmail');
            $participant->tel = $request->input('inputTel');
            $participant->save();

        } catch (QueryException $e) {
            // Gestion des erreurs de base de données
            return ['erreur' => true, 'message' => 'Erreur de base de données : ' . $e->getMessage()];
        } catch (Exception $e) {
            // Gestion des autres exceptions
            return ['erreur' => true, 'message' => 'Erreur : ' . $e->getMessage()];
        }

        try {
            $participant = $this->getParticipant('pseudo', $request->pseudo);
            $id_pseudo = $participant->id;
            $money = new Money();
            $money->pseudo = $request->inputPseudo;
            $money->id_pseudo = $id_pseudo;
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

        } catch (Exception $e) {
            // Gestion des erreurs, par exemple, si le participant n'est pas trouvé ou si une autre erreur se produit
            return ['erreur' => true, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()];
        }

        return ['erreur' => false, 'message' => 'Participant mis à jour avec succès !'];
    }


    //=== migration -> v3 réunification des pseudo
    public function unificationPseudo()
    {
        //=== récupération du group_id et transformation de group_id et nameGroup en json
        // $lines2json = Participants::query()->get();
        // foreach ($lines2json as $data) {
        //     if ($data->nameGroup) {
        //         $group = Groups::query()
        //             ->where('nameGroup', $data->nameGroup)
        //             ->first();
        //         $query = Participants::query()
        //             ->where('id', $data->id)
        //             ;
        //         $query->update([
        //             'nameGroup' => json_encode([$data->nameGroup]),
        //             'group_id' => json_encode([$group->id]),
        //         ]);
        //     }
        // }
        
        //=== réunification des peudos
        $flo = Participants::query()->where('pseudo', 'like', 'Flo%')->get();
        $gg = Participants::query()->where('pseudo', 'like', 'GG%')->get();
        $andjou = Participants::query()->where('pseudo', 'like', 'Andjou%')->get();
        $Marijo = Participants::query()->where('pseudo', 'like', 'Marijo%')->get();
        $Rom1 = Participants::query()->where('pseudo', 'like', 'Rom1%')->get();
        $Thalie = Participants::query()->where('pseudo', 'like', 'Thalie%')->get();
        $Nico = Participants::query()->where('pseudo', 'like', 'Nico%')->get();

        $this->action($flo, 'Flo', 'Flo', 3);
        $this->action($gg, 'GG', 'GGBE', 12);
        $this->action($andjou, 'Andjou', 'AndjouBE', 9);
        $this->action($Marijo, 'Marijo', 'Marijo', 4);
        $this->action($Rom1, 'Rom1', 'Rom1BL', 22);
        $this->action($Thalie, 'Thalie', 'ThalieBE', 24);
        $this->action($Nico, 'Nico', 'NicoBE', 13);

        return true;
    }

    public function action($participant, $pseudo_fin, $pseudo_origin, $id_pseudo_unique)
    {
        // $sum_amount         = 0;
        // $sum_totalAmount    = 0;
        // $array_group_id     = [];
        // $array_group_name   = [];
        // foreach ($participant as $data) {
        //     $sum_amount         = $sum_amount + $data->amount;
        //     $sum_totalAmount    = $sum_totalAmount + $data->totalAmount;
        //     if ($data->group_id) {
        //         $array_group_id     = array_merge($array_group_id, json_decode($data->group_id));
        //         $array_group_name   = array_merge($array_group_name, json_decode($data->nameGroup));
        //     }
        // }

        // $array_group_id     = json_encode($array_group_id);
        // $array_group_name   = json_encode($array_group_name);

        // $champs_participants = [
        //     'pseudo'        => $pseudo_fin,
        //     'group_id'      => $array_group_id,
        //     'nameGroup'     => $array_group_name,
        //     'amount'        => $sum_amount,
        //     'totalAmount'   => $sum_totalAmount,
        // ];

        // $query = Participants::query()
        //     ->where('pseudo', $pseudo_origin)
        //     ;
        // $query->update($champs_participants);

        //=== maj table money ======================================
        $champs_money = [
            'pseudo'    => $pseudo_fin,
            'id_pseudo' => $id_pseudo_unique,
        ];
        // dd($champs_money);
        $query = Money::query()
            ->where('pseudo', 'like', "$pseudo_fin%")
            ;
        $query->update($champs_money);

    }

}