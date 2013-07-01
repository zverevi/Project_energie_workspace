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
     * @var array<integer>
     * @Ser\Type("array<integer>")
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
        $midnight = date_create_from_format("Y-m-d H:i:s", $mesure->getTimestamp()->format("Y-m-d")." 00:00:00");
        $interval = $midnight->diff($mesure->getTimestamp());
        $index = (intval(($interval->format("%i"))) + (60 * intval($interval->format("%H"))))/10;
        $this->mesures[$index] = $mesure->getEnergy();
    }

    public function fillGap()
    {
        if ($this->version == "v2") {
            $this->fillGapV2();
        }
        if ($this->version == "v3" || $this->version == "v4") {
            $this->fillGapV34();
        }
    }

    public function fillGapV2()
    {
        $mesures = $this->mesures;
        for ($i = 0; $i < 144; $i++) {
            if ( !isset($mesures[$i])) {
                $mesures[$i] = 0;
            }
        }
        $finalMesures = [];
        for ($j=0; $j < 144; $j++) {
            $finalMesures[] = $mesures[$j];
        }
        $this->mesures = $finalMesures;
    }

    public function fillGapV34()
    {
        $mesures = $this->mesures;
        $prevEnergy = $this->prevMesure->getEnergy();
        for ($i = 0; $i < 144; $i++) {
            if ( !isset($mesures[$i])) {
                $mesures[$i] = $prevEnergy;
            } else {
                $prevEnergy = $mesures[$i];
            }
        }
        $finalMesures = [];
        for ($j=0; $j < 144; $j++) {
            $finalMesures[] = $mesures[$j];
        }
        $this->mesures = $finalMesures;

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