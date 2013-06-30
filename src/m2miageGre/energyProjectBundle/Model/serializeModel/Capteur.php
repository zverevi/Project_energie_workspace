<?php

namespace m2miageGre\energyProjectBundle\Model\serializeModel;

use JMS\Serializer\Annotation as Ser;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("capteur")
 */
class Capteur {

    /**
     * @var string
     * @Ser\Type("string")
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
     * @var integer
     * @Ser\Type("integer")
     * @Ser\SerializedName("id")
     */
    protected $id;

    /**
     * @var string
     * @Ser\Type("string")
     * @Ser\SerializedName("version")
     */
    protected $version;

    /**
     * @var $prevMesure Mesure
     * @Ser\Type("m2miageGre\energyProjectBundle\Model\serializeModel\Mesure")
     * @Ser\SerializedName("prevMesure")
     */
    protected $prevMesure;

    /**
     * @param $mesure Mesure
     */
    public function addMesure($mesure)
    {
        $this->mesures[] = $mesure;
    }

    function __construct($name, $id, $version, $prevMesure = null, $mesures= [])
    {
        $this->mesures = $mesures;
        $this->name = $name;
        $this->id = $id;
        $this->version = $version;
        $this->prevMesure = $prevMesure;
    }

    /**
     * @param \m2miageGre\energyProjectBundle\Model\serializeModel\Mesure $prevMesure
     */
    public function setPrevMesure($prevMesure)
    {
        $this->prevMesure = $prevMesure;
    }

    /**
     * @return \m2miageGre\energyProjectBundle\Model\serializeModel\Mesure
     */
    public function getPrevMesure()
    {
        return $this->prevMesure;
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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