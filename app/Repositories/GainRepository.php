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


}