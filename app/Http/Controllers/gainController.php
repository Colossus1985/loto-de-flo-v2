<?php

namespace App\Http\Controllers;

use App\Repositories\ParticipantRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;
use App\Repositories\GainRepository;
use Symfony\Component\HttpFoundation\Request;

class gainController extends Controller
{
    public function __construct(
        ParticipantRepository $participant,
        GroupsRepository $groups,
        MoneyRepository $money,
        GainRepository $gain,
        )
    {
        $this->participant      = $participant;
        $this->groups           = $groups;
        $this->money            = $money;
        $this->gain             = $gain;
    }

    /**
     * affichage du formulaire pour entrer un nouveau gain
     */
    public function addGainForm()
    {
        $groupsDispo = $this->groups->getGroups();

        return view('forms.addGainForm', [
            'groupsDispo'   => $groupsDispo,
        ]);
    }

    /**
     * ajout d'un nouveau gain
     * partage du gain parmi les personnes dans le group
     */
    public function addGain(Request $request)
    {
        // dd($request);
        $nameGroup = $request->inputNameGroup;
        if (!$nameGroup || $nameGroup == '') {
            return redirect()->back()
                ->with('error', 'Indiquez le groupe gagniant, s\'il vous plait');
        }

        $arrayParticipantWin = $this->participant->getParticipants();
        $arrayParticipantWin = collect($arrayParticipantWin)->filter(function ($participant) use ($nameGroup) {
            //=== Décoder le JSON contenu dans le champ nameGroup
            $groupNames = json_decode($participant->nameGroup, true);
            //=== Vérifier si le nom recherché se trouve dans le tableau décodé
            return is_array($groupNames) && in_array($nameGroup, $groupNames);
        });

        $gainValue = $request->inputAmount;
        $nbPersonnes = count($arrayParticipantWin);
        $gainIndividuel = bcdiv($gainValue, $nbPersonnes, 2); //downRounding 0.9999 = 0.99

        $champs = [
            'amount'         => $gainValue,
            'nameGroup'      => $request->inputNameGroup,
            'date'           => $request->inputDate,
            'nbPersonnes'    => $nbPersonnes,
            'gainIndividuel' => $gainIndividuel,
        ];
        $res_insert_gain = $this->gain->addGain($champs);
        if ($res_insert_gain['erreur']) {
            return redirect()->back()
                ->with('error', $res_insert_gain['message']);
        }
        
        $addMoney = $request->inputAddGainAuto;
        if ($addMoney === "true") {
            foreach ($arrayParticipantWin as $i => $participantWin) {
                $pseudo         = $participantWin->pseudo;

                $participant    = $this->participant->getParticipant('pseudo', $pseudo);
                $group          = $this->groups->getGroup('nameGroup', [$nameGroup]);

                $id_participant = $participant->id;

                $money          = $this->money->getMoney('id_pseudo', $id_participant);

                $credit = $gainIndividuel;
                $amount = $money[0]->amount;
                $amount = $amount + $credit;
                $totalAmount = $participant->totalAmount;
                $totalAmount = $totalAmount + $credit;
                
                $champs = [
                    'amount'        => $amount,
                    'totalAmount'   => $totalAmount,
                ];
                $res_maj_participant = $this->participant->updateParticipant($champs, $id_participant);
                if ($res_maj_participant['erreur']) {
                    return redirect()->back()
                        ->with('error', $res_insert_gain['message']);
                }

                $champs = [
                    'pseudo'        => $pseudo,
                    'id_pseudo'     => $id_participant,
                    'date'          => $request->inputDate,
                    'amount'        => $amount,
                    'id_group'      => $group->id,
                    'creditGain'    => $credit,
                    'group_name'     => $nameGroup,
                ];
                $res_insert_money = $this->money->insertMoney($champs);
                if ($res_insert_money['erreur']) {
                    return redirect()->back()
                        ->with('error', $res_insert_money['message']);
                }
            }
            return redirect()->back()
                ->with('success', 'felicitation, votre gain de '. $gainValue. '€ à été enrégistré et partagé parmi les participant(s)! 🥳🥳🥳🥳🥳🥳');
        }

        return redirect()->back()
            ->with('success', 'felicitation, votre gain de '. $gainValue. '€ à été enrégistré. Pense à la distribution du Gain ;) 🥳🥳🥳🥳🥳🥳');
    }

    public function getGainHistory()
    {
        $participants   = $this->participant->getParticipants();
        $gains          = $this->gain->getGains();
        $groups         = $this->groups->getGroups();

        return view('pages.gainHistorique', [
            'gains' => $gains,
            'participants' => $participants,
            'groups' => $groups,
        ]);
    } 

    public function ajoutGroupInGain()
    {
        $this->gain->ajoutGroupInGain();
        return response()->json('ok');
    }
}
