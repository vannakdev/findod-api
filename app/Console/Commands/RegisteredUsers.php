<?php

namespace App\Console\Commands;

use App\Feedback;
//use Illuminate\Support\Facades\Mail;
//use App\Mail\SendMailable;
use App\Http\Controllers\NotificationController;
use App\Scheduler;
use Illuminate\Console\Command;

class RegisteredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email of registered users';

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
     * @return mixed
     */
    public function handle()
    {
//        $totalUsers = \DB::table('users')
//                  ->whereRaw('Date(created_at) = CURDATE()')
//                  ->count();
//        Mail::to('krunal@appdividend.com')->send(new SendMailable($totalUsers));
//
//        $feedback = new Feedback();
//        $feedback->email = "test@emial.com";
//        $feedback->name = "test cronjob";
//        $feedback->message = "Test message send by conjob";
//        $feedback->save();

//        $notify = new NotificationController();
//        $notify->sentNotify($request);

        $schedulers = new Scheduler();
        foreach ($schedulers as $processer) {
            $request = json_decode($processer->request);
            $feedback = new Feedback();
            $feedback->email = 'test@emial.com';
            $feedback->name = 'test cronjob';
            $feedback->message = $request;
            $feedback->save();
        }
    }
}
