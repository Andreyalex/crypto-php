<?php

namespace App\Console\Commands;

use App\Robot\Binance\Spot;
use function array_key_exists;
use Faker\Core\DateTime;
use Illuminate\Console\Command;
use function Ramsey\Uuid\Generator\timestamp;
use function var_dump;

class EarnApr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'earn:apr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current Earn APR';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Spot([
            'key' => env('BINANCE_API_KEY'),
            'secret' => env('BINANCE_API_SECRET')
        ]);
        $response = $client->flexibleList(['asset' => 'USDT']);
        if (array_key_exists('rows', $response)) {
            foreach($response['rows'] as $row) {
                $model = new \App\Models\EarnApr([
                    'asset' => $row['asset'],
                    'earn_apr' => $row['latestAnnualPercentageRate']
                ]);
                $model->save();
            }
        }
        return 0;
    }
}
