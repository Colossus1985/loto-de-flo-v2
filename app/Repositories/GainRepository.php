<?php

namespace App\Repositories;

use Illuminate\Database\QueryException;
use Exception;
use DB;
use Illuminate\Support\Facades\Log;


use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;

use App\Repositories\ParticipantRepository;

class GainRepository 
{
    public function __construct(
        ParticipantRepository $participant
        )
    {
        $this->participant      = $participant;
    }

    protected $participant;
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
                ->where('del', 0)
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

    /**
     * supprimer une ligne de gains 
     * - maj l'historisation
     * - maj amount dans money
     * - maj totaux participant
     * - maj participant
     * @param int id ligne gain
     */
    public function updateDelLigGain($id_ligne)
    {
        try {
            $lig_del = Gains::find($id_ligne);
            if (!$lig_del) {
                return ['erreur' => true, 'message' => "Ligne Gains avec ID $id_ligne introuvable dans la table Gains."];
            }
            // Mettre à jour les champs avec les valeurs fournies
            $lig_del->del = 1;
            $lig_del->save();

            //=== mettre à jour amount de la dernière ligne du participant du group
            $lig_maj = Money::where('group_name', $lig_del->nameGroup)
                ->where('creditGain', $lig_del->gainIndividuel)
                ->where('date', $lig_del->date)
                ->orderBy('id', 'asc')
                ->first();

            if ($lig_maj) {
                $lig_maj->amount = $lig_maj->amount - floatval($lig_money->creditGain ?? 0);
                $lig_maj->save();
            } else {
                Log::warning("Aucune ligne trouvée pour mettre à jour amount (group: {$lig_del->nameGroup}, id_pseudo: {$lig_del->id_pseudo}, date: {$lig_del->date})");
            }

            //=== récupération d'une ligne par participant correspondant dans money
            $sub = DB::table('money')
                ->selectRaw('MAX(id) as id')
                ->where('group_name', $lig_del->nameGroup)
                ->where('creditGain', $lig_del->gainIndividuel)
                ->groupBy('id_pseudo');

            $lig_del_money = Money::whereIn('id', $sub)->get();

            foreach ($lig_del_money as $lig_money) {

                $lig_del = Money::find($lig_money->id);
                if (!$lig_del) {
                    return ['erreur' => true, 'message' => "Ligne avec ID $id_ligne introuvable dans la table Money."];
                }

                // Mettre à jour les champs avec les valeurs fournies
                $lig_del->del = 1;
                $lig_del->save();

                //=== mettre à jour participant totaux
                $participant    = $this->participant->getParticipant('id', $lig_money->id_pseudo);

                $amount         = $participant->amount      - floatval($lig_money->creditGain ?? 0);
                $totalAmount    = $participant->totalAmount - floatval($lig_money->creditGain ?? 0);
                
                $champs = [
                    'amount'        => $amount,
                    'totalAmount'   => $totalAmount,
                ];
                $res_maj_participant = $this->participant->updateParticipant($champs, $participant->id);
                if ($res_maj_participant['erreur']) {
                    Log::warning('erreur update lignes amount / amount total participant :' . $res_maj_participant['erreur']);
                }
                #############################

                //=== récalculer l'amount de la dernière ligne du groupe et du participant
                $all_lignes = Money::where('group_name', $lig_del->group_name)
                    ->where('id_pseudo', $lig_del->id_pseudo)
                    ->where(function($query) use ($lig_del) {
                        $query->where('date', '>', $lig_del->date)
                            ->orWhere(function($subquery) use ($lig_del) {
                                $subquery->where('date', $lig_del->date)
                                        ->where('id', '>', $lig_del->id);
                            });
                    })
                    ->orderBy('date')
                    ->orderBy('id')
                    ->get();

                // dd($all_lignes, $lig_del);
                foreach ($all_lignes as $ligne) {
                    $gain = floatval($lig_money->creditGain ?? 0);
                    $amount = $ligne->amount - $gain;
                    $ligne->amount = $amount;

                    // if ($ligne->isDirty('amount')) {
                    //     dump("Amount changé pour ID {$ligne->id} : " . $ligne->getOriginal('amount') . " → $amount");
                    // } else {
                    //     dump("Aucun changement détecté pour ID {$ligne->id} (amount = $amount)");
                    // }

                    try {
                        $ligne->save();
                    } catch (\Exception $e) {
                        Log::warning('erreur update lignes amount :' . $e);
                    }
                }
            }
            // dd('fini');
            return [
                'erreur' => false,
                'message' => "Update de la ligne $id_ligne dans la table Gain effectué avec succès !"
            ];

        } catch (\Exception $e) {
            return [
                'erreur' => true,
                'message' => "Erreur lors de la mise à jour de la table Gains pour la ligne $id_ligne: " . $e->getMessage()
            ];
        }
    }



}