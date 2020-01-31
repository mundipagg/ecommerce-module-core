<?php

namespace Mundipagg\Core\Test\Maintenance\Services;

use Mundipagg\Core\Maintenance\Services\VersionInfoRetrieverService;
use Mundipagg\Core\Test\Abstractions\AbstractSetupTest;
use Mundipagg\Core\Test\Mock\Concrete\PlatformCoreSetup;

class VersionInfoRetrieverServiceTest extends AbstractSetupTest
{
    /**
     * @var VersionInfoRetrieverService
     */
    private $versionInfoRetrieverService;

    public function setUp()
    {
        parent::setUp();
        $this->versionInfoRetrieverService = new VersionInfoRetrieverService();
    }

    public function testRetrieveInfo()
    {
        $retrieveInfo = $this->versionInfoRetrieverService->retrieveInfo('');

        $this->assertEquals(phpversion(), $retrieveInfo->phpVersion);
        $this->assertEquals(
            PlatformCoreSetup::class,
            $retrieveInfo->platformCoreConcreteClass
        );

        $this->assertEquals('1.0.0', $retrieveInfo->moduleVersion);
        $this->assertEquals('1.12.1', $retrieveInfo->coreVersion);
        $this->assertEquals('1.0.0', $retrieveInfo->platformVersion);
    }
}
