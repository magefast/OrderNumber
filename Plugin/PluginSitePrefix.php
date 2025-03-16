<?php

namespace Magefast\OrderNumber\Plugin;

use Magento\Framework\App\ResourceConnection;
use Magento\SalesSequence\Model\ResourceModel\Profile;

class PluginSitePrefix
{
    public const GLOBAL_ORDER_PREFIX = '7';

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param Profile $subject
     * @param $result
     * @param $metadataId
     * @return mixed
     */
    public function afterLoadActiveProfile(Profile $subject, $result, $metadataId)
    {
        $type = $this->getEntityTypeMeta($metadataId);

        if ($type != 'order') {
            return $result;
        }

        $type = self::GLOBAL_ORDER_PREFIX;
        return $result->setData('prefix', $type);
    }

    /**
     * @param $metadataId
     * @return string
     */
    private function getEntityTypeMeta($metadataId)
    {
        $connection = $this->resource->getConnection();
        $bind = ['meta_id' => $metadataId];
        $select = $connection->select()
            ->from('sales_sequence_meta', ['entity_type'])
            ->where('meta_id = :meta_id');

        $metaId = $connection->fetchOne($select, $bind);
        return $metaId;
    }
}
