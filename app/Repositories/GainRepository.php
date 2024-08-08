<?php

namespace App\Repositories;

use Illuminate\Database\QueryException;
use Exception;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

class GainRepository 
{
    /**
     * ajout d'un Gain 
     * @param array champs
     */
    public function addGain($champs) 
    {
        $erreur     = true;
        $message    = "Gain ajouté avec succès !";

        $query = Gains::query();

        try {
            // Création d'un nouveau gain
            $query->insert($champs);
        } catch (QueryException $e) {
            // Gestion des erreurs de base de données
            return ['erreur' => $erreur, 'message' => 'Erreur de base de données : ' . $e->getMessage()];
        } catch (Exception $e) {
            // Gestion des autres exceptions
            return ['erreur' => $erreur, 'message' => 'Erreur : ' . $e->getMessage()];
        }
        
        $erreur = false;
        return ['erreur' => $erreur, 'message' => $message];

    }

    /**
     * récupérer tout les gains
     */
    public function getGains()
    {
        $query = Gains::query()
                ->orderBy('date', 'desc')
            ;
        $res = $query->get();
        return $res;
    }

    public function ajoutGroupInGain() 
    {
        $lignes = Money::query()->get();
        foreach ($lignes as $ligne) {
            // Rechercher le participant associé
            $participant = Participants::query()
                ->where('id', $ligne->id_pseudo)
                ->first();
        
            // Vérifier si le participant est null
            if ($participant === null || $participant->nameGroup == null) {
                // Traitez le cas où le participant n'existe pas
                echo "Aucun participant trouvé pour id_pseudo: " . $ligne->id_pseudo . "\n";
                continue; // Passer à la ligne suivante
            }
        
            $group = Groups::query()
                ->where('nameGroup', $participant->nameGroup)
                ->first();

            // dd($participant->id, $group->id, $participant->nameGroup, date_format($ligne->created_at, "Y-m-d"));
            // Mettre à jour la table Money avec les informations du groupe
            Money::query()
                ->where('id_pseudo', $participant->id)
                ->update([
                    'id_group'      => $group->id,
                    'group_name'    => $participant->nameGroup,
                    'date'          => date_format($ligne->created_at, "Y-m-d")
                ])
                ;
        }
    }


}