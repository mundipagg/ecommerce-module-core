<?php

namespace Mundipagg\Core\Test\Maintenance\Services;

use Mundipagg\Core\Maintenance\Services\PhpInfoRetrieverService;
use PHPUnit\Framework\TestCase;

class PhpInfoRetrieverServiceTest extends TestCase
{

    /**
     * @var PhpInfoRetrieverService
     */
    private $phpInfoRetrieverService;

    public function setUp()
    {
        $this->phpInfoRetrieverService = new PhpInfoRetrieverService();
    }

    public function testRetrieveInfo()
    {
        $retrieveInfo = $this->phpInfoRetrieverService->retrieveInfo('');

        $this->assertContains('php', $retrieveInfo);
    }
}
