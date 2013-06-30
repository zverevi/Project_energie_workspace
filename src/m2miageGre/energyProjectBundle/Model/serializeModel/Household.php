<?php

namespace m2miageGre\energyProjectBundle\Model\serializeModel;

use JMS\Serializer\Annotation as Ser;

/**
 * @Ser\AccessType("public_method")
 * @Ser\XmlRoot("household")
 */
class Household {

    /**
     * @var integer
     * @Ser\Type("Integer")
     * @Ser\SerializedName("id")
     */
    protected $id;

    /**
     * @var array<\m2miageGre\energyProjectBundle\Model\serializeModel\Day>
     * @Ser\Type("array<m2miageGre\energyProjectBundle\Model\serializeModel\Day>")
     * @Ser\SerializedName("days")
     */
    protected $days;

    /**
     * @param $day Day
     */
    public function addDay($day)
    {
        $this->days[] = $day;
    }

    function __construct($days, $id)
    {
        $this->days = $days;
        $this->id = $id;
    }

    /**
     * @param array $days
     */
    public function setDays($days)
    {
        $this->days = $days;
    }

    /**
     * @return array
     */
    public function getDays()
    {
        return $this->days;
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