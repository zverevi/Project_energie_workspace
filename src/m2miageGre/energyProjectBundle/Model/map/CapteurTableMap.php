<?php

namespace m2miageGre\energyProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'capteur' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.m2miageGre.energyProjectBundle.Model.map
 */
class CapteurTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.m2miageGre.energyProjectBundle.Model.map.CapteurTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('capteur');
        $this->setPhpName('Capteur');
        $this->setClassname('m2miageGre\\energyProjectBundle\\Model\\Capteur');
        $this->setPackage('src.m2miageGre.energyProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('capteur_name', 'CapteurName', 'VARCHAR', true, 128, null);
        $this->addColumn('version', 'Version', 'VARCHAR', true, 255, null);
        $this->addForeignKey('household_id', 'HouseholdId', 'INTEGER', 'household', 'id', true, null, null);
        // validators
        $this->addValidator('id', 'unique', 'propel.validator.UniqueValidator', '', 'Capteur already exists !');
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('HouseHold', 'm2miageGre\\energyProjectBundle\\Model\\HouseHold', RelationMap::MANY_TO_ONE, array('household_id' => 'id', ), null, null);
        $this->addRelation('Mesure', 'm2miageGre\\energyProjectBundle\\Model\\Mesure', RelationMap::ONE_TO_MANY, array('id' => 'capteur_id', ), null, null, 'Mesures');
    } // buildRelations()

} // CapteurTableMap
