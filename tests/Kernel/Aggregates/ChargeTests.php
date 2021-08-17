<?php

namespace Mundipagg\Core\Test\Kernel\Aggregates;

use Mundipagg\Core\Kernel\Aggregates\Charge;
use Mundipagg\Core\Kernel\Aggregates\Transaction;
use Mundipagg\Core\Kernel\Exceptions\InvalidParamException;
use Mundipagg\Core\Kernel\ValueObjects\ChargeStatus;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;
use Mundipagg\Core\Kernel\ValueObjects\Id\TransactionId;
use PHPUnit\Framework\TestCase;
use Mockery;
use Carbon\Carbon;
use Mundipagg\Core\Kernel\Services\LogService;

class ChargeTests extends TestCase
{

    public $charge;

    public function setUp()
    {
        $this->charge = new Charge();
        $this->charge->logService = null;
    }

    public function testChargeShouldBeCreated()
    {

        $this->assertTrue($this->charge !== null);
    }

    public function testExpectedAnObjectOrderidToSetOrderId()
    {

        $orderId = Mockery::mock(OrderId::class);
        $this->charge->setOrderId($orderId);

        $this->assertEquals($orderId, $this->charge->getOrderId());
        $this->assertInstanceOf(OrderId::class, $this->charge->getOrderId());
    }

    public function testAmountShouldBeGreaterOrEqualToZero()
    {

        $this->charge->setAmount(10);
        $this->assertEquals(10, $this->charge->getAmount());
    }

    /**
     * @throws InvalidParamException
     * @expectedExceptionMessage Amount should be greater or equal to 0! Passed value: -10
     * @expectedExceptionCode 400
     */
    public function test_should_throw_an_exception_if_amount_is_invalid()
    {
        $this->expectException(InvalidParamException::class);


        $this->charge->setAmount(-10);
    }

    public function test_should_return_paid_amount()
    {

        $this->charge->setPaidAmount(10);
        $this->assertEquals(10, $this->charge->getPaidAmount());
    }

    public function test_should_return_zero_if_paid_amount_is_null()
    {

        $this->assertEquals(0, $this->charge->getPaidAmount());
    }

    public function test_should_return_zero_if_try_set_paid_amount_with_number_less_than_zero()
    {

        $this->charge->setPaidAmount(-10);
        $this->assertEquals(0, $this->charge->getPaidAmount());
    }

    public function test_should_return_canceled_amount()
    {

        $this->charge->setAmount(20);
        $this->charge->setCanceledAmount(10);
        $this->assertEquals(10, $this->charge->getCanceledAmount());
    }

    public function test_should_return_zero_if_canceled_amount_is_null()
    {

        $this->assertEquals(0, $this->charge->getCanceledAmount());
    }

    public function test_should_return_zero_if_try_set_canceled_amount_with_number_less_than_zero()
    {

        $this->charge->setCanceledAmount(-10);
        $this->assertEquals(0, $this->charge->getCanceledAmount());
    }

    public function test_should_return_amount_value_if_try_set_canceled_amount_with_number_greater_than_amount()
    {

        $this->charge->setAmount(5);
        $this->charge->setCanceledAmount(10);
        $this->assertEquals(5, $this->charge->getCanceledAmount());
    }


    public function test_should_return_refunded_amount()
    {

        $this->charge->setPaidAmount(20);
        $this->charge->setRefundedAmount(10);
        $this->assertEquals(10, $this->charge->getRefundedAmount());
    }

    public function test_should_return_zero_if_refunded_amount_is_null()
    {

        $this->assertEquals(0, $this->charge->getRefundedAmount());
    }

    public function test_should_return_zero_if_try_set_refunded_amount_with_number_less_than_zero()
    {

        $this->charge->setRefundedAmount(-10);
        $this->assertEquals(0, $this->charge->getRefundedAmount());
    }

    public function test_should_return_paid_amount_value_if_try_set_refunded_amount_with_number_greater_than_paid_amount()
    {

        $this->charge->setPaidAmount(5);
        $this->charge->setRefundedAmount(10);
        $this->assertEquals(5, $this->charge->getRefundedAmount());
    }

