<?php

namespace Mundipagg\Core\Test\Mock\Concrete;

use Mundipagg\Core\Kernel\Interfaces\PlatformInvoiceInterface;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;
use Mundipagg\Core\Kernel\ValueObjects\OrderState;
use Mundipagg\Core\Kernel\ValueObjects\OrderStatus;
use Mundipagg\Core\Payment\Aggregates\Customer;
use Mundipagg\Core\Payment\Aggregates\Item;
use Mundipagg\Core\Payment\Aggregates\Payments\AbstractPayment;
use Mundipagg\Core\Payment\Aggregates\Shipping;

class FakeOrder
{
    public function save()
    {
        // TODO: Implement save() method.
    }

    /**
     *
     * @return OrderState
     */
    public function getState()
    {
        // TODO: Implement getState() method.
    }

    public function setState(OrderState $state)
    {
        // TODO: Implement setState() method.
    }

    public function setStatus(OrderStatus $status)
    {
        // TODO: Implement setStatus() method.
    }

    public function getStatus()
    {
        // TODO: Implement getStatus() method.
    }

    public function loadByIncrementId($incrementId)
    {
        // TODO: Implement loadByIncrementId() method.
    }

    public function addHistoryComment($message)
    {
        // TODO: Implement addHistoryComment() method.
    }

    public function getHistoryCommentCollection()
    {
        return [
            0 =>
                array(
                    'entity_id' => '36',
                    'parent_id' => '9',
                    'is_customer_notified' => null,
                    'is_visible_on_front' => '0',
                    'comment' => 'MP - Subscription canceled',
                    'status' => 'processing',
                    'created_at' => '2020-01-17 19:48:08',
                    'entity_name' => 'order',
                    'store_id' => '1',
                ),
            1 =>
                array(
                    'entity_id' => '35',
                    'parent_id' => '9',
                    'is_customer_notified' => null,
                    'is_visible_on_front' => '0',
                    'comment' => 'MP - Webhook recebido: subscription.canceled',
                    'status' => 'processing',
                    'created_at' => '2020-01-17 19:47:36',
                    'entity_name' => 'order',
                    'store_id' => '1',
                ),
            2 =>
                array(
                    'entity_id' => '34',
                    'parent_id' => '9',
                    'is_customer_notified' => null,
                    'is_visible_on_front' => '0',
                    'comment' => 'MP - Fatura de assinatura paga.<br> MundipaggId: sub_0BvYQ8qU0uXmMjo9<br>Invoice: in_pmbZV6sDNsk1Z3rY',
                    'status' => 'pending',
                    'created_at' => '2020-01-17 19:44:56',
                    'entity_name' => 'invoice',
                    'store_id' => '1',
                ),
            3 =>
                array(
                    'entity_id' => '33',
                    'parent_id' => '9',
                    'is_customer_notified' => null,
                    'is_visible_on_front' => '0',
                    'comment' => 'MP - Invoice criada: #000000008',
                    'status' => 'pending',
                    'created_at' => '2020-01-17 19:44:56',
                    'entity_name' => 'invoice',
                    'store_id' => '1',
                ),
        ];
    }

    public function setIsCustomerNotified()
    {
        // TODO: Implement setIsCustomerNotified() method.
    }

    public function canInvoice()
    {
        // TODO: Implement canInvoice() method.
    }

    public function canUnhold()
    {
        // TODO: Implement canUnhold() method.
    }

    public function isPaymentReview()
    {
        // TODO: Implement isPaymentReview() method.
    }

    public function isCanceled()
    {
        // TODO: Implement isCanceled() method.
    }

    public function setPlatformOrder($platformOrder)
    {
        // TODO: Implement setPlatformOrder() method.
    }

    public function getPlatformOrder()
    {
        // TODO: Implement getPlatformOrder() method.
    }

    public function getIncrementId()
    {
        return '‌000000082';
    }

    public function payAmount($amount)
    {
        // TODO: Implement payAmount() method.
    }

    public function refundAmount($amountToRefund)
    {
        // TODO: Implement refundAmount() method.
    }

    public function cancelAmount($amountToRefund)
    {
        // TODO: Implement cancelAmount() method.
    }

    public function getGrandTotal()
    {
        // TODO: Implement getGrandTotal() method.
    }

    public function getBaseTaxAmount()
    {
        // TODO: Implement getBaseTaxAmount() method.
    }

    public function getTotalPaid()
    {
        // TODO: Implement getTotalPaid() method.
    }

    public function getTotalDue()
    {
        // TODO: Implement getTotalDue() method.
    }

    public function setTotalPaid($amount)
    {
        // TODO: Implement setTotalPaid() method.
    }

    public function setBaseTotalPaid($amount)
    {
        // TODO: Implement setBaseTotalPaid() method.
    }

    public function setTotalDue($amount)
    {
        // TODO: Implement setTotalDue() method.
    }

    public function setBaseTotalDue($amount)
    {
        // TODO: Implement setBaseTotalDue() method.
    }

    public function setTotalCanceled($amount)
    {
        // TODO: Implement setTotalCanceled() method.
    }

