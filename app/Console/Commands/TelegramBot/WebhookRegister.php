<?php

namespace App\Console\Commands\TelegramBot;

use Illuminate\Console\Command;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use function env;

class WebhookRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tgbot:webhook:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            // Create Telegram API object
            $telegram = new Telegram(
                env('TELEGRAM_BOT_TOKEN'),
                env('TELEGRAM_BOT_USERNAME')
            );

            // Set webhook
            $result = $telegram->setWebhook(env('TELEGRAM_BOT_WEBHOOK'));
            if ($result->isOk()) {
                $this->info($result->getDescription());
            }
        } catch (TelegramException $e) {
            // log telegram errors
            $this->error($e->getMessage());
        }
    }
}
