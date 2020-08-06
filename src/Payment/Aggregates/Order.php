<?php

namespace Mundipagg\Core\Payment\Aggregates;

use MundiAPILib\Models\CreateOrderRequest;
use Mundipagg\Core\Kernel\Abstractions\AbstractEntity;
use Mundipagg\Core\Payment\Aggregates\Payments\AbstractPayment;
use Mundipagg\Core\Payment\Aggregates\Payments\SavedCreditCardPayment;
use Mundipagg\Core\Payment\Interfaces\ConvertibleToSDKRequestsInterface;
use Mundipagg\Core\Payment\Traits\WithAmountTrait;
use Mundipagg\Core\Payment\Traits\WithCustomerTrait;
use Mundipagg\Core\Kernel\Abstractions\AbstractModuleCoreSetup as MPSetup;
use Mundipagg\Core\Kernel\ValueObjects\PaymentMethod as PaymentMethod;
use Mundipagg\Core\Kernel\Services\LocalizationService;

final class Order extends AbstractEntity implements ConvertibleToSDKRequestsInterface
{
    use WithAmountTrait;
    use WithCustomerTrait;

    private $paymentMethod;

    /** @var string */
    private $code;
    /** @var Item[] */
    private $items;
    /** @var null|Shipping */
    private $shipping;
    /** @var AbstractPayment[] */
    private $payments;
    /** @var boolean */
    private $closed;

    /** @var boolean */
    private $antifraudEnabled;

    public function __construct()
    {
        $this->payments = [];
        $this->items = [];
        $this->closed = true;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = substr($code, 0, 52);
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function addItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * @return Shipping|null
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @param Shipping|null $shipping
     */
    public function setShipping($shipping)
    {
        $this->shipping = $shipping;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param string $paymentMethodName
     */
    public function setPaymentMethod($paymentMethodName)
    {
        $replace = str_replace('_', '', $paymentMethodName);
        $paymentMethodObject = $replace . 'PaymentMethod';

        $this->paymentMethod = $this->$paymentMethodObject();
    }

    /**
     * @return AbstractPayment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }

    public function addPayment(AbstractPayment $payment)
    {
        $this->validatePaymentInvariants($payment);
        $this->blockOverPaymentAttempt($payment);

        $payment->setOrder($this);

        if ($payment->getCustomer() === null) {
            $payment->setCustomer($this->getCustomer());
        }

        $this->payments[] = $payment;
    }

    /**
     * @return bool
     */
    public function isPaymentSumCorrect()
    {
        if (
            $this->amount === null ||
            empty($this->payments)
        ) {
            return false;
        }

        $sum = 0;
        foreach ($this->payments as $payment)
        {
            $sum += $payment->getAmount();
        }

        return $this->amount === $sum;
    }

    /**
     *  Blocks any overpayment attempt.
     *
     * @param AbstractPayment $payment
     * @throws \Exception
     */
    private function blockOverPaymentAttempt(AbstractPayment $payment)
    {
        $currentAmount = $payment->getAmount();
        foreach ($this->payments as $currentPayment) {
            $currentAmount += $currentPayment->getAmount();
        }

        if ($currentAmount > $this->amount) {
            $i18n = new LocalizationService();
            $message = $i18n->getDashboard("The sum of payment amounts is bigger than the amount of the order!");

            throw new \Exception($message, 400);
        }
    }

    /**
     * Calls the invariant validator method of each payment method, if applicable.
     *
     * @param AbstractPayment $payment
     * @throws \Exception
     */
    private function validatePaymentInvariants(AbstractPayment $payment)
    {
        $paymentClass = $this->discoverPaymentMethod($payment);
        $paymentValidator = "validate$paymentClass";

        if (method_exists($this, $paymentValidator)) {
            $this->$paymentValidator($payment);
        }
    }

    private function discoverPaymentMethod(AbstractPayment $payment)
    {
        $paymentClass = get_class($payment);
        $paymentClass = explode ('\\', $paymentClass);
        $paymentClass = end($paymentClass);
        return $paymentClass;
    }

    private function validateSavedCreditCardPayment(SavedCreditCardPayment $payment)
    {
        $i18n = new LocalizationService();

        if ($this->customer === null) {
            $message = $i18n->getDashboard("To use a saved credit card payment in an order you must add a customer to it.");

            throw new \Exception($message, 400);
        }

        $customerId = $this->customer->getMundipaggId();
        if ($customerId === null) {
            $message = $i18n->getDashboard("You can\'t use a saved credit card of a fresh new customer.");

            throw new \Exception($message, 400);
        }

        if (!$customerId->equals($payment->getOwner())) {
            $message = $i18n->getDashboard("The saved credit card informed doesn\'t belong to the informed customer.");

            throw new \Exception($message, 400);
        }
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * @param bool $closed
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;
    }

    /**
     * @return bool
     */
    public function isAntifraudEnabled()
    {
        $payments = $this->getPayments();

        foreach ($payments as $payment) {
            $payment;
        }

        $antifraudMinAmount = MPSetup::getModuleConfiguration()->getAntifraudMinAmount();

        if ($this->amount < $antifraudMinAmount) {
            return false;
        }
        return $this->antifraudEnabled;
    }

    /**
     * @param bool $antifraudEnabled
     */
    public function setAntifraudEnabled($antifraudEnabled)
    {
        $this->antifraudEnabled = $antifraudEnabled;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->customer = $this->getCustomer();
        $obj->code = $this->getCode();
        $obj->items = $this->getItems();

        $shipping = $this->getShipping();
        if ($shipping !== null) {
            $obj->shipping = $this->getShipping();
        }

        $obj->payments = $this->getPayments();
        $obj->closed = $this->isClosed();
        $obj->antifraudEnabled = $this->isAntifraudEnabled();

        return $obj;
    }

    /**
     * @return CreateOrderRequest
     */
    public function convertToSDKRequest()
    {
        $orderRequest = new CreateOrderRequest();

        $orderRequest->antifraudEnabled = $this->isAntifraudEnabled();
        $orderRequest->closed = $this->isClosed();
        $orderRequest->code = $this->getCode();
        $orderRequest->customer = $this->getCustomer()->convertToSDKRequest();

        $orderRequest->payments = [];
        foreach ($this->getPayments() as $payment) {
            $orderRequest->payments[] = $payment->convertToSDKRequest();
        }

        $orderRequest->items = [];
        foreach ($this->getItems() as $item) {
            $orderRequest->items[] = $item->convertToSDKRequest();
        }

        $shipping = $this->getShipping();
        if ($shipping !== null) {
            $orderRequest->shipping = $shipping->convertToSDKRequest();
        }

        return $orderRequest;
    }

    private function creditcardPaymentMethod()
    {
        return PaymentMethod::credit_card();
    }

    private function boletoPaymentMethod()
    {
        return PaymentMethod::boleto();
    }

    private function voucherPaymentMethod()
    {
        return PaymentMethod::voucher();
    }

    private function debitPaymentMethod()
    {
        return PaymentMethod::debit_card();
    }
}