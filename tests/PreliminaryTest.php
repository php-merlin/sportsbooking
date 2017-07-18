<?php

namespace Xoco70\KendoTournaments\Tests;


use Illuminate\Foundation\Testing\DatabaseTransactions;
use Xoco70\KendoTournaments\Models\Championship;
use Xoco70\KendoTournaments\Models\Tournament;

class PreliminaryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function check_number_of_groups_when_generating_preliminary_tree()
    {
//        $numGroupsExpected = [0, 1, 1, 2, 2, 2, 4, 4];
        $numAreas = [1, 2, 4];
        $competitorsInTree =
                 [1, 2, 3, 4, 5, 6, 7, 8];
        $numPreliminaryGroups = [
            3 => [0, 1, 1, 2, 2, 2, 4, 4, 4, 8, 8],
            4 => [0, 1, 1, 1, 2, 2, 2, 2, 4, 4, 4],
            5 => [0, 1, 1, 1, 1, 2, 2, 2, 2, 2, 4],
        ];

        foreach ($numPreliminaryGroups as $preliminaryGroupSize => $numPreliminaryGroup) {
            foreach ($numAreas as $numArea) {
                foreach ($competitorsInTree as $numCompetitors) {
                    $setting = $this->createSetting($numArea, $numCompetitors, 0, 1, $preliminaryGroupSize);// $team
                    $this->generateTreeWithUI($setting);
                    parent::checkGroupsNumber($this->championshipWithComp, $numArea, $numCompetitors, $numPreliminaryGroup,$preliminaryGroupSize, __METHOD__);
                }
            }
        }
    }


    /** @test */
    public function check_number_of_fights_when_preliminary_tree()
    {
        $competitorsInTree = [1, 2, 3, 4, 5, 6, 7, 8];
        $numFightsExpected = [0, 1, 2, 2, 4, 4, 4, 4];

        $numAreas = [1, 2];
        $numPreliminaryGroups = [3];

        foreach ($numPreliminaryGroups as $numPreliminaryGroup) {
            foreach ($numAreas as $numArea) {
                foreach ($competitorsInTree as $numCompetitors) {
                    $setting = $this->createSetting($numArea, $numCompetitors, 0, 0, $numPreliminaryGroup);// $team
                    $this->generateTreeWithUI($setting);
                    parent::checkFightsNumber($this->championshipWithComp, $numArea, $numCompetitors, $numFightsExpected, __METHOD__);
                }
            }
        }
    }

}
