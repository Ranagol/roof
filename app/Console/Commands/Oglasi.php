<?php

namespace App\Console\Commands;

use App\Repository\Crawler\OglasiCrawlerRepository;
use Illuminate\Console\Command;

class Oglasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oglasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The oglasi command will start the crawling process for new ads,
    which will be saved in the db.';


	/**
	 * Execute the console command.
	 *
	 * @return void
	 * @throws \Exception
	 */
    public function handle()
    {
        $repository = new OglasiCrawlerRepository();
        $ads = $repository->getAds();
        $repository->saveAds($ads);

        echo 'Ads were successfully retrieved' . PHP_EOL;
    }
}
