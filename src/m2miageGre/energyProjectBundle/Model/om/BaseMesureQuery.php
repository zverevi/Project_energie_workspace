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
use m2miageGre\energyProjectBundle\Model\Mesure;
use m2miageGre\energyProjectBundle\Model\MesurePeer;
use m2miageGre\energyProjectBundle\Model\MesureQuery;

/**
 * @method MesureQuery orderById($order = Criteria::ASC) Order by the id column
 * @method MesureQuery orderByTimestamp($order = Criteria::ASC) Order by the timestamp column
 * @method MesureQuery orderByState($order = Criteria::ASC) Order by the state column
 * @method MesureQuery orderByEnergy($order = Criteria::ASC) Order by the energy column
 * @method MesureQuery orderByCapteurId($order = Criteria::ASC) Order by the capteur_id column
 *
 * @method MesureQuery groupById() Group by the id column
 * @method MesureQuery groupByTimestamp() Group by the timestamp column
 * @method MesureQuery groupByState() Group by the state column
 * @method MesureQuery groupByEnergy() Group by the energy column
 * @method MesureQuery groupByCapteurId() Group by the capteur_id column
 *
 * @method MesureQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method MesureQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method MesureQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method MesureQuery leftJoinCapteur($relationAlias = null) Adds a LEFT JOIN clause to the query using the Capteur relation
 * @method MesureQuery rightJoinCapteur($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Capteur relation
 * @method MesureQuery innerJoinCapteur($relationAlias = null) Adds a INNER JOIN clause to the query using the Capteur relation
 *
 * @method Mesure findOne(PropelPDO $con = null) Return the first Mesure matching the query
 * @method Mesure findOneOrCreate(PropelPDO $con = null) Return the first Mesure matching the query, or a new Mesure object populated from the query conditions when no match is found
 *
 * @method Mesure findOneByTimestamp(string $timestamp) Return the first Mesure filtered by the timestamp column
 * @method Mesure findOneByState(int $state) Return the first Mesure filtered by the state column
 * @method Mesure findOneByEnergy(int $energy) Return the first Mesure filtered by the energy column
 * @method Mesure findOneByCapteurId(int $capteur_id) Return the first Mesure filtered by the capteur_id column
 *
 * @method array findById(int $id) Return Mesure objects filtered by the id column
 * @method array findByTimestamp(string $timestamp) Return Mesure objects filtered by the timestamp column
 * @method array findByState(int $state) Return Mesure objects filtered by the state column
 * @method array findByEnergy(int $energy) Return Mesure objects filtered by the energy column
 * @method array findByCapteurId(int $capteur_id) Return Mesure objects filtered by the capteur_id column
 */
