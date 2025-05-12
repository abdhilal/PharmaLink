<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\WelcomeMail;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $users = User::all();

        foreach ($users as $user) {

            SendWelcomeEmail::dispatch($user);


        }

        $this->info('Emails sent successfully!');

    }
}
