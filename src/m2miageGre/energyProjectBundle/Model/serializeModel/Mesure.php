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

    /**
     * @var integer
     * @Ser\Type("integer")
     * @Ser\SerializedName("state")
     */
    public $state;

    /**
     * @var integer
     * @Ser\Type("integer")
     * @Ser\SerializedName("energy")
     */
    public $energy;

    /**
     * @var \DateTime
     * @Ser\Type("DateTime<'Y-m-d H:i:s', 'Europe/Paris'>")
     * @Ser\SerializedName("timestamp")
     */
    public $timestamp;

    function __construct($energy, $state, $timestamp)
    {
        $this->energy = $energy;
        $this->state = $state;
        $this->timestamp = $timestamp;
    }

    /**
     * @param int $energy
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;
    }

    /**
     * @return int
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}