    public function test_should_return_code()
    {

        $code = "code_1234";
        $this->charge->setCode($code);

        $this->assertEquals($code, $this->charge->getCode());
    }

    public function test_expected_an_object_chargestatus_to_set_status()
    {

        $chargeStatus = ChargeStatus::paid();
        $this->charge->setStatus($chargeStatus);

        $this->assertEquals($chargeStatus, $this->charge->getStatus());
        $this->assertInstanceOf(ChargeStatus::class, $this->charge->getStatus());
    }

    public function test_should_return_metadata()
    {

        $metadata = ["metadata"];
        $this->charge->setMetadata($metadata);

        $this->assertEquals($metadata, $this->charge->getMetadata());
    }

    public function test_should_json_serialize_correctly()
    {

        $this->charge->setCode("code");

        $chargeArray = json_decode(json_encode($this->charge), true);
        $error = json_last_error();

        $this->assertArrayHasKey("code", $chargeArray);
        $this->assertEquals(0, $error);
    }

    public function test_should_return_empty_array_if_doesnt_have_transactions()
    {

        $this->assertEmpty($this->charge->getTransactions());
    }


    public function test_should_return_empty_array_if_doesnt_have_last_transaction()
    {

        $this->assertEmpty($this->charge->getLastTransaction());
    }

    public function test_should_return_the_last_transaction_that_was_added()
    {
        $transaction1 = $this->getTransaction("_____1");
        $transaction1->setCreatedAt(Carbon::now()->subMinutes(2));

        $transaction2 = $this->getTransaction("_____2");


        $this->charge->addTransaction($transaction1);
        $this->charge->addTransaction($transaction2);

        $this->assertEquals($transaction2, $this->charge->getLastTransaction());
    }

    public function test_should_return_last_transaction()
    {
        $transaction1 = $this->getTransaction("_____1");
        $transaction1->setCreatedAt(Carbon::now()->addMinutes(2));

        $transaction2 = $this->getTransaction("_____2");


        $this->charge->addTransaction($transaction1);
        $this->charge->addTransaction($transaction2);

        $this->assertEquals($transaction1, $this->charge->getLastTransaction());
    }

    public function test_should_not_add_transaction_one_more_time()
    {
        $transaction1 = $this->getTransaction("_____1");


        $this->charge->addTransaction($transaction1);
        $this->charge->addTransaction($transaction1);

        $this->assertEquals($transaction1, $this->charge->getLastTransaction());
        $this->assertCount(1, $this->charge->getTransactions());
    }

    public function test_should_update_a_transaction()
    {
        $transaction = $this->getTransaction("_____1");


        $this->charge->addTransaction($transaction);

        $transaction->setAmount(3);
        $this->charge->updateTransaction($transaction);

        $this->assertEquals(3, $this->charge->getLastTransaction()->getAmount());
        $this->assertCount(1, $this->charge->getTransactions());
    }

    public function test_should_not_overwrite_a_transaction_if_not_set_to_ovewrite()
    {
        $this->markTestSkipped();

        $transaction = $this->getTransaction("_____1");
        $transaction->setId("ID_1");


        $this->charge->addTransaction($transaction);

        $transaction->setId("ID_2");

        $ovewrite = false;
        $this->charge->updateTransaction($transaction, $ovewrite);

        $this->assertEquals("ID_1", $this->charge->getLastTransaction()->getId());
        $this->assertCount(1, $this->charge->getTransactions());
    }

    public function test_should_add_a_transaction_on_update_method_if_transaction_was_not_added()
    {
        $transaction = $this->getTransaction("_____1");
        $transaction->setCreatedAt(Carbon::now()->subMinutes(3));
        $transaction->setId("ID_1");


        $this->charge->addTransaction($transaction);

        $transaction2 = $this->getTransaction("_____2");
        $transaction2->setId("ID_2");

        $ovewrite = false;
        $this->charge->updateTransaction($transaction2, $ovewrite);

        $this->assertEquals("ID_2", $this->charge->getLastTransaction()->getId());
        $this->assertCount(2, $this->charge->getTransactions());
    }

