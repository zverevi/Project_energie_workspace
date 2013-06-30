<?php
/**
 * Created by JetBrains PhpStorm.
 * User: paul
 * Date: 30/06/13
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */

namespace m2miageGre\energyProjectBundle\Model\serializeModel;

use JMS\Serializer\Annotation as Ser;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("day")
 */
class Day {



    /**
     * @param $capteur Capteur
     */
    public function addCapteur($capteur)
    {
        $this->capteurs[] = $capteur;
    }

    function __construct($date, $capteurs = [])
    {
        $this->capteurs = $capteurs;
        $this->date = $date;
    }

    /**
     * @param array $capteurs
     */
    public function setCapteurs($capteurs)
    {
        $this->capteurs = $capteurs;
    }

    /**
     * @return array
     */
    public function getCapteurs()
    {
        return $this->capteurs;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}