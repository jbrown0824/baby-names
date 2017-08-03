<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $thisWeek = strtotime('last sunday');
//        if (date('N') == 7) {
//            $thisWeek = strtotime('today');
//        }

        $thisWeek = date("Y-m-d H:i:s", $thisWeek);

        DB::table('voters')->insert([
            'name' => 'Jeff',
            'votes_available' => 10,
            'refills_on' => $thisWeek,
        ]);

        DB::table('voters')->insert([
            'name' => 'Heather',
            'votes_available' => 10,
            'refills_on' => $thisWeek,
        ]);

        DB::table('names')->insert([
            'name' => 'Abigail',
            'votes' => 1,
            'rank_last_week' => 1,
        ]);

        DB::table('names')->insert([
            'name' => 'Shelby',
            'votes' => 1,
            'rank_last_week' => 1,
        ]);

        DB::table('names')->insert([
            'name' => 'Murphy',
            'votes' => 1,
            'rank_last_week' => 1,
        ]);

        DB::table('names')->insert([
            'name' => 'Elle',
            'votes' => 1,
            'rank_last_week' => 1,
        ]);
        DB::table('names')->insert([
            'name' => 'Madaline',
            'votes' => 1,
            'rank_last_week' => 1,
        ]);
    }
}
