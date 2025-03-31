<?php

declare(strict_types=1);

namespace Amadeco\SmileCustomEntitySeo\Plugin;

use Smile\CustomEntity\Model\CustomEntity;
use Amadeco\SmileCustomEntitySeo\Api\Data\CustomEntitySeoInterface;

/**
 * Plugin for CustomEntity model to add SEO methods.
 */
class CustomEntityPlugin
{
    /**
     * Add SEO interface attributes to interface attributes list.
     *
     * @param CustomEntity $subject Custom entity model
     * @param array $result Current interface attributes
     * @return array Updated interface attributes
     */
    public function afterGetInterfaceAttributes(CustomEntity $subject, array $result): array
    {
        return array_merge($result, [
            CustomEntitySeoInterface::META_TITLE,
            CustomEntitySeoInterface::META_DESCRIPTION,
            CustomEntitySeoInterface::META_KEYWORDS,
            CustomEntitySeoInterface::META_ROBOTS
        ]);
    }
}