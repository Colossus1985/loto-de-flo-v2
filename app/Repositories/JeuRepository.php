<?php

namespace App\Repositories;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Jeux;
use App\Models\Participants;

use App\Repositories\ParticipantRepository;

use Illuminate\Database\QueryException;
use Exception;
use DB;
use Illuminate\Support\Facades\Log;

class JeuRepository 
{
    protected $participant;
    public function __construct(
        ParticipantRepository $participant
        )
    {
        $this->participant      = $participant;
    }
    /**
     * récupérer le montant 
     * table : Jeux
     * @param string champ à chercher
     * @param string valeur à chercher
     */
    public function getJeux()
    {
        $query = Jeux::query()
            ->where('del', 0)
            ->orderBy('id', 'desc')
            ;
        $res = $query->get();
        return $res;
    }

    /**
     * ajout ligne 
     * Table jeux
     * @param array chmaps = values
     */
    public function addJeu($champs)
    {
        dd('ici aussi');
        try {
            $jeu = New Jeux();
            // Mettre à jour les champs avec les valeurs fournies dans le tableau $array
            foreach ($champs as $key => $value) {
                $jeu->$key = $value;
            }
            $jeu->save();
        } catch (Exception $e) {
            return ['erreur' => true, 'message' => 'Erreur lors de la mise à jour de la table Jeux : ' . $e->getMessage()];
        }
        
        return ['erreur' => false, 'message' => 'Insertion dans la table Jeux effectué avec succès !'];
    }

    /**
     * update ligne
     * Table jeux
     * @param array chmaps = values
     */
    public function updateDelLigJeux($champs, $id_ligne)
    {
        dd('ici');
        try {
            $lig_del = Money::find($id_ligne);
            if (!$lig_del) {
                return ['erreur' => true, 'message' => "Ligne avec ID $id_ligne introuvable dans la table Money."];
            }

            // Mettre à jour les champs avec les valeurs fournies
            foreach ($champs as $key => $value) {
                $lig_del->$key = $value;
            }

            // $lig_del->save();

            //=== mettre à jour participant totaux
            $participant    = $this->participant->getParticipant('id', $lig_del->id_pseudo);

            $amount         = $participant->amount      - floatval($lig_del->credit ?? 0);
            $totalAmount    = $participant->totalAmount - floatval($lig_del->credit ?? 0);
            
            $champs = [
                'amount'        => $amount,
                'totalAmount'   => $totalAmount,
            ];
            $res_maj_participant = $this->participant->updateParticipant($champs, $participant->id);
            if ($res_maj_participant['erreur']) {
                Log::warning('erreur update lignes amount / amount total participant :' . $res_maj_participant['erreur']);
            }
            #############################

            // récalculer l'amount de la dernière ligne du groupe et du participant
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
                $credit = floatval($lig_del->credit ?? 0);
                
                $amount = $ligne->amount - $credit;
                // dd($solde, $credit, $debit);
                // dump("amount = $amount");
                // dump("credit = $credit");
                // dump("debit = $debit");
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
            
            return [
                'erreur' => false,
                'message' => "Update de la ligne $id_ligne dans la table Money effectué avec succès !"
            ];

        } catch (\Exception $e) {
            return [
                'erreur' => true,
                'message' => "Erreur lors de la mise à jour de la table Money pour la ligne $id_ligne: " . $e->getMessage()
            ];
        }
    }





}