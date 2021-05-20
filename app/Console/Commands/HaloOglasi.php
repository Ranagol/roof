<?php

namespace App\Console\Commands;

use App\Repository\Crawler\HaloOglasiCrawlerRepository;
use Illuminate\Console\Command;

class HaloOglasi extends Command
{
	/**
	 * The name and signature of the console command.
	 * Example: php artisan halo-oglasi
	 *
	 * @var string $signature
	 */
    protected $signature = 'halo-oglasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


	/**
	 * Execute the console command.
	 *
	 * @return void
	 * @throws \Exception
	 */
    public function handle(): void
    {
        //https://laravel.com/docs/8.x/artisan#options
        $repository = new HaloOglasiCrawlerRepository();
        $ads = $repository->getAds();
        $repository->saveAds($ads);

        echo 'Ads were successfully retrieved' . PHP_EOL;
    }
}
