<?php
/**
 * Amadeco SmileCustomEntitySeo module
 *
 * @category  Amadeco
 * @package   Amadeco_SmileCustomEntitySeo
 * @copyright Ilan Parmentier
 */
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
use Amadeco\SmileCustomEntitySeo\Api\Data\CustomEntitySeoInterface;
use Amadeco\SmileCustomEntitySeo\Model\Source\Robots;

/**
 * Add SEO attributes to Custom Entity and creates SEO groups on all attribute sets.
 */
class AddSeoAttributesAndGroups implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * SEO attribute group name
     */
    public const SEO_GROUP_NAME = 'Search Engine Optimization';

    /**
     * SEO attribute group sort order
     */
    public const SEO_GROUP_SORT_ORDER = 100;

    /**
     * SEO attributes configuration
     */
    private const SEO_ATTRIBUTES = [
        CustomEntitySeoInterface::META_TITLE => [
            'type' => 'varchar',
            'label' => 'Meta Title',
            'input' => 'text',
            'required' => false,
            'sort_order' => 10
        ],
        CustomEntitySeoInterface::META_DESCRIPTION => [
            'type' => 'text',
            'label' => 'Meta Description',
            'input' => 'textarea',
            'required' => false,
            'note' => 'Maximum 255 chars',
            'class' => 'validate-length maximum-length-255',
            'sort_order' => 20
        ],
        CustomEntitySeoInterface::META_KEYWORDS => [
            'type' => 'text',
            'label' => 'Meta Keywords',
            'input' => 'textarea',
            'required' => false,
            'sort_order' => 30
        ],
        CustomEntitySeoInterface::META_ROBOTS => [
            'type' => 'varchar',
            'label' => 'Meta Robots',
            'input' => 'select',
            'source' => Robots::class,
            'required' => false,
            'sort_order' => 40,
            'default' => ''
        ]
    ];

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

        $this->createSeoAttributeGroups($eavSetup);
        $this->createSeoAttributes($eavSetup);

        return $this;
    }

    /**
     * Create SEO attribute groups in all attribute sets
     *
     * @param EavSetup $eavSetup
     * @return void
     */
    private function createSeoAttributeGroups(EavSetup $eavSetup): void
    {
        $entityTypeId = $eavSetup->getEntityTypeId(CustomEntityAttributeInterface::ENTITY_TYPE_CODE);
        $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);

        // Create SEO group in all attribute sets if it doesn't exist
        foreach ($attributeSetIds as $attributeSetId) {
            $groupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, self::SEO_GROUP_NAME);

            if (!$groupId) {
                $eavSetup->addAttributeGroup(
                    CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
                    $attributeSetId,
                    self::SEO_GROUP_NAME,
                    self::SEO_GROUP_SORT_ORDER
                );
            }
        }
    }

    /**
     * Create SEO attributes if they don't exist
     *
     * @param EavSetup $eavSetup
     * @return void
     * @throws LocalizedException
     */
    private function createSeoAttributes(EavSetup $eavSetup): void
    {
        foreach (self::SEO_ATTRIBUTES as $attributeCode => $attributeData) {
            // Check if attribute already exists
            try {
                $attribute = $this->eavConfig->getAttribute(
                    CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
                    $attributeCode
                );

                if ($attribute && $attribute->getId()) {
                    // Attribute already exists, skip creation
                    continue;
                }
            } catch (\Exception $e) {
                // Attribute doesn't exist, we can create it
            }

            // Add common attribute properties
            $attributeData = array_merge($attributeData, [
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => self::SEO_GROUP_NAME
            ]);

            // Create attribute
            $eavSetup->addAttribute(
                CustomEntityAttributeInterface::ENTITY_TYPE_CODE,
                $attributeCode,
                $attributeData
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Remove attributes
        foreach (array_keys(self::SEO_ATTRIBUTES) as $attributeCode) {
            $eavSetup->removeAttribute(CustomEntityAttributeInterface::ENTITY_TYPE_CODE, $attributeCode);
        }

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
