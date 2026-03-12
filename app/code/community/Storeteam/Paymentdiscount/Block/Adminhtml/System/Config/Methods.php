<?php
class Storeteam_Paymentdiscount_Block_Adminhtml_System_Config_Methods extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $saved = Mage::getStoreConfig('paymentdiscount/methods/all_methods');
        if (is_string($saved) && strlen($saved)) {
            $saved = @unserialize($saved);
        }
        if (!is_array($saved)) {
            $saved = array();
        }

        $methods = Mage::getModel('payment/config')->getAllMethods();

        $html = '<table class="form-list" cellspacing="0">';
        $html .= '<tr><th align="left" style="padding:2px 10px 6px 0;">Payment Method</th><th align="left" style="padding:2px 10px 6px 0;">Discount (%)</th></tr>';

        foreach ($methods as $code => $method) {
            $title = Mage::getStoreConfig('payment/' . $code . '/title');
            if (!$title && is_object($method)) {
                try {
                    $title = $method->getTitle();
                } catch (Exception $e) {
                    $title = '';
                }
            }
            if (!$title) {
                $title = $code;
            }

            $value = isset($saved[$code]) ? $saved[$code] : '';

            $html .= '<tr>';
            $html .= '<td style="padding:2px 10px 2px 0;">' . $this->escapeHtml($title) . '</td>';
            $html .= '<td style="padding:2px 10px 2px 0;">';
            $html .= '<input type="text" name="' . $element->getName() . '[' . $this->escapeHtml($code) . ']" value="' . $this->escapeHtml($value) . '" style="width:70px;" />';
            $html .= ' % <small>(' . $this->escapeHtml($code) . ')</small>';
            $html .= '</td></tr>';
        }

        $html .= '</table>';
        return $html;
    }
}