    public function setBaseTotalCanceled($amount)
    {
        // TODO: Implement setBaseTotalCanceled() method.
    }

    public function getTotalRefunded()
    {
        // TODO: Implement getTotalRefunded() method.
    }

    public function setTotalRefunded($amount)
    {
        // TODO: Implement setTotalRefunded() method.
    }

    public function setBaseTotalRefunded($amount)
    {
        // TODO: Implement setBaseTotalRefunded() method.
    }

    public function getCode()
    {
        return '‌000000082';
    }

    public function getData()
    {
        return [
            'entity_id' => '9',
            'state' => 'processing',
            'status' => 'processing',
            'coupon_code' => null,
            'protect_code' => 'c4b3f6f9cc4b72bfb3adb54f9fcd1401',
            'shipping_description' => 'Flat Rate - Fixed',
            'is_virtual' => '0',
            'store_id' => '1',
            'customer_id' => '3',
            'base_discount_amount' => '-120.0000',
            'base_discount_canceled' => null,
            'base_discount_invoiced' => '-120.0000',
            'base_discount_refunded' => null,
            'base_grand_total' => '485.0000',
            'base_shipping_amount' => '5.0000',
            'base_shipping_canceled' => null,
            'base_shipping_invoiced' => '5.0000',
            'base_shipping_refunded' => null,
            'base_shipping_tax_amount' => '0.0000',
            'base_shipping_tax_refunded' => null,
            'base_subtotal' => '600.0000',
            'base_subtotal_canceled' => null,
            'base_subtotal_invoiced' => '600.0000',
            'base_subtotal_refunded' => null,
            'base_tax_amount' => '0.0000',
            'base_tax_canceled' => null,
            'base_tax_invoiced' => '0.0000',
            'base_tax_refunded' => null,
            'base_to_global_rate' => '1.0000',
            'base_to_order_rate' => '1.0000',
            'base_total_canceled' => '0.0000',
            'base_total_invoiced' => '485.0000',
            'base_total_invoiced_cost' => '0.0000',
            'base_total_offline_refunded' => null,
            'base_total_online_refunded' => null,
            'base_total_paid' => '0.0000',
            'base_total_qty_ordered' => null,
            'base_total_refunded' => '0.0000',
            'discount_amount' => '-120.0000',
            'discount_canceled' => null,
            'discount_invoiced' => '-120.0000',
            'discount_refunded' => null,
            'grand_total' => '485.0000',
            'shipping_amount' => '5.0000',
            'shipping_canceled' => null,
            'shipping_invoiced' => '5.0000',
            'shipping_refunded' => null,
            'shipping_tax_amount' => '0.0000',
            'shipping_tax_refunded' => null,
            'store_to_base_rate' => '0.0000',
            'store_to_order_rate' => '0.0000',
            'subtotal' => '600.0000',
            'subtotal_canceled' => null,
            'subtotal_invoiced' => '600.0000',
            'subtotal_refunded' => null,
            'tax_amount' => '0.0000',
            'tax_canceled' => null,
            'tax_invoiced' => '0.0000',
            'tax_refunded' => null,
            'total_canceled' => '0.0000',
            'total_invoiced' => '485.0000',
            'total_offline_refunded' => null,
            'total_online_refunded' => null,
            'total_paid' => '0.0000',
            'total_qty_ordered' => '1.0000',
            'total_refunded' => '0.0000',
            'can_ship_partially' => null,
            'can_ship_partially_item' => null,
            'customer_is_guest' => '0',
            'customer_note_notify' => '1',
            'billing_address_id' => '18',
            'customer_group_id' => '1',
            'edit_increment' => null,
            'email_sent' => '1',
            'send_email' => '1',
            'forced_shipment_with_invoice' => null,
            'payment_auth_expiration' => null,
            'quote_address_id' => null,
            'quote_id' => '13',
            'shipping_address_id' => '17',
            'adjustment_negative' => null,
            'adjustment_positive' => null,
            'base_adjustment_negative' => null,
            'base_adjustment_positive' => null,
            'base_shipping_discount_amount' => '0.0000',
            'base_subtotal_incl_tax' => '600.0000',
            'base_total_due' => '485.0000',
            'payment_authorization_amount' => null,
            'shipping_discount_amount' => '0.0000',
            'subtotal_incl_tax' => '600.0000',
            'total_due' => '485.0000',
            'weight' => '0.0000',
            'customer_dob' => null,
            'increment_id' => '000000010',
            'applied_rule_ids' => '2,3',
            'base_currency_code' => 'BRL',
            'customer_email' => 'wallace.sf87@gmail.com',
            'customer_firstname' => 'teste',
            'customer_lastname' => 'teste sobrenome',
            'customer_middlename' => null,
            'customer_prefix' => null,
            'customer_suffix' => null,
            'customer_taxvat' => '66663447076',
            'discount_description' => null,
            'ext_customer_id' => null,
            'ext_order_id' => null,
            'global_currency_code' => 'BRL',
            'hold_before_state' => null,
            'hold_before_status' => null,
            'order_currency_code' => 'BRL',
            'original_increment_id' => null,
            'relation_child_id' => null,
            'relation_child_real_id' => null,
            'relation_parent_id' => null,
            'relation_parent_real_id' => null,
            'remote_ip' => '172.25.0.1',
            'shipping_method' => 'flatrate_flatrate',
            'store_currency_code' => 'BRL',
            'store_name' => 'Main Website Main Website Store',
            'x_forwarded_for' => null,
            'customer_note' => null,
            'created_at' => '2020-01-17 19:44:53',
            'updated_at' => '2020-01-17 19:48:08',
            'total_item_count' => '1',
            'customer_gender' => null,
            'discount_tax_compensation_amount' => '0.0000',
            'base_discount_tax_compensation_amount' => '0.0000',
            'shipping_discount_tax_compensation_amount' => '0.0000',
            'base_shipping_discount_tax_compensation_amnt' => null,
            'discount_tax_compensation_invoiced' => '0.0000',
            'base_discount_tax_compensation_invoiced' => '0.0000',
            'discount_tax_compensation_refunded' => null,
            'base_discount_tax_compensation_refunded' => null,
            'shipping_incl_tax' => '5.0000',
            'base_shipping_incl_tax' => '5.0000',
            'coupon_rule_name' => null,
            'gift_message_id' => null,
            'paypal_ipn_customer_notified' => '0'
        ];
    }

