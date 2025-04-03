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

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config;
use Smile\CustomEntity\Model\CustomEntity;

/**
 * Apply SEO metadata later in the rendering process.
 */
class ApplySeoMetadata implements ObserverInterface
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
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * Constructor.
     *
     * @param Registry $registry Registry
     * @param Config $pageConfig Page config
     * @param RequestInterface $request Request
     */
    public function __construct(
        Registry $registry,
        Config $pageConfig,
        RequestInterface $request
    ) {
        $this->registry = $registry;
        $this->pageConfig = $pageConfig;
        $this->request = $request;
    }

    /**
     * Apply SEO metadata to the page just before rendering.
     *
     * @param Observer $observer Observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isCustomEntityView()) {
            return;
        }

        /** @var CustomEntity|null $entity */
        $entity = $this->registry->registry('current_custom_entity');

        if (!$entity || !($entity instanceof CustomEntity)) {
            return;
        }

        // Apply meta title only if explicitly set
        $metaTitle = $entity->getMetaTitle();
        if (!empty($metaTitle)) {
            $this->pageConfig->getTitle()->set($metaTitle);
        }

        // Apply meta description only if explicitly set
        $metaDescription = $entity->getMetaDescription();
        if (!empty($metaDescription)) {
            $this->pageConfig->setDescription($metaDescription);
        }

        // Apply meta keywords only if explicitly set
        $metaKeywords = $entity->getMetaKeywords();
        if (!empty($metaKeywords)) {
            $this->pageConfig->setKeywords($metaKeywords);
        }

        // Apply meta robots only if explicitly set
        $metaRobots = $entity->getMetaRobots();
        if (!empty($metaRobots)) {
            $this->pageConfig->setRobots($metaRobots);
        }
    }

    /**
     * Check if current page is a custom entity view.
     *
     * @return bool
     */
    private function isCustomEntityView(): bool
    {
        return $this->request->getFullActionName() === 'smile_custom_entity_entity_view';
    }
}
