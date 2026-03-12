<?php
class Storeteam_Paymentdiscount_Model_Total extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
    public function __construct()
    {
        $this->setCode('paymentdiscount');
    }

    public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);

        $quote = $address->getQuote();
        $helper = Mage::helper('paymentdiscount');

        $address->setData('paymentdiscount_amount', 0);
        $address->setData('base_paymentdiscount_amount', 0);

        if (!$quote || !$helper->isEnabled($quote->getStoreId())) {
            return $this;
        }

        if (!$address->getAllItems()) {
            return $this;
        }

        $percent = (float) $quote->getData('paymentdiscount_percent');
        if ($percent <= 0) {
            $quote->setData('paymentdiscount_amount', 0);
            return $this;
        }

        $subtotal = (float) $address->getSubtotal();
        if ($subtotal <= 0) {
            $quote->setData('paymentdiscount_amount', 0);
            return $this;
        }

        $discountAmount = round(($subtotal * $percent) / 100, 4);
        if ($discountAmount <= 0) {
            $quote->setData('paymentdiscount_amount', 0);
            return $this;
        }

        $negative = -1 * $discountAmount;
        $address->setData('paymentdiscount_amount', $negative);
        $address->setData('base_paymentdiscount_amount', $negative);

        $quote->setData('paymentdiscount_amount', $negative);
        $quote->setData('base_paymentdiscount_amount', $negative);

        $address->setGrandTotal($address->getGrandTotal() + $negative);
        $address->setBaseGrandTotal($address->getBaseGrandTotal() + $negative);

        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = (float) $address->getData('paymentdiscount_amount');
        if (!$amount) {
            return $this;
        }

        $quote = $address->getQuote();
        $payment = $quote ? $quote->getPayment() : null;
        $title = '';
        if ($payment) {
            try {
                if ($payment->getMethodInstance()) {
                    $title = $payment->getMethodInstance()->getTitle();
                }
            } catch (Exception $e) {
                $title = '';
            }
        }

        $percent = (float) $quote->getData('paymentdiscount_percent');
        $label = Mage::helper('paymentdiscount')->getDiscountLabel($title, $percent, $quote ? $quote->getStoreId() : null);

        $address->addTotal(array(
            'code'  => $this->getCode(),
            'title' => $label,
            'value' => $amount
        ));

        return $this;
    }
}
