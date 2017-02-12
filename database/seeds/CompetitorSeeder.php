<?php

use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Xoco70\KendoTournaments\Models\Championship;
use Xoco70\KendoTournaments\Models\Competitor;


class CompetitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Competitors seeding!');
        $faker = Faker::create();

        $championship = Championship::where('tournament_id', 1)->first();


        $users[] = factory(User::class)->create(['name' => 't1']);
        $users[] = factory(User::class)->create(['name' => 't2']);
        $users[] = factory(User::class)->create(['name' => 't3']);
        $users[] = factory(User::class)->create(['name' => 't4']);

        foreach ($users as $id => $user) {
            factory(Competitor::class)->create([
                'championship_id' => $championship->id,
                'user_id' => $user->id,
                'confirmed' => 1,
            ]);
        }

    }
}
