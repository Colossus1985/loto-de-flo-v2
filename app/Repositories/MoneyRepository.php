<?php

namespace App\Repositories;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

use Illuminate\Database\QueryException;
use Exception;

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
     * récupérer le fond des groups
     */
    public function fonds($groups)
    {
        $arrayFondsByGroup = [];
        if (count($groups) > 0) {
            foreach ($groups as $i => $group) {
                $query = Participants::query()
                    ->where('nameGroup', '=', $group->nameGroup)
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
        return $arrayFondsByGroup;
    }

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

}