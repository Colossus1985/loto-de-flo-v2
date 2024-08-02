<?php

namespace App\Http\Controllers;

use App\Repositories\ParticipantRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;

use Illuminate\Http\Request;

class moneyController extends Controller
{
    public function __construct(
        ParticipantRepository $participant,
        GroupsRepository $groups,
        MoneyRepository $money,
        )
    {
        $this->participant      = $participant;
        $this->groups           = $groups;
        $this->money            = $money;
    }

    /**
     * affichage du formulaire pour jouer
     */
    public function getJouerForm()
    {
        $groupsDispo = $this->groups->getGroups();

        return view('forms.jouerForm', [
            'groupsDispo'   => $groupsDispo,
        ]);
    }

    /**
     * ajout d'un montant en tant que gain ou crédit pour un participant
     * @param Request
     * @param int id du participant
     */
    public function addMoney(Request $request, $id_participant)
    {
        $participant    = $this->participant->getParticipant('id', $id_participant);
        $money          = $this->money->getMoney('id_pseudo', $id_participant);
        $credit         = $request->inputMontant;
        $pseudo         = $request->input_pseudo;

        $amount = $money[0]->amount;
        $amount = $amount + $credit;
        $totalAmount = $participant->totalAmount;
        $totalAmount = $totalAmount + $credit;
        
        $gain = $request->inputAddGain;

        $champs = [
            'amount'        => $amount,
            'totalAmount'   => $totalAmount,
        ];
        $res_maj_participant = $this->participant->updateParticipant($champs, $id_participant);
        if ($res_maj_participant['erreur']) {
            return redirect()->back()
                ->with('error', $res_maj_participant['message']);
        }

        if ($gain === "true") {
            $creditGain = $credit;
            $credit     = 0.00;
        } else {
            $credit     = $credit;
            $creditGain = 0.00;
        }
        $champs = [
            'pseudo'        => $pseudo,
            'id_pseudo'     => $id_participant,
            'amount'        => $amount,
            'creditGain'    => $creditGain,
            'credit'        => $credit,
        ];
        $res_insert_money = $this->money->insertMoney($champs);
        if ($res_insert_money['erreur']) {
            return redirect()->back()
                ->with('error', $res_insert_money['message']);
        }

        return redirect()->back()
            ->with('success', $credit.'€ ajouté sur le compte de '. $pseudo);
    }

    public function debitMoney(Request $request, $id_participant)
    {
        $participant    = $this->participant->getParticipant('id', $id_participant);
        $money          = $this->money->getMoney('id_pseudo', $id_participant);
        $credit         = $request->inputMontant;
        $pseudo         = $request->input_pseudo;

        $debit = $request->inputMontant;
        $amount = $money[0]->amount;
        $amount = $amount - $debit;
        $totalAmount = $participant->totalAmount;
        
        $champs = [
            'amount'        => $amount,
            'totalAmount'   => $totalAmount,
        ];
        $res_maj_participant = $this->participant->updateParticipant($champs, $id_participant);
        if ($res_maj_participant['erreur']) {
            return redirect()->back()
                ->with('error', $res_maj_participant['message']);
        }

        $champs = [
            'pseudo'        => $pseudo,
            'id_pseudo'     => $id_participant,
            'amount'        => $amount,
            'debit'         => $debit,
        ];
        $res_insert_money = $this->money->insertMoney($champs);
        if ($res_insert_money['erreur']) {
            return redirect()->back()
                ->with('error', $res_insert_money['message']);
        }

        return redirect()->back()
            ->with('success', $debit.'€ retiré du compte de '. $pseudo);
    }

    /**
     * préléver le montant de tous les participants pour jouer
     */
    public function debitAll(Request $request)
    {
        $nameGroup = $request->inputNameGroup;
        if (!$nameGroup || $nameGroup == '') {
            return redirect()->back()
                ->with('error', 'indiquez le groupe qui joue, s\'il vous plait');
        }

        $arrayParticipant = $this->participant->getParticipants('nameGroup', $nameGroup);

        $debitValue = $request->inputAmount;
        $nbPersonnes = count($arrayParticipant);
        $debitIndividuel = bcdiv($debitValue, $nbPersonnes, 2); //downRounding 0.9999 = 0.99
        
        foreach ($arrayParticipant as $i => $participant) {
            $money = $this->money->getMoney('id_pseudo', $participant->id);

            $debit = $debitIndividuel;
            $amount = $money[0]->amount;
            $amount = $amount - $debit;
            $totalAmount = $participant->totalAmount;
        
            $update_participant = [
                'amount'        => $amount,
                'totalAmount'   => $totalAmount,
            ];
            $res = $this->participant->updateParticipant($update_participant, $participant->id);
            
            if ($res['erreur']) {
                return redirect()->back()
                ->with('error', $res['message']);
            }

            $update_money = [
                'amount'        => $amount,
                'pseudo'        => $participant->pseudo,
                'id_pseudo'     => $participant->id,
                'debit'         => $debit,
                'date'          => $request->inputDate,
            ];
            $res = $this->money->insertMoney($update_money);
            if ($res['erreur']) {
                return redirect()->back()
                ->with('error', $res['message']);
            }
        }

        return redirect()->back()
            ->with('success', $debit.' € retiré du(des) compte(s) du(des) Participant(s)');
    }
}
