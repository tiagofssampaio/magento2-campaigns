<?php

namespace TiagoSampaio\Campaigns\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{

    const IS_ACTIVE = 1;

    const NOT_ATIVE = 0;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = [
            self::IS_ACTIVE => __('Yes'),
            self::NOT_ATIVE => __('No')
        ];

        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
