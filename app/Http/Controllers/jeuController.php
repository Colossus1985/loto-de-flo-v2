<?php

namespace App\Http\Controllers;

use App\Repositories\ParticipantRepository;
use App\Repositories\GroupsRepository;
use App\Repositories\MoneyRepository;
use App\Repositories\GainRepository;
use App\Repositories\JeuRepository;
use Symfony\Component\HttpFoundation\Request;

class jeuController extends Controller
{
    protected $participant;
    protected $groups;
    protected $money;
    protected $gain;
    protected $jeu;
    
    public function __construct(
        ParticipantRepository $participant,
        GroupsRepository $groups,
        MoneyRepository $money,
        GainRepository $gain,
        JeuRepository $jeu,
        )
    {
        $this->participant      = $participant;
        $this->groups           = $groups;
        $this->money            = $money;
        $this->gain             = $gain;
        $this->jeu              = $jeu;
    }

    /**
     * ajout d'un nouveau jeu
     * partage du montant du jeu parmi les personnes dans le group
     */
    public function jeuHistory()
    {
        // dd($request);
    }

    public function getJeuHistory()
    {
        $jeux   = $this->jeu->getJeux();

        return view('pages.jeuHistorique', [
            'jeux' => $jeux,
        ]);
    } 

    /**
     * supprimer une ligne jeu de l'historisation  et de chaque participant
     * @param int id ligne
     */
    public function delLigJeux(int $id_ligne)
    {
        $res = $this->jeu->updateDelLigJeux($id_ligne);
        if ($res['erreur']) {
            return redirect()->back()
            ->with('error', $res['message']);
        }

        return redirect()->back()->with('success', $res['message']);
    }
}
