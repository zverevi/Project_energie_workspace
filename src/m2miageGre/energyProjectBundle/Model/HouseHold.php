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

    function __construct($household)
    {
        $this->household = $household;
    }

    /**
     * @param $capteur Capteur
     */
    public function addCapteurs($capteur)
    {
        $this->capteurs[] = $capteur;
    }

}