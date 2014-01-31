<?php
/**
 * MOKADEV
 *
 * @category   Mokadev
 * @package    Mokadev_Popin
 * @author     Mohamed Kaïd <mohamed@mokadev.com>
 */
class Mokadev_Popin_Model_System_Config_Source_Cms_Block
{

    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $collection = Mage::getResourceModel('cms/block_collection')
                ->load();

            $res = array();
            foreach ($collection as $item) {
                $res[] = array(
                    'value' => $item->getData('block_id'),
                    'label' => $item->getData('title'),
                );
            }
            $this->_options = $res;
        }
        return $this->_options;
    }

}
