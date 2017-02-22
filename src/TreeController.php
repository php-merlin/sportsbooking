<?php

namespace Xoco70\KendoTournaments;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Xoco70\KendoTournaments\Exceptions\TreeGenerationException;
use Xoco70\KendoTournaments\Models\Championship;
use Xoco70\KendoTournaments\Models\ChampionshipSettings;
use Xoco70\KendoTournaments\Models\Competitor;
use Xoco70\KendoTournaments\Models\Round;
use Xoco70\KendoTournaments\Models\Tournament;
use Xoco70\KendoTournaments\TreeGen\TreeGen;

class TreeController extends Controller
{
    /**
     * Display a listing of trees.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $tournament = Tournament::with(
            'competitors',
            'championshipSettings',
            'championships.settings',
            'championships.category')->first();

        return view('kendo-tournaments::tree.index')
            ->with('tournament', $tournament)
            ->with('settings', $tournament->championships[0]->setting);
    }

    /**
     * Build Tree.
     *
     * @param Request      $request
     * @param Championship $championship
     *
     * @return \Illuminate\Http\Response|string
     */
    public function store(Request $request, Championship $championship)
    {

        DB::table('fight')->delete();
        DB::table('round')->delete();
        DB::table('round_competitor')->delete();
        DB::table('round_team')->delete();
        DB::table('competitor')->delete();
        
        $championship = Championship::with('teams', 'users', 'category', 'settings')->find($championship->id);

        $numFighters = $request->numFighters;

        $users = factory(User::class, (int)$numFighters)->create();

        foreach ($users as $user) {
            factory(Competitor::class)->create([
                'championship_id' => $championship->id,
                'user_id' => $user->id,
                'confirmed' => 1,
            ]);
        }

        $settings = ChampionshipSettings::createOrUpdate($request, $championship);


        //TODO Set groupBy argument to NULL for now
        $generation = new TreeGen($championship, null, $settings);
        try {
            $rounds = $generation->run();
            Round::generateFights($rounds, $settings, $championship);
        } catch (TreeGenerationException $e) {
            redirect()->back()
                ->withErrors([$numFighters ."-".$e->getMessage()]);
        }

        return redirect()->back()
            ->with('numFighters', $numFighters)
            ->with(['success' , "Success"]);
    }
}