    public function test_should_overwrite_a_transaction_if_set_to_ovewrite()
    {
        $transaction = $this->getTransaction("_____1");
        $transaction->setId("ID_1");


        $this->charge->addTransaction($transaction);

        $transaction->setId("ID_2");
        $ovewrite = true;
        $this->charge->updateTransaction($transaction, $ovewrite);

        $this->assertEquals("ID_2", $this->charge->getLastTransaction()->getId());
        $this->assertCount(1, $this->charge->getTransactions());
    }

    public function test_should_pay_partial_a_charge_and_set_status_how_underpaid()
    {
        $this->markTestSkipped();

        $this->charge->setAmount(100);
        $this->charge->setStatus(ChargeStatus::pending());

        $transaction = $this->getTransaction("_____1");
        $this->charge->addTransaction($transaction);

        $this->charge->pay(50);

        $this->assertEquals(50, $this->charge->getPaidAmount());
        $this->assertEquals(ChargeStatus::underpaid(), $this->charge->getStatus());
    }


    public function test_should_pay_a_charge_and_set_status_how_paid()
    {

        $this->charge->setAmount(100);
        $this->charge->setStatus(ChargeStatus::pending());

        $transaction = $this->getTransaction("_____1");
        $this->charge->addTransaction($transaction);

        $this->charge->pay(100);

        $this->assertEquals(100, $this->charge->getPaidAmount());
        $this->assertEquals(ChargeStatus::paid(), $this->charge->getStatus());
    }


    public function test_should_pay_a_charge_with_value_greater_then_amount_and_set_status_how_overpaid()
    {

        $this->charge->setAmount(100);
        $this->charge->setStatus(ChargeStatus::pending());

        $transaction = $this->getTransaction("_____1");
        $transaction->setPaidAmount(200);
        $this->charge->addTransaction($transaction);

        // verificar se o valor d transação é que cota para setar uma charge para overpaid
        $this->charge->pay(200);

        $this->assertEquals(200, $this->charge->getPaidAmount());
        $this->assertEquals(ChargeStatus::overpaid(), $this->charge->getStatus());
    }

    public function test_if_not_was_passed_a_value_to_cancel_should_cancel_the_paid_value()
    {

        $this->charge->setAmount(100);
        $this->charge->setStatus(ChargeStatus::pending());

        $transaction = $this->getTransaction("_____1");
        $this->charge->addTransaction($transaction);

        $this->charge->pay(100);

        $this->charge->cancel();

        $this->assertEquals(100, $this->charge->getRefundedAmount());
        $this->assertEquals(ChargeStatus::canceled(), $this->charge->getStatus());
    }

    public function test_should_cancel_partial_and_set_status_how_canceled()
    {

        $this->charge->setAmount(100);
        $this->charge->setStatus(ChargeStatus::pending());

        $this->charge->cancel(50);

        $this->assertEquals(
            100,
            $this->charge->getCanceledAmount(),
            "The canceled amount is the total charge value because the charge was not paid"
        );
        $this->assertEquals(ChargeStatus::canceled(), $this->charge->getStatus());
    }

    public function test_should_refound_partial_value_paid_and_continue_with_status_how_paid()
    {

        $this->charge->setAmount(100);
        $this->charge->setStatus(ChargeStatus::pending());

        $transaction = $this->getTransaction("_____1");
        $this->charge->addTransaction($transaction);

        $this->charge->pay(100);

        $this->charge->cancel(50);

        $this->assertEquals(
            50,
            $this->charge->getRefundedAmount()
        );
        $this->assertEquals(ChargeStatus::paid(), $this->charge->getStatus());
    }

    public function getTransaction($endId)
    {
        $transactionId = "tran_1234567890" . $endId;

        $transaction = new Transaction();
        $transaction->setMundipaggId(
            new TransactionId($transactionId)
        );
        $transaction->setCreatedAt(Carbon::now());

        return $transaction;
    }
}
