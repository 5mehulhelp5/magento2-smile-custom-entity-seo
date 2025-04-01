<?php
/**
 * Amadeco SmileCustomEntitySeo module
 *
 * @category  Amadeco
 * @package   Amadeco_SmileCustomEntitySeo
 * @copyright Ilan Parmentier
 */
declare(strict_types=1);

namespace Amadeco\SmileCustomEntitySeo\Plugin\Controller\Entity;

use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Result\Page;
use Smile\CustomEntity\Controller\Entity\View;
use Amadeco\SmileCustomEntitySeo\Api\Data\CustomEntitySeoInterface;

/**
 * Plugin for Custom Entity View controller to apply SEO metadata.
 */
class ViewPlugin
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
     * After execute plugin to apply SEO metadata to the page.
     *
     * @param View $subject The controller
     * @param ResultInterface $result The result
     * @return ResultInterface
     */
    public function afterExecute(View $subject, ResultInterface $result): ResultInterface
    {
        if ($result instanceof Page) {
            $this->applySeoMetadata($result);
        }

        return $result;
    }

    /**
     * Apply SEO metadata to the page.
     *
     * @param Page $page The page
     * @return void
     */
    private function applySeoMetadata(Page $page): void
    {
        $entity = $this->registry->registry('current_custom_entity');
        
        if (!$entity || !$entity instanceof CustomEntitySeoInterface) {
            return;
        }

        $pageConfig = $page->getConfig();

        // Apply Meta Title
        if ($entity->getMetaTitle()) {
            $pageConfig->getTitle()->set($entity->getMetaTitle());
        }

        // Apply Meta Description
        if ($entity->getMetaDescription()) {
            $pageConfig->setDescription($entity->getMetaDescription());
        }

        // Apply Meta Keywords
        if ($entity->getMetaKeywords()) {
            $pageConfig->setKeywords($entity->getMetaKeywords());
        }

        // Apply Meta Robots only if explicitly set
        if ($entity->getMetaRobots() && $entity->getMetaRobots() !== '') {
            $pageConfig->setRobots($entity->getMetaRobots());
        }
    }
}
