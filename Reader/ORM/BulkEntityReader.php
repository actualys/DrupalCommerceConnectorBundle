<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM;

use Pim\Bundle\BaseConnectorBundle\Reader\ORM\EntityReader;

/**
 * Reads all entities at once
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class BulkEntityReader extends EntityReader
{
    /**
     * @return array|mixed|null
     */
    public function read()
    {
        $entities = [];

        while ($entity = parent::read()) {
            $entities[] = $entity;
        }

        return empty($entities) ? null : $entities;
    }

    /**
     * @return array
     */
    public function getConfigurationFields()
    {
        return array();
    }
}
