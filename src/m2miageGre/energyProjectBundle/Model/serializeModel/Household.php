<?php

namespace m2miageGre\energyProjectBundle\Model\serializeModel;

use JMS\Serializer\Annotation as Ser;
use m2miageGre\energyProjectBundle\Model\Capteur as propelCapteur;
use m2miageGre\energyProjectBundle\Model\Mesure as propelMesure;
use m2miageGre\energyProjectBundle\Model\serializeModel\Capteur;
use m2miageGre\energyProjectBundle\Model\serializeModel\Mesure;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("household")
 */
class Household {

    /**
     * @var string
     * @Ser\Type("string")
     * @Ser\SerializedName("id")
     */
    protected $id;

    /**
     * @var \DateTime
     * @Ser\Type("DateTime<'Y-m-d', 'Europe/Paris'>")
     * @Ser\SerializedName("date")
     */
    protected $date;

    /**
     * @var array<\m2miageGre\energyProjectBundle\Model\serializeModel\Capteur>
     * @Ser\Type("array<m2miageGre\energyProjectBundle\Model\serializeModel\Capteur>")
     * @Ser\SerializedName("capteurs")
     */
    protected $capteurs;

    /**
     * @param $capteur Capteur
     */
    public function addCapteur($capteur)
    {
        $this->capteurs[] = $capteur;
    }

    /**
     * @param $mesure propelMesure
     */
    public function addMesure($mesure)
    {
        $capteur = $this->getCapteur($mesure->getCapteurId());
        $capteur->addMesure(new Mesure(
            $mesure->getEnergy(),
            $mesure->getState(),
            $mesure->getTimestamp()
        ));
    }

    public function fillGap()
    {
        foreach ($this->capteurs as $capteur) {
            $capteur->fillGap();
        }
    }

    /**
     * @param $id
     * @return Capteur
     */
    public function getCapteur($id)
    {
        $targetCapteur = null;
        /** @var $capteur propelCapteur */
        foreach($this->capteurs as $capteur) {
            if ($id == $capteur->getId()) {
                $targetCapteur = $capteur;
                break;
            }
        }

        return $targetCapteur;
    }

    function __construct($date, $id, $capteurs = [])
    {
        $this->capteurs = $capteurs;
        $this->date = $date;
        $this->id = $id;
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
}