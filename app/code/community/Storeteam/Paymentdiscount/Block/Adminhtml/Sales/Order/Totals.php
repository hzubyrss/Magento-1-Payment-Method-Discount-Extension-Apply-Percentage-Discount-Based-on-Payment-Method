<?php
class Storeteam_Paymentdiscount_Block_Adminhtml_Sales_Order_Totals extends Mage_Adminhtml_Block_Sales_Order_Totals
{
    protected function _initTotals()
    {
        parent::_initTotals();

        $order = $this->getOrder();
        if (!$order) {
            return $this;
        }

        $amount = (float) $order->getData('paymentdiscount_amount');
        if (!$amount) {
            return $this;
        }

        $label = (string) $order->getData('paymentdiscount_label');
        if (!$label) {
            $title = '';
            try {
                if ($order->getPayment() && $order->getPayment()->getMethodInstance()) {
                    $title = $order->getPayment()->getMethodInstance()->getTitle();
                }
            } catch (Exception $e) {
                $title = '';
            }
            $label = Mage::helper('paymentdiscount')->getDiscountLabel($title, (float) $order->getData('paymentdiscount_percent'), $order->getStoreId());
        }

        $total = new Varien_Object(array(
            'code'       => 'paymentdiscount',
            'value'      => $amount,
            'base_value' => $amount,
            'label'      => $label,
        ));

        $this->addTotalBefore($total, 'grand_total');
        return $this;
    }
}
