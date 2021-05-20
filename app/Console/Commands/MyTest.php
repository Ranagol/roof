<?php

namespace App\Console\Commands;

use App\Repository\MyTest\MyTestRepository;
use Illuminate\Console\Command;

class MyTest extends Command
{
    /**
     * The name and signature of the console command.
     * Example how to call this command: php artisan mytest
     *
     * @var string
     */
    protected $signature = 'mytest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just playing around with artisan commands and repository topics.';

    /**
     * Execute the console command.
     *
     * @return
     */
    public function handle()
    {
        $repository = new MyTestRepository();
        $repository->echoSomethingFromInterface();
    }
}
