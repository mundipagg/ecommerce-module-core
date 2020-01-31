<?php

namespace Mundipagg\Core\Test\Maintenance\Services;

use Mundipagg\Core\Maintenance\Services\LogInfoRetrieverService;
use Mundipagg\Core\Test\Abstractions\AbstractSetupTest;

class LogInfoRetrieverServiceTest extends AbstractSetupTest
{
    /**
     * @var LogInfoRetrieverService
     */
    private $logInfoRetriever;

    public function setUp()
    {
        parent::setUp();
        $this->logInfoRetriever = new LogInfoRetrieverService();
    }

    public function testRetrieveInfo()
    {
        $_SERVER['REQUEST_URI'] = 'localhost';
        $logInfoRetrieverObj = $this->logInfoRetriever->retrieveInfo('2020-12-12');

        $this->assertEquals('/tmp/logs', $logInfoRetrieverObj->moduleLogsDirectory);
        $this->assertInternalType('array', $logInfoRetrieverObj->platformLogsDirectories);
        $this->assertInternalType('array', $logInfoRetrieverObj->files);
        $this->assertInternalType('array', $logInfoRetrieverObj->donwloadURIs);
    }
}
