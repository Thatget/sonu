<?php
/**
 * @author Aitoc Team
 * @copyright Copyright (c) 2020 Aitoc (https://www.aitoc.com)
 * @package Aitoc_CheckoutFieldsManager
 */

/**
 * Copyright © 2018 Aitoc. All rights reserved.
 */

namespace Aitoc\CheckoutFieldsManager\Model\ResourceModel;

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class Attribute extends \Magento\Eav\Model\ResourceModel\Attribute
{
    /**
     * Get Form attribute table
     *
     * Get table, where dependency between form name and attribute ids is stored
     *
     * @return string|null
     */
    protected function _getFormAttributeTable()
    {
        return $this->getTable('eav_form_attribute');
    }

    /**
     * Retrieve select object for load object data.
     *
     * Overridden to fix issue (exception).
     * @see https://github.com/magento/magento2/issues/6043
     * @see https://github.com/magento/magento2/pull/6044
     *
     * todo: add current version check when would be fixed in core
     *
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = $this->__getLoadSelect($field, $value, $object);
        $websiteId = (int)$object->getWebsite()->getId();

        if ($websiteId) {
            $connection = $this->getConnection();
            $columns = [];
            $scopeTable = $this->_getEavWebsiteTable();
            if ($scopeTable) {
                $describe = $connection->describeTable($scopeTable);
                unset($describe['attribute_id']);
                foreach (array_keys($describe) as $columnName) {
                    $columns['scope_' . $columnName] = $columnName;
                }
                $conditionSql = $connection->quoteInto(
                    $this->getMainTable() . '.attribute_id = scope_table.attribute_id AND scope_table.website_id =?',
                    $websiteId
                );
                $select->joinLeft(['scope_table' => $scopeTable], $conditionSql, $columns);
            }
        }

        return $select;
    }

    /**
     * Retrieve select object for load object data.
     *
     * Parent of parent (\Magento\Framework\Model\ResourceModel\Db\AbstractDb) implementation.
     * @see \Magento\Framework\Model\ResourceModel\Db\AbstractDb::_getLoadSelect
     *
     * @param string $field
     * @param mixed $value
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return \Magento\Framework\DB\Select
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function __getLoadSelect($field, $value, $object)
    {
        $field = $this->getConnection()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), $field));
        $select = $this->getConnection()->select()->from($this->getMainTable())->where($field . '=?', $value);
        return $select;
    }

    /**
     * Get EAV website table
     *
     * Get table, where website-dependent attribute parameters are stored
     * If realization doesn't demand this functionality, let this function just return null
     *
     * @return string|null
     */
    protected function _getEavWebsiteTable()
    {
        return null;
    }
}
