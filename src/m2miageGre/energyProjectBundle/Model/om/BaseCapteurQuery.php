<?php

namespace m2miageGre\energyProjectBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use m2miageGre\energyProjectBundle\Model\Capteur;
use m2miageGre\energyProjectBundle\Model\CapteurPeer;
use m2miageGre\energyProjectBundle\Model\CapteurQuery;
use m2miageGre\energyProjectBundle\Model\HouseHold;
use m2miageGre\energyProjectBundle\Model\Mesure;

/**
 * @method CapteurQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CapteurQuery orderByCapteurName($order = Criteria::ASC) Order by the capteur_name column
 * @method CapteurQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method CapteurQuery orderByHouseholdId($order = Criteria::ASC) Order by the household_id column
 *
 * @method CapteurQuery groupById() Group by the id column
 * @method CapteurQuery groupByCapteurName() Group by the capteur_name column
 * @method CapteurQuery groupByVersion() Group by the version column
 * @method CapteurQuery groupByHouseholdId() Group by the household_id column
 *
 * @method CapteurQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CapteurQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CapteurQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CapteurQuery leftJoinHouseHold($relationAlias = null) Adds a LEFT JOIN clause to the query using the HouseHold relation
 * @method CapteurQuery rightJoinHouseHold($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HouseHold relation
 * @method CapteurQuery innerJoinHouseHold($relationAlias = null) Adds a INNER JOIN clause to the query using the HouseHold relation
 *
 * @method CapteurQuery leftJoinMesure($relationAlias = null) Adds a LEFT JOIN clause to the query using the Mesure relation
 * @method CapteurQuery rightJoinMesure($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Mesure relation
 * @method CapteurQuery innerJoinMesure($relationAlias = null) Adds a INNER JOIN clause to the query using the Mesure relation
 *
 * @method Capteur findOne(PropelPDO $con = null) Return the first Capteur matching the query
 * @method Capteur findOneOrCreate(PropelPDO $con = null) Return the first Capteur matching the query, or a new Capteur object populated from the query conditions when no match is found
 *
 * @method Capteur findOneByCapteurName(string $capteur_name) Return the first Capteur filtered by the capteur_name column
 * @method Capteur findOneByVersion(string $version) Return the first Capteur filtered by the version column
 * @method Capteur findOneByHouseholdId(int $household_id) Return the first Capteur filtered by the household_id column
 *
 * @method array findById(int $id) Return Capteur objects filtered by the id column
 * @method array findByCapteurName(string $capteur_name) Return Capteur objects filtered by the capteur_name column
 * @method array findByVersion(string $version) Return Capteur objects filtered by the version column
 * @method array findByHouseholdId(int $household_id) Return Capteur objects filtered by the household_id column
 */
abstract class BaseCapteurQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCapteurQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'm2miageGre\\energyProjectBundle\\Model\\Capteur', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CapteurQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CapteurQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CapteurQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CapteurQuery) {
            return $criteria;
        }
        $query = new CapteurQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   Capteur|Capteur[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CapteurPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CapteurPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Capteur A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 Capteur A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `capteur_name`, `version`, `household_id` FROM `capteur` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new Capteur();
            $obj->hydrate($row);
            CapteurPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return Capteur|Capteur[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|Capteur[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CapteurPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CapteurPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CapteurPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CapteurPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CapteurPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the capteur_name column
     *
     * Example usage:
     * <code>
     * $query->filterByCapteurName('fooValue');   // WHERE capteur_name = 'fooValue'
     * $query->filterByCapteurName('%fooValue%'); // WHERE capteur_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $capteurName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function filterByCapteurName($capteurName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($capteurName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $capteurName)) {
                $capteurName = str_replace('*', '%', $capteurName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CapteurPeer::CAPTEUR_NAME, $capteurName, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion('fooValue');   // WHERE version = 'fooValue'
     * $query->filterByVersion('%fooValue%'); // WHERE version LIKE '%fooValue%'
     * </code>
     *
     * @param     string $version The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($version)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $version)) {
                $version = str_replace('*', '%', $version);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CapteurPeer::VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the household_id column
     *
     * Example usage:
     * <code>
     * $query->filterByHouseholdId(1234); // WHERE household_id = 1234
     * $query->filterByHouseholdId(array(12, 34)); // WHERE household_id IN (12, 34)
     * $query->filterByHouseholdId(array('min' => 12)); // WHERE household_id >= 12
     * $query->filterByHouseholdId(array('max' => 12)); // WHERE household_id <= 12
     * </code>
     *
     * @see       filterByHouseHold()
     *
     * @param     mixed $householdId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function filterByHouseholdId($householdId = null, $comparison = null)
    {
        if (is_array($householdId)) {
            $useMinMax = false;
            if (isset($householdId['min'])) {
                $this->addUsingAlias(CapteurPeer::HOUSEHOLD_ID, $householdId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($householdId['max'])) {
                $this->addUsingAlias(CapteurPeer::HOUSEHOLD_ID, $householdId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CapteurPeer::HOUSEHOLD_ID, $householdId, $comparison);
    }

    /**
     * Filter the query by a related HouseHold object
     *
     * @param   HouseHold|PropelObjectCollection $houseHold The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CapteurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByHouseHold($houseHold, $comparison = null)
    {
        if ($houseHold instanceof HouseHold) {
            return $this
                ->addUsingAlias(CapteurPeer::HOUSEHOLD_ID, $houseHold->getId(), $comparison);
        } elseif ($houseHold instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CapteurPeer::HOUSEHOLD_ID, $houseHold->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByHouseHold() only accepts arguments of type HouseHold or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HouseHold relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function joinHouseHold($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HouseHold');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'HouseHold');
        }

        return $this;
    }

    /**
     * Use the HouseHold relation HouseHold object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \m2miageGre\energyProjectBundle\Model\HouseHoldQuery A secondary query class using the current class as primary query
     */
    public function useHouseHoldQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinHouseHold($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HouseHold', '\m2miageGre\energyProjectBundle\Model\HouseHoldQuery');
    }

    /**
     * Filter the query by a related Mesure object
     *
     * @param   Mesure|PropelObjectCollection $mesure  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CapteurQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMesure($mesure, $comparison = null)
    {
        if ($mesure instanceof Mesure) {
            return $this
                ->addUsingAlias(CapteurPeer::ID, $mesure->getCapteurId(), $comparison);
        } elseif ($mesure instanceof PropelObjectCollection) {
            return $this
                ->useMesureQuery()
                ->filterByPrimaryKeys($mesure->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByMesure() only accepts arguments of type Mesure or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Mesure relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function joinMesure($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Mesure');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Mesure');
        }

        return $this;
    }

    /**
     * Use the Mesure relation Mesure object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \m2miageGre\energyProjectBundle\Model\MesureQuery A secondary query class using the current class as primary query
     */
    public function useMesureQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinMesure($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Mesure', '\m2miageGre\energyProjectBundle\Model\MesureQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Capteur $capteur Object to remove from the list of results
     *
     * @return CapteurQuery The current query, for fluid interface
     */
    public function prune($capteur = null)
    {
        if ($capteur) {
            $this->addUsingAlias(CapteurPeer::ID, $capteur->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
