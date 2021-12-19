<?php

namespace App\Tests\Integration;

use App\Dto\Compare;
use App\Dto\ComparedRepo;
use App\Service\CompareService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CompareServiceTest extends KernelTestCase
{
    public function testGetComparedRepo(): void
    {
        $service = $this->getService();
        $compared = $service->getComparedRepo('symfony/symfony');

        $this->assertInstanceOf(ComparedRepo::class, $compared);
    }

    public function testCompare(): void
    {
        $service = $this->getService();
        $compare = $service->compare('https://github.com/symfony/symfony', 'https://github.com/laravel/laravel');

        $this->assertInstanceOf(Compare::class, $compare);
    }

    public function getService(): CompareService
    {
        self::bootKernel();
        $container = static::getContainer();

        return $container->get(CompareService::class);
    }
}
