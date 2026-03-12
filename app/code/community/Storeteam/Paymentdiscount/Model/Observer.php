<?php
class Storeteam_Paymentdiscount_Model_Observer
{
    public function collectBeforeTotals(Varien_Event_Observer $observer)
    {
        $quote = $observer->getQuote();
        if (!$quote) {
            return $this;
        }

        $helper = Mage::helper('paymentdiscount');
        $quote->setData('paymentdiscount_amount', 0);
        $quote->setData('paymentdiscount_percent', 0);
        $quote->setData('paymentdiscount_label', '');

        if (!$helper->isEnabled($quote->getStoreId())) {
            return $this;
        }

        $payment = $quote->getPayment();
        if (!$payment) {
            return $this;
        }

        $methodCode = $payment->getMethod();
        if (!$methodCode) {
            return $this;
        }

        $percent = $helper->getDiscountPercent($methodCode, $quote->getStoreId());
        if ($percent <= 0) {
            return $this;
        }

        $title = '';
        try {
            if ($payment->getMethodInstance()) {
                $title = $payment->getMethodInstance()->getTitle();
            }
        } catch (Exception $e) {
            $title = '';
        }

        $quote->setData('paymentdiscount_percent', $percent);
        $quote->setData('paymentdiscount_label', $helper->getDiscountLabel($title, $percent, $quote->getStoreId()));
        return $this;
    }

    public function quoteToOrder(Varien_Event_Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();
        $order = $observer->getEvent()->getOrder();
        if (!$quote || !$order) {
            return $this;
        }

        $amount = (float) $quote->getData('paymentdiscount_amount');
        $percent = (float) $quote->getData('paymentdiscount_percent');
        $label = (string) $quote->getData('paymentdiscount_label');

        $order->setData('paymentdiscount_amount', $amount);
        $order->setData('base_paymentdiscount_amount', $amount);
        $order->setData('paymentdiscount_percent', $percent);
        $order->setData('paymentdiscount_label', $label);

        return $this;
    }
}
