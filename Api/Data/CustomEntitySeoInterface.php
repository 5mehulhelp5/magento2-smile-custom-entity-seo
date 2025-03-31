<?php
/**
 * Amadeco SmileCustomEntitySeo module
 *
 * @category  Amadeco
 * @package   Amadeco_SmileCustomEntitySeo
 * @copyright Ilan Parmentier
 */
declare(strict_types=1);

namespace Amadeco\SmileCustomEntitySeo\Api\Data;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom Entity SEO Interface.
 */
interface CustomEntitySeoInterface extends CustomEntityInterface
{
    /**
     * Constants for keys of data array
     */
    public const META_TITLE = 'meta_title';
    public const META_DESCRIPTION = 'meta_description';
    public const META_KEYWORDS = 'meta_keywords';
    public const META_ROBOTS = 'meta_robots';

    /**
     * Get meta title.
     *
     * @return string|null
     */
    public function getMetaTitle(): ?string;

    /**
     * Set meta title.
     *
     * @param string $metaTitle Meta title
     * @return $this
     */
    public function setMetaTitle(string $metaTitle): self;

    /**
     * Get meta description.
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * Set meta description.
     *
     * @param string $metaDescription Meta description
     * @return $this
     */
    public function setMetaDescription(string $metaDescription): self;

    /**
     * Get meta keywords.
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * Set meta keywords.
     *
     * @param string $metaKeywords Meta keywords
     * @return $this
     */
    public function setMetaKeywords(string $metaKeywords): self;

    /**
     * Get meta robots.
     *
     * @return string|null
     */
    public function getMetaRobots(): ?string;

    /**
     * Set meta robots.
     *
     * @param string $metaRobots Meta robots
     * @return $this
     */
    public function setMetaRobots(string $metaRobots): self;
}