    /**
     *
     * @return OrderId
     */
    public function getMundipaggId()
    {
        return 'or_ey89e12y8y12e89y';
    }

    /**
     *
     * @return PlatformInvoiceInterface[]
     */
    public function getInvoiceCollection()
    {
        return [];
    }

    public function getTransactionCollection()
    {
        return [
            0 =>
                array(
                    'entity_id' => '9',
                    'parent_id' => '9',
                    'base_shipping_captured' => '5.0000',
                    'shipping_captured' => '5.0000',
                    'amount_refunded' => null,
                    'base_amount_paid' => '485.0000',
                    'amount_canceled' => null,
                    'base_amount_authorized' => null,
                    'base_amount_paid_online' => null,
                    'base_amount_refunded_online' => null,
                    'base_shipping_amount' => '5.0000',
                    'shipping_amount' => '5.0000',
                    'amount_paid' => '485.0000',
                    'amount_authorized' => null,
                    'base_amount_ordered' => '485.0000',
                    'base_shipping_refunded' => null,
                    'shipping_refunded' => null,
                    'base_amount_refunded' => null,
                    'amount_ordered' => '485.0000',
                    'base_amount_canceled' => null,
                    'quote_payment_id' => null,
                    'additional_data' => null,
                    'cc_exp_month' => '1',
                    'cc_ss_start_year' => null,
                    'echeck_bank_name' => null,
                    'method' => 'mundipagg_creditcard',
                    'cc_debug_request_body' => null,
                    'cc_secure_verify' => null,
                    'protection_eligibility' => null,
                    'cc_approval' => null,
                    'cc_last_4' => '0010',
                    'cc_status_description' => null,
                    'echeck_type' => null,
                    'cc_debug_response_serialized' => null,
                    'cc_ss_start_month' => null,
                    'echeck_account_type' => null,
                    'last_trans_id' => null,
                    'cc_cid_status' => null,
                    'cc_owner' => 'fftert',
                    'cc_type' => 'visa',
                    'po_number' => null,
                    'cc_exp_year' => '2021',
                    'cc_status' => null,
                    'echeck_routing_number' => null,
                    'account_status' => null,
                    'anet_trans_method' => null,
                    'cc_debug_response_body' => null,
                    'cc_ss_issue' => null,
                    'echeck_account_name' => null,
                    'cc_avs_status' => null,
                    'cc_number_enc' => null,
                    'cc_trans_id' => null,
                    'address_status' => null,
                    'additional_information' =>
                        array(
                            'cc_saved_card' => '',
                            'cc_type' => 'visa',
                            'cc_last_4' => '0010',
                            'cc_token_credit_card' => 'token_Rnp8NQPfkmFo1OaG',
                            'cc_savecard' => '0',
                            'cc_buyer_checkbox' => '',
                            'cc_installments' => 1,
                            'method_title' => 'MundiPagg Credit Card',
                        ),
                ),
        ];
    }

    public function getPaymentCollection()
    {
        return [];
    }

    /** @return Customer */
    public function getCustomer()
    {
        // TODO: Implement getCustomer() method.
    }

    /** @return Item[] */
    public function getItemCollection()
    {
        // TODO: Implement getItemCollection() method.
    }

    /** @return AbstractPayment[] */
    public function getPaymentMethodCollection()
    {
        // TODO: Implement getPaymentMethodCollection() method.
    }

    /** @return null|Shipping */
    public function getShipping()
    {
        // TODO: Implement getShipping() method.
    }

    /** @since  1.6.5 */
    public function getTotalCanceled()
    {
        // TODO: Implement getTotalCanceled() method.
    }

    /** @since  1.7.2 */
    public function getTotalPaidFromCharges()
    {
        // TODO: Implement getTotalPaidFromCharges() method.
    }

    /** @since 1.11.0 */
    public function getPaymentMethod()
    {
        // TODO: Implement getPaymentMethod() method.
    }
}
