<?php
class Storeteam_Paymentdiscount_Model_System_Config_Backend_Methods extends Mage_Core_Model_Config_Data
{
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if (!is_array($value)) {
            $value = array();
        }

        $clean = array();
        foreach ($value as $code => $percent) {
            $percent = trim((string) $percent);
            if ($percent === '') {
                continue;
            }
            $percent = (float) $percent;
            if ($percent < 0) {
                $percent = 0;
            } elseif ($percent > 100) {
                $percent = 100;
            }
            $clean[$code] = $percent;
        }

        $this->setValue(serialize($clean));
        return parent::_beforeSave();
    }

    protected function _afterLoad()
    {
        $value = $this->getValue();
        if (is_string($value) && strlen($value)) {
            $unserialized = @unserialize($value);
            if (is_array($unserialized)) {
                $this->setValue($unserialized);
            }
        }
        return parent::_afterLoad();
    }
}
