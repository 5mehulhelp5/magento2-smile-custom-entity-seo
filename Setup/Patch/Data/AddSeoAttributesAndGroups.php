<?php

declare(strict_types=1);

namespace Amadeco\SmileCustomEntitySeo\Setup\Patch\Data;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;
use Amadeco\SmileCustomEntitySeo\Model\Source\Robots;

/**
 * Add SEO attributes to Custom Entity and creates SEO groups on all attribute sets.
 */
class AddSeoAttributesAndGroups implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var string
     */
    private const ATTRIBUTE_GROUP_SEO_NAME = 'Search Engine Optimization';

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @var Config
     */
    private Config $eavConfig;

    /**
     * Constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup Module data setup
     * @param EavSetupFactory $eavSetupFactory EAV setup factory
     * @param Config $eavConfig EAV config
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Create attribute group if it doesn't exist
        $entityTypeId = $eavSetup->getEntityTypeId(CustomEntityAttributeInterface::ENTITY_TYPE_CODE);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

        // Create SEO group in all attribute sets
        foreach ($attributeSetIds as $attributeSetId) {
            $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, self::ATTRIBUTE_GROUP_SEO_NAME);

            if (!$groupId) {
                $eavSetup->addAttributeGroup(
                    CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
                    $attributeSetId,
                    self::ATTRIBUTE_GROUP_SEO_NAME,
                    100 // Sort order
                );
            }
        }

        // Add meta_title attribute
        $eavSetup->addAttribute(
            CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
            'meta_title',
            [
                'type' => 'varchar',
                'label' => 'Meta Title',
                'input' => 'text',
                'required' => false,
                'sort_order' => 10,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => self::ATTRIBUTE_GROUP_SEO_NAME,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'note' => 'Maximum 255 characters'
            ]
        );

        // Add meta_description attribute
        $eavSetup->addAttribute(
            CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
            'meta_description',
            [
                'type' => 'text',
                'label' => 'Meta Description',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 20,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => self::ATTRIBUTE_GROUP_SEO_NAME
            ]
        );

        // Add meta_keywords attribute
        $eavSetup->addAttribute(
            CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
            'meta_keywords',
            [
                'type' => 'text',
                'label' => 'Meta Keywords',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 30,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => self::ATTRIBUTE_GROUP_SEO_NAME
            ]
        );

        // Add meta_robots attribute
        $eavSetup->addAttribute(
            CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
            'meta_robots',
            [
                'type' => 'varchar',
                'label' => 'Meta Robots',
                'input' => 'select',
                'source' => Robots::class,
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => self::ATTRIBUTE_GROUP_SEO_NAME,
                'default' => ''
            ]
        );

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Remove attributes
        $eavSetup->removeAttribute(CustomEntityAttributeInterface::ENTITY_TYPE_CODE, 'meta_title');
        $eavSetup->removeAttribute(CustomEntityAttributeInterface::ENTITY_TYPE_CODE, 'meta_description');
        $eavSetup->removeAttribute(CustomEntityAttributeInterface::ENTITY_TYPE_CODE, 'meta_keywords');
        $eavSetup->removeAttribute(CustomEntityAttributeInterface::ENTITY_TYPE_CODE, 'meta_robots');

        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}