<?php

namespace App\Repositories;

use Illuminate\Database\QueryException;
use Exception;
use DB;

use App\Models\Gains;

class LogRepository 
{
    /**
     * récupération des infos des groupes pour les stats du dashbord
     * @param string période demandé
     * @param int nombre d'année
     */
    public function getGroups($periode, $nb_annee)
    {
        $vals = array();
  
        // Mois pour indice depuis requete
        $months = config('commun.mois_ordo');
        // Labels
        $vals['labels'] = config('commun.mois_label');

        $current = date('Y');
        for ($an = ($current-$nb_annee)+1 ; $an <= $current; $an++) {
            $query = Gains::select(
                        'nameGroup',
                        DB::raw("DATE_FORMAT(date, '%Y') as annee"),
                        DB::raw("DATE_FORMAT(date, '%m') as mois"),
                        DB::raw('SUM(gainIndividuel) as total_gain_mensuel')
                    )
                    ->whereYear('date', $an)
                    ->groupBy('nameGroup', 'annee', 'mois')
                    ->orderBy('annee')
                    ->orderBy('mois')
                    ->orderBy('nameGroup')
                    ;

            $rows = $query->get()->toArray();

            // Affectation cle/valeur tableau des valeurs
            foreach ($vals['labels'] as $label) {
                $vals['datasets'][$an][$label]    = 0;
            } 

            // Affectation des valeurs trouvées aux dates
            $total_set = 0;
            foreach ($rows as $row) {
                $vals['datasets'][$an][$months[(int) $row['mois']]] = round($row['total_gain_mensuel']);
                $total_set    += $row['total_gain_mensuel'];
            }  
            
            // Affectation total/set
            $vals['totaux'][$an] = number_format($total_set, 0, '.', ' ');
           
        }
        return $vals;
    }

    /**
     * récupération des infos des participants pour les stats du dashbord
     * @param string période demandé
     * @param int nombre d'année
     */
    public function getParticipants($periode, $nb_annee)
    {
        
    }
}