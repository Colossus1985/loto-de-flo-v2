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

        return [
            'labels'    => $vals['labels'],
            'data'      => $data,
            'totaux'    => $totaux
        ];
    }

    /**
     * récupération des informations pour un seul group
     * pour les charts
     * @param string nom du groupe
     * @return array infos du groupe
     */
    public function getGroupData($group_name)
    {
        // Configuration et initialisation
        $months = config('commun.mois_ordo');
        $labels = config('commun.mois_label');

        $currentYear = date('Y');
        $pastYear = $currentYear - 2; // Inclure les trois dernières années

        // Requête pour récupérer les données du groupe spécifique
        $query = Gains::select(
                DB::raw("DATE_FORMAT(date, '%Y') as annee"),
                DB::raw("DATE_FORMAT(date, '%m') as mois"),
                DB::raw('SUM(gainIndividuel) as total_gain_mensuel')
            )
            ->where('nameGroup', $group_name)
            ->whereYear('date', '>=', $pastYear)
            ->groupBy('annee', 'mois')
            ->orderBy('annee')
            ->orderBy('mois')
            ->get()
            ->toArray();

        // Initialisation des données pour le graphique et des totaux
        $data = [];
        $totaux = [];
        foreach ($labels as $label) {
            $data[$label] = [];
        }

        // Remplissage des données et calcul des totaux
        foreach ($query as $row) {
            $monthLabel = $months[(int) $row['mois']];
            $annee = $row['annee'];
            $total_gain_mensuel = round($row['total_gain_mensuel'], 2);

            // Stocker les données dans le tableau $data
            if (!isset($data[$monthLabel])) {
                $data[$monthLabel] = [];
            }
            $data[$monthLabel][$annee] = round($row['total_gain_mensuel'], 2);

            // Calcul des totaux pour chaque année
            if (isset($totaux[$annee])) {
                $totaux[$annee] += round($total_gain_mensuel, 2);
            } else {
                $totaux[$annee] = round($total_gain_mensuel, 2);
            }
        }

        // dd($labels, $data, $totaux, $group_name);
        $result =  [
            'labels'        => $labels,
            'data'          => $data,
            'totaux'        => $totaux,
            'group_name'    => $group_name
        ];

        return $result;
        
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