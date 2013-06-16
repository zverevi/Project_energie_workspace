<?php

namespace m2miageGre\energyProjectBundle\Model;

use JMS\Serializer\Annotation as Ser;
use m2miageGre\energyProjectBundle\Model\Mesure;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("capteur")
 */
class Capteur {

    /**
     * @var string
     * @Ser\Type("string")
     * @Ser\SerializedName("appliance")
     */
    protected $appliance;

    /**
     * @var array<\m2miageGre\energyProjectBundle\Model\Mesure>
     * @Ser\Type("array<m2miageGre\energyProjectBundle\Model\Mesure>")
     * @Ser\SerializedName("mesures")
     */
    protected $mesures;

    /**
     * @param $mesure Mesure
     */
    public function addMesures($mesure)
    {
        $this->mesures[] = $mesure;
    }
}