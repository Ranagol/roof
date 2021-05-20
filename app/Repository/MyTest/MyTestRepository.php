<?php

namespace App\Repository\MyTest;

use App\MyTest\MyTest;
use App\Repository\Interfaces\MyTestRepositoryInterface;

class MyTestRepository implements MyTestRepositoryInterface
{
    private $mytest;

    public function __construct()
    {
        $this->mytest = new MyTest();//here we create a new instance of the desired class
    }

    public function echoSomethingFromInterface()
    {
        return $this->mytest->echoSomethingFromMyTest();
    }
}
