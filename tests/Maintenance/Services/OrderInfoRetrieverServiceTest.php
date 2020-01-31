<?php

namespace Mundipagg\Core\Test\Maintenance\Services;

use Mundipagg\Core\Maintenance\Services\OrderInfoRetrieverService;
use Mundipagg\Core\Test\Abstractions\AbstractSetupTest;
use stdClass;

class OrderInfoRetrieverServiceTest extends AbstractSetupTest
{
    /**
     * @var OrderInfoRetrieverService
     */
    private $orderInfoRetriever;

    public function setUp()
    {
        parent::setUp();
        $this->orderInfoRetriever = new OrderInfoRetrieverService();
    }


    public function testRetrieveInfo()
    {
        $retrieveInfo = $this->orderInfoRetriever->retrieveInfo('000000010');

        $this->assertInstanceOf(stdClass::class, $retrieveInfo->core);
        $this->assertInstanceOf(stdClass::class, $retrieveInfo->platform);
    }
}