abstract class BaseMesureQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseMesureQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'm2miageGre\\energyProjectBundle\\Model\\Mesure', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new MesureQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   MesureQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return MesureQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof MesureQuery) {
            return $criteria;
        }
        $query = new MesureQuery();
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
     * @return   Mesure|Mesure[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MesurePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(MesurePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Mesure A model object, or null if the key is not found
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
     * @return                 Mesure A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `timestamp`, `state`, `energy`, `capteur_id` FROM `mesure` WHERE `id` = :p0';
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
            $obj = new Mesure();
            $obj->hydrate($row);
            MesurePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Mesure|Mesure[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Mesure[]|mixed the list of results, formatted by the current formatter
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
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MesurePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MesurePeer::ID, $keys, Criteria::IN);
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
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MesurePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MesurePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MesurePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the timestamp column
     *
     * Example usage:
     * <code>
     * $query->filterByTimestamp('2011-03-14'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp('now'); // WHERE timestamp = '2011-03-14'
     * $query->filterByTimestamp(array('max' => 'yesterday')); // WHERE timestamp > '2011-03-13'
     * </code>
     *
     * @param     mixed $timestamp The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterByTimestamp($timestamp = null, $comparison = null)
    {
        if (is_array($timestamp)) {
            $useMinMax = false;
            if (isset($timestamp['min'])) {
                $this->addUsingAlias(MesurePeer::TIMESTAMP, $timestamp['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($timestamp['max'])) {
                $this->addUsingAlias(MesurePeer::TIMESTAMP, $timestamp['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MesurePeer::TIMESTAMP, $timestamp, $comparison);
    }

    /**
     * Filter the query on the state column
     *
     * Example usage:
     * <code>
     * $query->filterByState(1234); // WHERE state = 1234
     * $query->filterByState(array(12, 34)); // WHERE state IN (12, 34)
     * $query->filterByState(array('min' => 12)); // WHERE state >= 12
     * $query->filterByState(array('max' => 12)); // WHERE state <= 12
     * </code>
     *
     * @param     mixed $state The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterByState($state = null, $comparison = null)
    {
        if (is_array($state)) {
            $useMinMax = false;
            if (isset($state['min'])) {
                $this->addUsingAlias(MesurePeer::STATE, $state['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($state['max'])) {
                $this->addUsingAlias(MesurePeer::STATE, $state['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MesurePeer::STATE, $state, $comparison);
    }

    /**
     * Filter the query on the energy column
     *
     * Example usage:
     * <code>
     * $query->filterByEnergy(1234); // WHERE energy = 1234
     * $query->filterByEnergy(array(12, 34)); // WHERE energy IN (12, 34)
     * $query->filterByEnergy(array('min' => 12)); // WHERE energy >= 12
     * $query->filterByEnergy(array('max' => 12)); // WHERE energy <= 12
     * </code>
     *
     * @param     mixed $energy The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterByEnergy($energy = null, $comparison = null)
    {
        if (is_array($energy)) {
            $useMinMax = false;
            if (isset($energy['min'])) {
                $this->addUsingAlias(MesurePeer::ENERGY, $energy['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($energy['max'])) {
                $this->addUsingAlias(MesurePeer::ENERGY, $energy['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MesurePeer::ENERGY, $energy, $comparison);
    }

    /**
     * Filter the query on the capteur_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCapteurId(1234); // WHERE capteur_id = 1234
     * $query->filterByCapteurId(array(12, 34)); // WHERE capteur_id IN (12, 34)
     * $query->filterByCapteurId(array('min' => 12)); // WHERE capteur_id >= 12
     * $query->filterByCapteurId(array('max' => 12)); // WHERE capteur_id <= 12
     * </code>
     *
     * @see       filterByCapteur()
     *
     * @param     mixed $capteurId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function filterByCapteurId($capteurId = null, $comparison = null)
    {
        if (is_array($capteurId)) {
            $useMinMax = false;
            if (isset($capteurId['min'])) {
                $this->addUsingAlias(MesurePeer::CAPTEUR_ID, $capteurId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($capteurId['max'])) {
                $this->addUsingAlias(MesurePeer::CAPTEUR_ID, $capteurId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MesurePeer::CAPTEUR_ID, $capteurId, $comparison);
    }

    /**
     * Filter the query by a related Capteur object
     *
     * @param   Capteur|PropelObjectCollection $capteur The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MesureQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCapteur($capteur, $comparison = null)
    {
        if ($capteur instanceof Capteur) {
            return $this
                ->addUsingAlias(MesurePeer::CAPTEUR_ID, $capteur->getId(), $comparison);
        } elseif ($capteur instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MesurePeer::CAPTEUR_ID, $capteur->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCapteur() only accepts arguments of type Capteur or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Capteur relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function joinCapteur($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Capteur');

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
            $this->addJoinObject($join, 'Capteur');
        }

        return $this;
    }

    /**
     * Use the Capteur relation Capteur object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \m2miageGre\energyProjectBundle\Model\CapteurQuery A secondary query class using the current class as primary query
     */
    public function useCapteurQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCapteur($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Capteur', '\m2miageGre\energyProjectBundle\Model\CapteurQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Mesure $mesure Object to remove from the list of results
     *
     * @return MesureQuery The current query, for fluid interface
     */
    public function prune($mesure = null)
    {
        if ($mesure) {
            $this->addUsingAlias(MesurePeer::ID, $mesure->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
