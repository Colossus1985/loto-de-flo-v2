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
    public function getGroups()
    {
        // Variables de configuration et initialisation
        $vals = array();
        $months = config('commun.mois_ordo');
        $vals['labels'] = config('commun.mois_label');

        $current = date('Y');
        $pastYear = $current - 2; // Trois dernières années

        // // Récupération des données
        // $query = Gains::select(
        //             'nameGroup',
        //             DB::raw("DATE_FORMAT(date, '%Y') as annee"),
        //             DB::raw("DATE_FORMAT(date, '%m') as mois"),
        //             DB::raw('SUM(gainIndividuel) as total_gain_mensuel')
        //         )
        //         ->whereYear('date', '>=', $pastYear)
        //         ->groupBy('nameGroup', 'annee', 'mois')
        //         ->orderBy('annee')
        //         ->orderBy('mois')
        //         ->orderBy('nameGroup')
        //         ->get()
        //         ->toArray();

        $query = Gains::select(
                DB::raw("DATE_FORMAT(date, '%Y') as annee"),
                DB::raw("DATE_FORMAT(date, '%m') as mois"),
                DB::raw('SUM(gainIndividuel) as total_gain_mensuel')
                )
                ->whereYear('date', '>=', $pastYear)
                ->groupBy('annee', 'mois')
                ->orderBy('annee')
                ->orderBy('mois')
                ->get()
                ->toArray();

       // Initialisation des données pour le graphique et des totaux
        $data = [];
        $totaux = [];
        foreach ($vals['labels'] as $label) {
            $data[$label] = [];
        }

        // Remplissage des données et calcul des totaux
        foreach ($query as $row) {
            $monthLabel = $months[(int) $row['mois']];
            $annee = $row['annee'];
            $total_gain_mensuel = round($row['total_gain_mensuel']);

            // Stocker les données dans le tableau $data
            $data[$monthLabel][$annee] = $total_gain_mensuel;

            // Calcul des totaux pour chaque année
            if (isset($totaux[$annee])) {
                $totaux[$annee] += $total_gain_mensuel;
            } else {
                $totaux[$annee] = $total_gain_mensuel;
            }
        }

        // dd(['labels' => $vals['labels'], 'data' => $data, 'totaux' => $totaux]);
        return [
            'labels'    => $vals['labels'],
            'data'      => $data,
            'totaux'    => $totaux
        ];
    }

    /**
     * récupération des infos des participants pour les stats du dashbord
     * @param string période demandé
     * @param int nombre d'année
     */
    public function getParticipants()
    {
        
    }
}