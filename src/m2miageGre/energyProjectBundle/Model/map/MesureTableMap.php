<?php

namespace m2miageGre\energyProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'mesure' table.
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
class MesureTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.m2miageGre.energyProjectBundle.Model.map.MesureTableMap';

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
        $this->setName('mesure');
        $this->setPhpName('Mesure');
        $this->setClassname('m2miageGre\\energyProjectBundle\\Model\\Mesure');
        $this->setPackage('src.m2miageGre.energyProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('timestamp', 'Timestamp', 'TIMESTAMP', true, null, null);
        $this->addColumn('state', 'State', 'INTEGER', true, null, null);
        $this->addColumn('energy', 'Energy', 'INTEGER', true, null, null);
        $this->addForeignKey('capteur_id', 'CapteurId', 'INTEGER', 'capteur', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Capteur', 'm2miageGre\\energyProjectBundle\\Model\\Capteur', RelationMap::MANY_TO_ONE, array('capteur_id' => 'id', ), null, null);
    } // buildRelations()

} // MesureTableMap
