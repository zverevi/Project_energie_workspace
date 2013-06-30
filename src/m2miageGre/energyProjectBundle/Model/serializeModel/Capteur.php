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
 * @Ser\XmlRoot("capteur")
 */
class Capteur {

    /**
     * @var string
     * @Ser\Type("String")
     * @Ser\SerializedName("name")
     */
    protected $name;

    /**
     * @var array<\m2miageGre\energyProjectBundle\Model\serializeModel\Mesure>
     * @Ser\Type("array<m2miageGre\energyProjectBundle\Model\serializeModel\Mesure>")
     * @Ser\SerializedName("mesures")
     */
    protected $mesures;

    /**
     * @param $mesure Mesure
     */
    public function addMesure($mesure)
    {
        $this->mesures[] = $mesure;
    }

    function __construct($mesures, $name)
    {
        $this->mesures = $mesures;
        $this->name = $name;
    }


    /**
     * @param array $mesures
     */
    public function setMesures($mesures)
    {
        $this->mesures = $mesures;
    }

    /**
     * @return array
     */
    public function getMesures()
    {
        return $this->mesures;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }




}