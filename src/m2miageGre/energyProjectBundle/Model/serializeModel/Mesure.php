<?php
/**
 * Created by JetBrains PhpStorm.
 * User: paul
 * Date: 30/06/13
 * Time: 16:38
 * To change this template use File | Settings | File Templates.
 */

namespace m2miageGre\energyProjectBundle\Model\serializeModel;

use JMS\Serializer\Annotation as Ser;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("mesure")
 */
class Mesure {

    public $state;
    public $energy;
    public $timestamp;
}