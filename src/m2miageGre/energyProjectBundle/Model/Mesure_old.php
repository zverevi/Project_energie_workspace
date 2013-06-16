<?php

namespace m2miageGre\energyProjectBundle\Model;

use JMS\Serializer\Annotation as Ser;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("mesure")
 */
class Mesure_old {

    /**
     * @var \DateTime
     * @Ser\Type("DateTime<'Y-m-d H:i:s', 'Europe/Paris'>")
     */
    protected $date;

    /**
     * @var integer
     * @Ser\Type("integer")
     */
    protected $energy;

    /**
     * @var string
     * @Ser\Type("integer")
     */
    protected $state;

    function __construct($date, $energy, $state)
    {
        $this->date = $date;
        $this->energy = $energy;
        $this->state = $state;
    }


}