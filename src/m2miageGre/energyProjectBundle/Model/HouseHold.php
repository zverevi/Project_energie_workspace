<?php

namespace m2miageGre\energyProjectBundle\Model;

use JMS\Serializer\Annotation as Ser;
use m2miageGre\energyProjectBundle\Model\Capteur;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("household")
 */
class HouseHold {

    /**
     * @var integer
     * @Ser\Type("integer")
     * @Ser\SerializedName("houseold")
     */
    protected $household;

    /**
     * @var array<\m2miageGre\energyProjectBundle\Model\Capteur>
     * @Ser\Type("array<m2miageGre\energyProjectBundle\Model\Capteur>")
     * @Ser\SerializedName("capteurs")
     */
    protected $capteurs;

    /**
     * @param $capteur Capteur
     */
    public function addCapteurs($capteur)
    {
        $this->capteurs[] = $capteur;
    }

}