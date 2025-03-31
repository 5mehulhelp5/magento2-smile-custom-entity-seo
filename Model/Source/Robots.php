<?php
/**
 * Amadeco SmileCustomEntitySeo module
 *
 * @category  Amadeco
 * @package   Amadeco_SmileCustomEntitySeo
 * @copyright Ilan Parmentier
 */
declare(strict_types=1);

namespace Amadeco\SmileCustomEntitySeo\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Source model for meta robots options.
 */
class Robots extends AbstractSource
{
    /**
     * Possible robots values.
     *
     * @var array
     */
    protected static array $options = [
        '' => 'Default',
        'INDEX,FOLLOW' => 'INDEX, FOLLOW',
        'NOINDEX,FOLLOW' => 'NOINDEX, FOLLOW',
        'INDEX,NOFOLLOW' => 'INDEX, NOFOLLOW',
        'NOINDEX,NOFOLLOW' => 'NOINDEX, NOFOLLOW'
    ];

    /**
     * @inheritdoc
     */
    public function getAllOptions(): array
    {
        $options = [];
        foreach (self::$options as $value => $label) {
            $options[] = ['value' => $value, 'label' => __($label)];
        }

        return $options;
    }
}