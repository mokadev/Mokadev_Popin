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
            $existingIdentifiers = array();
            foreach ($collection as $item) {
                $identifier = $item->getData('identifier');

                $data['value'] = $identifier;
                $data['label'] = $item->getData('title');

                if (in_array($identifier, $existingIdentifiers)) {
                    $data['value'] .= '|' . $item->getData('page_id');
                } else {
                    $existingIdentifiers[] = $identifier;
                }

                $res[] = $data;
            }
            $this->_options = $res;
        }
        return $this->_options;
    }

}
