<?php
class Storeteam_Paymentdiscount_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag('paymentdiscount/general/enabled', $store);
    }

    public function getDefaultLabel($store = null)
    {
        $label = Mage::getStoreConfig('paymentdiscount/general/discount_label', $store);
        return $label ? $label : $this->__('Payment Discount');
    }

    public function getMethodDiscounts($store = null)
    {
        $raw = Mage::getStoreConfig('paymentdiscount/methods/all_methods', $store);
        if (is_array($raw)) {
            return $raw;
        }
        if (!is_string($raw) || $raw === '') {
            return array();
        }
        $data = @unserialize($raw);
        return is_array($data) ? $data : array();
    }

    public function getDiscountPercent($methodCode, $store = null)
    {
        if (!$methodCode) {
            return 0;
        }
        $discounts = $this->getMethodDiscounts($store);
        if (!isset($discounts[$methodCode])) {
            return 0;
        }
        $percent = (float) $discounts[$methodCode];
        if ($percent < 0) {
            $percent = 0;
        }
        if ($percent > 100) {
            $percent = 100;
        }
        return $percent;
    }

    public function getDiscountLabel($title, $percent = 0, $store = null)
    {
        $baseTitle = $title ? $title : $this->getDefaultLabel($store);
        $label = trim($baseTitle) . ' ' . $this->__('Discount');
        $percent = (float) $percent;
        if ($percent > 0) {
            $label .= ' (' . round($percent, 2) . '%)';
        }
        return $label;
    }
}
