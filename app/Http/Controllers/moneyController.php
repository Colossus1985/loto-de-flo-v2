<?php

namespace App\Http\Controllers;

use App\Repositories\ParticipantRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;

use Illuminate\Http\Request;

class moneyController extends Controller
{
    protected $participant;
    protected $groups;
    protected $money;

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
     * @param string nom du groupe
     */
    public function addMoney(Request $request, $id_participant)
    {
        $participant    = $this->participant->getParticipant('id', $id_participant);
        $money          = $this->money->getMoney('id_pseudo', $id_participant);
        $group          = $this->groups->getGroup('nameGroup', [$request->input_group_name]);
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
            'id_group'      => $group[0]->id,
            'group_name'    => $group[0]->nameGroup,
            'date'          => now(),
        ];
        $res_insert_money = $this->money->insertMoney($champs);
        if ($res_insert_money['erreur']) {
            return redirect()->back()
                ->with('error', $res_insert_money['message']);
        }

        return redirect()->back()
            ->with('success', ifNotZero($request->inputMontant, true, ' €', '.', ' ').' ajouté sur le compte de '. $pseudo .' du groupe '. $request->input_group_name);
    }

    /**
     * retire un montant en tant que gain ou crédit pour un participant
     * @param Request
     * @param int id du participant
     * @param string nom du groupe
     */
    public function debitMoney(Request $request, $id_participant)
    {
        $participant    = $this->participant->getParticipant('id', $id_participant);
        $money          = $this->money->getMoney('id_pseudo', $id_participant);
        $group          = $this->groups->getGroup('nameGroup', [$request->input_group_name]);
        $correction     = isset($request->input_correction) ? $request->input_correction : 0;
        $debit          = $request->inputMontant;
        $pseudo         = $request->input_pseudo;

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
            'id_group'      => $group[0]->id,
            'group_name'    => $group[0]->nameGroup,
            'date'          => now(),
            'correction'    => $correction,
        ];
        $res_insert_money = $this->money->insertMoney($champs);
        if ($res_insert_money['erreur']) {
            return redirect()->back()
                ->with('error', $res_insert_money['message']);
        }

        return redirect()->back()
            ->with('success', ifNotZero($debit, true, ' €', '.', ' ').' retiré du compte de '. $pseudo .' du groupe '. $request->input_group_name);
    }

    /**
     * préléver le montant de tous les participants pour jouer
     */
    public function debitAll(Request $request)
    {
        $nameGroup      = $request->inputNameGroup;
        $group          = $this->groups->getGroup('nameGroup', [$request->inputNameGroup]);
        $date           = $request->filled('inputDate') ? $request->input('inputDate') : now()->toDateString();

        // dd($date);
        if (!$nameGroup || $nameGroup == '') {
            return redirect()->back()
                ->with('error', 'indiquez le groupe qui joue, s\'il vous plait');
        }

        $arrayParticipant = $this->participant->getParticipants('group_id', $group[0]->id);
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
            // dd($date);

            $update_money = [
                'amount'        => $amount,
                'pseudo'        => $participant->pseudo,
                'id_pseudo'     => $participant->id,
                'debit'         => $debit,
                'date'          => $date,
                'id_group'      => $group[0]->id,
                'group_name'    => $group[0]->nameGroup,
            ];
            $res = $this->money->insertMoney($update_money);
            if ($res['erreur']) {
                return redirect()->back()
                ->with('error', $res['message']);
            }
        }

        $liste_pseudo = [];
        foreach ($arrayParticipant as $data) {
            $liste_pseudo[] = $data->pseudo;
        }
        // Convertir le tableau des pseudonymes en une chaîne
        $liste_pseudo_str = implode(', ', $liste_pseudo);

        return redirect()->back()
            ->with('success', $debit.' € retiré du(des) compte(s) de ' . $liste_pseudo_str . ' du groupe ' . $nameGroup);
    }

    /**
     * supprimer une ligne de l'historisation détail mouvement d'un participant
     * @param int id ligne
     */
    public function delLigDetailParticipant(int $id_ligne)
    {
        $update_money = [
            'del'        => 1,
        ];
        $res = $this->money->updateMoney($update_money, $id_ligne);
        if ($res['erreur']) {
            return redirect()->back()
            ->with('error', $res['message']);
        }

        return redirect()->back()->with('success', $res['message']);
    }

}
