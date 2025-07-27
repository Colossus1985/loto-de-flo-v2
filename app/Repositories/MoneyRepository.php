<?php

namespace App\Repositories;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

use App\Repositories\ParticipantRepository;

use Illuminate\Database\QueryException;
use Exception;
use DB;

class MoneyRepository 
{
    /**
     * récupérer le montant 
     * table : Money
     * @param string champ à chercher
     * @param string valeur à chercher
     */
    public function getMoney($champ, $valeur)
    {
        $query = Money::query()
            ->where($champ, $valeur)
            ->orderBy('id', 'desc')
            ;
        $res = $query->get();
        return $res;
    }

    /**
     * ajout ligne 
     * Table Money
     * @param array chmaps = values
     */
    public function insertMoney($champs)
    {
        try {
            $money = New Money();
            // Mettre à jour les champs avec les valeurs fournies dans le tableau $array
            foreach ($champs as $key => $value) {
                $money->$key = $value;
            }
            $money->save();
        } catch (Exception $e) {
            return ['erreur' => true, 'message' => 'Erreur lors de la mise à jour de la table Money : ' . $e->getMessage()];
        }
        
        return ['erreur' => false, 'message' => 'Insertion dans la table Money effectué avec succès !'];
    }

    /**
     * Vérifie si un enregistrement existe dans la table Money
     * pour un participant et un groupe donnés.
     *
     * @param int $id_group L'identifiant du groupe.
     * @param int $id_participant L'identifiant du participant.
     * @param string nom du groupe.
     * @return bool True si l'enregistrement existe, false sinon.
     */
    public function historique_exist($id_group, $name_group, $id_participant)
    {
        // Construire la requête pour vérifier l'existence d'un enregistrement
        $query = Money::query()
            ->where('id_pseudo', $id_participant)
            ->where('id_group', $id_group);
        
        // Vérifier si l'enregistrement existe
        $hist_exist = $query->exists();

        if (!$hist_exist) {
            $pseudo = Participants::query()
                ->select('pseudo')
                ->where('id', $id_participant)
                ->first();
            $champs = [
                'date'          => now()->format('Y-m-d'),
                'id_pseudo'     => $id_participant,
                'pseudo'        => $pseudo->pseudo,
                'id_group'      => $id_group,
                'group_name'    => $name_group,
            ];
            // dd($champs);
            $this->insertMoney($champs);
        }
        
        // Retourner le résultat
        return $hist_exist;
    }

    /**
     * récupération des fonds de chaque groupe
     * @param array objets group
     */
    public function fonds($groups)
    {
        $arrayFondsByGroup = [];
        if (count($groups) > 0) {
            foreach ($groups as $i => $group) {
                $query = Participants::query()
                    ->where('nameGroup', 'like', '%'.$group->nameGroup.'%')
                    ;
                $participantsOfGroup = $query->get();

                $fonds = 0.00;
                if (count($participantsOfGroup) != 0) {
                    foreach ($participantsOfGroup as $i2 => $participantOfGroup) {
                        $fonds = $fonds + $participantOfGroup->amount;
                    }
                    
                    $fonds = ifNotZero($fonds, true, false, '.', ' ', 2, true);
                    array_push($arrayFondsByGroup, ['nameGroup' => $group->nameGroup, 'fonds' => $fonds]);
                }
            }
        }   

        // dd($arrayFondsByGroup);
        return $arrayFondsByGroup;
    }

    /**
     * récupération des gains de chaque groupe
     * @param array objets group
     */
    function gains($groups)
    {
        $arrayGainByGroup = [];
        if (count($groups) > 0) {
            foreach ($groups as $i => $group) {
                $query = Gains::query()
                    ->where('nameGroup', '=', $group->nameGroup)
                    ;
                $gainGroups = $query->get();

                $sommeGains = 0.00;
                if (count($gainGroups) != 0) {
                    foreach ($gainGroups as $i2 => $gainGroup) {
                        $sommeGains = $sommeGains + $gainGroup->amount;
                    }   
                    $sommeGains = ifNotZero($sommeGains, true, false, '.', ' ', 2, true);
                    array_push($arrayGainByGroup, ['nameGroup' => $group->nameGroup, 'sommeGains' => $sommeGains]); 
                    
                }  
            }
        }
        return $arrayGainByGroup;
    }

    /**
     * récupérer le récap de tous les participants actif
     */
    public function getJeux()
    {
        $query = Money::select(
            'money.id_pseudo',
            'money.pseudo',
            'money.id_group',
            'money.group_name',
            'participants.id',
            DB::raw('SUM(money.credit) - SUM(CASE WHEN money.correction = 1 THEN money.debit ELSE 0 END) AS total_credit'),
            DB::raw('SUM(money.creditGain) AS total_gain'),
            DB::raw('SUM(CASE WHEN money.correction = 0 THEN money.debit ELSE 0 END) AS total_jouee'),
            DB::raw('(SUM(money.credit) + SUM(money.creditGain)) - SUM(money.debit) AS total_dispo')
        )
        ->join('participants', 'money.id_pseudo', '=', 'participants.id')
        ->where('participants.actif', 1)
        ->whereNotNull('money.id_group')
        ->groupBy('money.id_pseudo', 'money.pseudo', 'money.id_group', 'money.group_name', 'participants.id');
    
        $res = $query->get();

        return $res;
    }

    /**
     * Récuération de la somme des corrections
     * @param string id groupe
     * @param string id participant
     */
    public function getCorrections(int $id_group, int $id_participant)
    {
        $res = Money::select(
                    DB::raw('SUM(CASE WHEN money.correction = 1 THEN money.debit ELSE 0 END) AS correction')
                )
                ->where('id_group', $id_group)
                ->where('id_pseudo', $id_participant)
                ->get();
        return $res;
    }

}