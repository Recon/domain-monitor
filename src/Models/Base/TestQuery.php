<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Test as ChildTest;
use Models\TestQuery as ChildTestQuery;
use Models\Map\TestTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'test' table.
 *
 *
 *
 * @method     ChildTestQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildTestQuery orderByDomainId($order = Criteria::ASC) Order by the domain_id column
 * @method     ChildTestQuery orderByTestType($order = Criteria::ASC) Order by the test_type column
 * @method     ChildTestQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildTestQuery orderByLastChecked($order = Criteria::ASC) Order by the last_checked column
 *
 * @method     ChildTestQuery groupById() Group by the id column
 * @method     ChildTestQuery groupByDomainId() Group by the domain_id column
 * @method     ChildTestQuery groupByTestType() Group by the test_type column
 * @method     ChildTestQuery groupByStatus() Group by the status column
 * @method     ChildTestQuery groupByLastChecked() Group by the last_checked column
 *
 * @method     ChildTestQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildTestQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildTestQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildTestQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildTestQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildTestQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildTestQuery leftJoinDomain($relationAlias = null) Adds a LEFT JOIN clause to the query using the Domain relation
 * @method     ChildTestQuery rightJoinDomain($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Domain relation
 * @method     ChildTestQuery innerJoinDomain($relationAlias = null) Adds a INNER JOIN clause to the query using the Domain relation
 *
 * @method     ChildTestQuery joinWithDomain($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Domain relation
 *
 * @method     ChildTestQuery leftJoinWithDomain() Adds a LEFT JOIN clause and with to the query using the Domain relation
 * @method     ChildTestQuery rightJoinWithDomain() Adds a RIGHT JOIN clause and with to the query using the Domain relation
 * @method     ChildTestQuery innerJoinWithDomain() Adds a INNER JOIN clause and with to the query using the Domain relation
 *
 * @method     ChildTestQuery leftJoinStatusChange($relationAlias = null) Adds a LEFT JOIN clause to the query using the StatusChange relation
 * @method     ChildTestQuery rightJoinStatusChange($relationAlias = null) Adds a RIGHT JOIN clause to the query using the StatusChange relation
 * @method     ChildTestQuery innerJoinStatusChange($relationAlias = null) Adds a INNER JOIN clause to the query using the StatusChange relation
 *
 * @method     ChildTestQuery joinWithStatusChange($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the StatusChange relation
 *
 * @method     ChildTestQuery leftJoinWithStatusChange() Adds a LEFT JOIN clause and with to the query using the StatusChange relation
 * @method     ChildTestQuery rightJoinWithStatusChange() Adds a RIGHT JOIN clause and with to the query using the StatusChange relation
 * @method     ChildTestQuery innerJoinWithStatusChange() Adds a INNER JOIN clause and with to the query using the StatusChange relation
 *
 * @method     \Models\DomainQuery|\Models\StatusChangeQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildTest findOne(ConnectionInterface $con = null) Return the first ChildTest matching the query
 * @method     ChildTest findOneOrCreate(ConnectionInterface $con = null) Return the first ChildTest matching the query, or a new ChildTest object populated from the query conditions when no match is found
 *
 * @method     ChildTest findOneById(int $id) Return the first ChildTest filtered by the id column
 * @method     ChildTest findOneByDomainId(int $domain_id) Return the first ChildTest filtered by the domain_id column
 * @method     ChildTest findOneByTestType(int $test_type) Return the first ChildTest filtered by the test_type column
 * @method     ChildTest findOneByStatus(boolean $status) Return the first ChildTest filtered by the status column
 * @method     ChildTest findOneByLastChecked(string $last_checked) Return the first ChildTest filtered by the last_checked column *
 * @method     ChildTest requirePk($key, ConnectionInterface $con = null) Return the ChildTest by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTest requireOne(ConnectionInterface $con = null) Return the first ChildTest matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTest requireOneById(int $id) Return the first ChildTest filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTest requireOneByDomainId(int $domain_id) Return the first ChildTest filtered by the domain_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTest requireOneByTestType(int $test_type) Return the first ChildTest filtered by the test_type column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTest requireOneByStatus(boolean $status) Return the first ChildTest filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildTest requireOneByLastChecked(string $last_checked) Return the first ChildTest filtered by the last_checked column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildTest[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildTest objects based on current ModelCriteria
 * @method     ChildTest[]|ObjectCollection findById(int $id) Return ChildTest objects filtered by the id column
 * @method     ChildTest[]|ObjectCollection findByDomainId(int $domain_id) Return ChildTest objects filtered by the domain_id column
 * @method     ChildTest[]|ObjectCollection findByTestType(int $test_type) Return ChildTest objects filtered by the test_type column
 * @method     ChildTest[]|ObjectCollection findByStatus(boolean $status) Return ChildTest objects filtered by the status column
 * @method     ChildTest[]|ObjectCollection findByLastChecked(string $last_checked) Return ChildTest objects filtered by the last_checked column
 * @method     ChildTest[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class TestQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\TestQuery object.
     *
     * @param     string $dbName     The database name
     * @param     string $modelName  The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Test', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildTestQuery object.
     *
     * @param     string   $modelAlias The alias of a model in the query
     * @param     Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildTestQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildTestQuery) {
            return $criteria;
        }
        $query = new ChildTestQuery();
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
     * @param mixed               $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildTest|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TestTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([
                    $key,
                    '__toString',
                ]) ? (string)$key : $key))) && !$this->formatter
        ) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TestTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed               $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTest A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, domain_id, test_type, status, last_checked FROM test WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildTest $obj */
            $obj = new ChildTest();
            $obj->hydrate($row);
            TestTableMap::addInstanceToPool($obj,
                null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string)$key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed               $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildTest|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     *
     * @param     array               $keys Primary keys to use for the query
     * @param     ConnectionInterface $con  an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TestTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TestTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed  $id         The value to use as filter.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TestTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TestTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TestTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the domain_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDomainId(1234); // WHERE domain_id = 1234
     * $query->filterByDomainId(array(12, 34)); // WHERE domain_id IN (12, 34)
     * $query->filterByDomainId(array('min' => 12)); // WHERE domain_id > 12
     * </code>
     *
     * @see       filterByDomain()
     *
     * @param     mixed  $domainId   The value to use as filter.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterByDomainId($domainId = null, $comparison = null)
    {
        if (is_array($domainId)) {
            $useMinMax = false;
            if (isset($domainId['min'])) {
                $this->addUsingAlias(TestTableMap::COL_DOMAIN_ID, $domainId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($domainId['max'])) {
                $this->addUsingAlias(TestTableMap::COL_DOMAIN_ID, $domainId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TestTableMap::COL_DOMAIN_ID, $domainId, $comparison);
    }

    /**
     * Filter the query on the test_type column
     *
     * Example usage:
     * <code>
     * $query->filterByTestType(1234); // WHERE test_type = 1234
     * $query->filterByTestType(array(12, 34)); // WHERE test_type IN (12, 34)
     * $query->filterByTestType(array('min' => 12)); // WHERE test_type > 12
     * </code>
     *
     * @param     mixed  $testType   The value to use as filter.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterByTestType($testType = null, $comparison = null)
    {
        if (is_array($testType)) {
            $useMinMax = false;
            if (isset($testType['min'])) {
                $this->addUsingAlias(TestTableMap::COL_TEST_TYPE, $testType['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($testType['max'])) {
                $this->addUsingAlias(TestTableMap::COL_TEST_TYPE, $testType['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TestTableMap::COL_TEST_TYPE, $testType, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(true); // WHERE status = true
     * $query->filterByStatus('yes'); // WHERE status = true
     * </code>
     *
     * @param     boolean|string $status     The value to use as filter.
     *                                       Non-boolean arguments are converted using the following rules:
     *                                       * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                                       * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *                                       Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string         $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_string($status)) {
            $status = in_array(strtolower($status), ['false', 'off', '-', 'no', 'n', '0', '']) ? false : true;
        }

        return $this->addUsingAlias(TestTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the last_checked column
     *
     * Example usage:
     * <code>
     * $query->filterByLastChecked('2011-03-14'); // WHERE last_checked = '2011-03-14'
     * $query->filterByLastChecked('now'); // WHERE last_checked = '2011-03-14'
     * $query->filterByLastChecked(array('max' => 'yesterday')); // WHERE last_checked > '2011-03-13'
     * </code>
     *
     * @param     mixed  $lastChecked The value to use as filter.
     *                                Values can be integers (unix timestamps), DateTime objects, or strings.
     *                                Empty strings are treated as NULL.
     *                                Use scalar values for equality.
     *                                Use array values for in_array() equivalent.
     *                                Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function filterByLastChecked($lastChecked = null, $comparison = null)
    {
        if (is_array($lastChecked)) {
            $useMinMax = false;
            if (isset($lastChecked['min'])) {
                $this->addUsingAlias(TestTableMap::COL_LAST_CHECKED, $lastChecked['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastChecked['max'])) {
                $this->addUsingAlias(TestTableMap::COL_LAST_CHECKED, $lastChecked['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TestTableMap::COL_LAST_CHECKED, $lastChecked, $comparison);
    }

    /**
     * Filter the query by a related \Models\Domain object
     *
     * @param \Models\Domain|ObjectCollection $domain     The related object(s) to use as filter
     * @param string                          $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildTestQuery The current query, for fluid interface
     */
    public function filterByDomain($domain, $comparison = null)
    {
        if ($domain instanceof \Models\Domain) {
            return $this
                ->addUsingAlias(TestTableMap::COL_DOMAIN_ID, $domain->getId(), $comparison);
        } elseif ($domain instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(TestTableMap::COL_DOMAIN_ID, $domain->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDomain() only accepts arguments of type \Models\Domain or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Domain relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function joinDomain($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Domain');

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
            $this->addJoinObject($join, 'Domain');
        }

        return $this;
    }

    /**
     * Use the Domain relation Domain object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias  optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType       Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\DomainQuery A secondary query class using the current class as primary query
     */
    public function useDomainQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinDomain($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Domain', '\Models\DomainQuery');
    }

    /**
     * Filter the query by a related \Models\StatusChange object
     *
     * @param \Models\StatusChange|ObjectCollection $statusChange the related object to use as filter
     * @param string                                $comparison   Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildTestQuery The current query, for fluid interface
     */
    public function filterByStatusChange($statusChange, $comparison = null)
    {
        if ($statusChange instanceof \Models\StatusChange) {
            return $this
                ->addUsingAlias(TestTableMap::COL_ID, $statusChange->getTestId(), $comparison);
        } elseif ($statusChange instanceof ObjectCollection) {
            return $this
                ->useStatusChangeQuery()
                ->filterByPrimaryKeys($statusChange->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByStatusChange() only accepts arguments of type \Models\StatusChange or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the StatusChange relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function joinStatusChange($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('StatusChange');

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
            $this->addJoinObject($join, 'StatusChange');
        }

        return $this;
    }

    /**
     * Use the StatusChange relation StatusChange object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias  optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType       Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\StatusChangeQuery A secondary query class using the current class as primary query
     */
    public function useStatusChangeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinStatusChange($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'StatusChange', '\Models\StatusChangeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildTest $test Object to remove from the list of results
     *
     * @return $this|ChildTestQuery The current query, for fluid interface
     */
    public function prune($test = null)
    {
        if ($test) {
            $this->addUsingAlias(TestTableMap::COL_ID, $test->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the test table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TestTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TestTableMap::clearInstancePool();
            TestTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                                 if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                                 rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(TestTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(TestTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            TestTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            TestTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // TestQuery
