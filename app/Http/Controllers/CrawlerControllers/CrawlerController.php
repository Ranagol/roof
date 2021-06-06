<?php


namespace App\Http\Controllers\CrawlerControllers;

use App\Repository\Interfaces\CrawlerRepositoryInterface;

abstract class CrawlerController
{
    /**
     * @var CrawlerRepositoryInterface
     */
    private $repository;

    public function __construct(CrawlerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * __invoke
     *
     * @url https://laravel.com/docs/8.x/controllers#single-action-controllers
     * @url https://www.php.net/manual/en/language.oop5.magic.php#object.invoke
     *
     * @throws \Exception Couldn't save the ads.
     */
    public function __invoke()
    {
        $ads = $this->repository->getAds();
        $this->repository->saveAds($ads);
    }
}
