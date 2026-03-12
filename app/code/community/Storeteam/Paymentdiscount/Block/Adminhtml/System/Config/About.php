<?php
class Storeteam_Paymentdiscount_Block_Adminhtml_System_Config_About extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return '<div style="padding:6px 0 2px 0; line-height:1.6;">'
            . '<strong>Storeteam Payment Method Discount</strong><br/>'
            . 'Free Magento 1 extension by <a href="https://storeteam.net" target="_blank">Storeteam</a>.<br/>'
            . 'Apply automatic percentage discounts based on the selected payment method and show the discount in checkout and admin order totals.<br/>'
            . 'Website: <a href="https://storeteam.net" target="_blank">storeteam.net</a><br/>'
            . 'Support: <a href="https://storeteam.net/contact-us" target="_blank">storeteam.net/contact-us</a>'
            . '</div>';
    }
}
