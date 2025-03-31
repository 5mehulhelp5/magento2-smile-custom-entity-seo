<?php
/**
 * Amadeco SmileCustomEntitySeo module
 *
 * @category  Amadeco
 * @package   Amadeco_SmileCustomEntitySeo
 * @copyright Ilan Parmentier
 */
declare(strict_types=1);

namespace Amadeco\SmileCustomEntitySeo\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Amadeco\SmileCustomEntitySeo\Api\Data\CustomEntitySeoInterface;

/**
 * Apply SEO metadata to custom entity pages.
 */
class ApplyEntitySeoMetadata implements ObserverInterface
{
    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var Config
     */
    private Config $pageConfig;

    /**
     * Constructor.
     *
     * @param Registry $registry Registry
     * @param Config $pageConfig Page config
     */
    public function __construct(
        Registry $registry,
        Config $pageConfig
    ) {
        $this->registry = $registry;
        $this->pageConfig = $pageConfig;
    }

    /**
     * Apply SEO metadata to the page when a custom entity is loaded.
     *
     * @param Observer $observer Observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        /** @var CustomEntityInterface|null $entity */
        $entity = $this->registry->registry('current_custom_entity');

        if (!$entity || !$entity instanceof CustomEntitySeoInterface) {
            return;
        }

        // Apply Meta Title
        if ($entity->getMetaTitle()) {
            $this->pageConfig->getTitle()->set($entity->getMetaTitle());
        }

        // Apply Meta Description
        if ($entity->getMetaDescription()) {
            $this->pageConfig->setDescription($entity->getMetaDescription());
        }

        // Apply Meta Keywords
        if ($entity->getMetaKeywords()) {
            $this->pageConfig->setKeywords($entity->getMetaKeywords());
        }

        // Apply Meta Robots only if explicitly set
        if ($entity->getMetaRobots() && $entity->getMetaRobots() !== '') {
            $this->pageConfig->setRobots($entity->getMetaRobots());
        }
    }
}