<?php

namespace MW\CustomCurrency\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Index extends Action{

    protected $attributeOptionManagement;

    protected $eavAttributeFactory;

    protected $stores;

    protected $eavConfig;
	
	protected $optionLabelFactory;

	protected $optionFactory;
	
    public function __construct(
        \Magento\Eav\Api\AttributeOptionManagementInterface $attributeOptionManagement,
        \Magento\Eav\Model\Entity\AttributeFactory $eavAttributeFactory,
        \Magento\Store\Api\StoreRepositoryInterface $repository,
        \Magento\Eav\Model\Config $eavConfig,
		\Magento\Eav\Api\Data\AttributeOptionLabelInterfaceFactory $optionLabelFactory,
		\Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionFactory,
        Context $context
    )
    {
		$this->optionFactory = $optionFactory;
		$this->optionLabelFactory = $optionLabelFactory;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->eavAttributeFactory = $eavAttributeFactory;
        $this->stores = $repository;
        $this->eavConfig = $eavConfig;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

			$oldAttribute = 'newtestmachine2';
            $newAttribute = 'newmachine2';
            $attributeOld = $this->eavConfig->getAttribute('aitoc_checkout', $oldAttribute, 1);
            $optionsOld = $attributeOld->getSource()->getAllOptions();

            $magentoAttribute = $this->eavAttributeFactory->create()->loadByCode('aitoc_checkout', $newAttribute)->setStoreId(2);

            $attributeCode = $magentoAttribute->getAttributeCode();
            $magentoAttributeOptions = $this->attributeOptionManagement->getItems(
                'aitoc_checkout',
                $attributeCode
            );

            $existingMagentoAttributeOptions = [];
            $newOptions = [];
            $counter = 0;

            foreach ($magentoAttributeOptions as $option) {
                if (!$option->getValue()) {
                    continue;
                }
                if ($option->getLabel() instanceof \Magento\Framework\Phrase) {
                    $label = $option->getText();
                } else {
                    $label = $option->getLabel();
                }

                if ($label == '') {
                    continue;
                }

                $existingMagentoAttributeOptions[] = $label;
                $newOptions['value'][$option->getValue()] = [$label, $label];
                $counter++;
            }

            foreach ($optionsOld as $option) {
                if ($option['label'] == '') {
                    continue;
                }

try {

              // If no, add it.

              /** @var \Magento\Eav\Model\Entity\Attribute\OptionLabel $optionLabel */
              $optionLabel = $this->optionLabelFactory->create();
              $optionLabel->setStoreId(2);
              $optionLabel->setLabel($option['label']);

              $option1 = $this->optionFactory->create();
              $option1->setLabel($optionLabel->getLabel());
              // $option->setLabel($label);
              $option1->setStoreLabels([$optionLabel]);
              $option1->setSortOrder(0);
              $option1->setIsDefault(false);

              $this->attributeOptionManagement->add(
                  \Magento\Catalog\Model\Product::ENTITY,
                  $magentoAttribute->getId(),
                  $option1
              );

        } catch (\Exception $e) {
          throw new \Exception($e->getMessage());
        }



                if (!in_array($option['label'], $existingMagentoAttributeOptions)) {
                    $newOptions['value']['option_' . $counter] = [$option['label'], $option['label']];
                }

                $counter++;
            }
            if (count($newOptions)) {
                $magentoAttribute->setOption($newOptions)->save();
            }

    }
}