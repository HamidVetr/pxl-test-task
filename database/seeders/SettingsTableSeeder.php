<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();

        Setting::query()->create([
            'key' => Setting::LAST_PEOPLE_INSERTED_INDEX,
            'value' => 0,
        ]);
    }
}
