<?php
$installer = $this;
$installer->startSetup();

$tables = array(
    $installer->getTable('sales/quote') => array(
        'paymentdiscount_amount'      => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'base_paymentdiscount_amount' => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'paymentdiscount_percent'     => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'paymentdiscount_label'       => "VARCHAR(255) NULL"
    ),
    $installer->getTable('sales/quote_address') => array(
        'paymentdiscount_amount'      => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'base_paymentdiscount_amount' => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'"
    ),
    $installer->getTable('sales/order') => array(
        'paymentdiscount_amount'      => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'base_paymentdiscount_amount' => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'paymentdiscount_percent'     => "DECIMAL(12,4) NOT NULL DEFAULT '0.0000'",
        'paymentdiscount_label'       => "VARCHAR(255) NULL"
    )
);

$connection = $installer->getConnection();
foreach ($tables as $tableName => $columns) {
    foreach ($columns as $columnName => $definition) {
        if (!$connection->tableColumnExists($tableName, $columnName)) {
            $connection->addColumn($tableName, $columnName, $definition);
        }
    }
}

$installer->endSetup();
