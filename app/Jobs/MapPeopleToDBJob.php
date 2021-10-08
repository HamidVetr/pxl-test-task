<?php

namespace App\Jobs;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use JsonMachine\JsonMachine;

class MapPeopleToDBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 0);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lastInsertedIndexDBRecord = Setting::query()->where('key', Setting::LAST_PEOPLE_INSERTED_INDEX)->first();
        $lastInsertedIndex = 0;
        if (!is_null($lastInsertedIndexDBRecord)) {
            $lastInsertedIndex = $lastInsertedIndexDBRecord->value;
        } else {
            $lastInsertedIndexDBRecord = Setting::query()->create([
                'key' => Setting::LAST_PEOPLE_INSERTED_INDEX,
                'value' => 0,
            ]);
        }

        $people = JsonMachine::fromFile(storage_path('app' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'challenge.json'));

        foreach ($people as $index => $person) {
            if ($index >= $lastInsertedIndex) {
                $dateOfBirth = $this->unifyDateOfBirth($person['date_of_birth']);
                if (is_null($dateOfBirth) || ($dateOfBirth['age'] >= 18 && $dateOfBirth['age'] <= 65)) {
                    try {
                        DB::beginTransaction();
                        $user = User::query()->create([
                            'name' => $person['name'],
                            'account' => $person['account'],
                            'email' => $person['email'],
                            'address' => $person['address'],
                            'description' => $person['description'],
                            'date_of_birth' => $dateOfBirth['date'],
                            'checked' => $person['checked'],
                        ]);

                        if ($user->wasRecentlyCreated) {
                            if (!is_null($person['interest'])) {
                                $user->interests()->create([
                                    'title' => $person['interest'],
                                ]);
                            }

                            if (!is_null($person['credit_card'])) {
                                $user->creditCards()->create([
                                    'type' => $person['credit_card']['type'],
                                    'name' => $person['credit_card']['name'],
                                    'number' => $person['credit_card']['number'],
                                    'expiration_date' => Carbon::parse(Carbon::createFromFormat('m/y', $person['credit_card']['expirationDate'])->format('Y-m-d'))->endOfMonth()->format('Y-m-d'),
                                ]);
                            }
                        }

                        $lastInsertedIndexDBRecord->increment('value');
                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollBack();
                    }
                }
            }
        }
    }

    /**
     * convert old date of birth to a new unified format
     *
     * @param $dateOfBirth
     * @return array|null
     */
    private function unifyDateOfBirth($dateOfBirth): ?array
    {
        $unifiedBirthDateFormat = 'Y-m-d';

        if (!is_null($dateOfBirth)) {
            if (str_contains($dateOfBirth, '/')) {
                return [
                    'date' => Carbon::createFromFormat('d/m/Y', $dateOfBirth)->format($unifiedBirthDateFormat),
                    'age' => Carbon::createFromFormat('d/m/Y', $dateOfBirth)->age,
                ];
            } else {
                return [
                    'date' => Carbon::parse($dateOfBirth)->format($unifiedBirthDateFormat),
                    'age' => Carbon::parse($dateOfBirth)->age,
                ];
            }
        }

        return null;
    }
}
