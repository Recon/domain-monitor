<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\Domain as ChildDomain;
use Models\DomainQuery as ChildDomainQuery;
use Models\Map\DomainTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'domain' table.
 *
 *
 *
 * @method     ChildDomainQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildDomainQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method     ChildDomainQuery orderByUri($order = Criteria::ASC) Order by the uri column
 * @method     ChildDomainQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method     ChildDomainQuery orderByIsEnabled($order = Criteria::ASC) Order by the is_enabled column
 * @method     ChildDomainQuery orderByLastChecked($order = Criteria::ASC) Order by the last_checked column
 * @method     ChildDomainQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildDomainQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildDomainQuery groupById() Group by the id column
 * @method     ChildDomainQuery groupByAccountId() Group by the account_id column
 * @method     ChildDomainQuery groupByUri() Group by the uri column
 * @method     ChildDomainQuery groupByStatus() Group by the status column
 * @method     ChildDomainQuery groupByIsEnabled() Group by the is_enabled column
 * @method     ChildDomainQuery groupByLastChecked() Group by the last_checked column
 * @method     ChildDomainQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildDomainQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildDomainQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildDomainQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildDomainQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildDomainQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildDomainQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildDomainQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildDomainQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method     ChildDomainQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method     ChildDomainQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method     ChildDomainQuery joinWithAccount($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Account relation
 *
 * @method     ChildDomainQuery leftJoinWithAccount() Adds a LEFT JOIN clause and with to the query using the Account relation
 * @method     ChildDomainQuery rightJoinWithAccount() Adds a RIGHT JOIN clause and with to the query using the Account relation
 * @method     ChildDomainQuery innerJoinWithAccount() Adds a INNER JOIN clause and with to the query using the Account relation
 *
 * @method     ChildDomainQuery leftJoinUsersDomain($relationAlias = null) Adds a LEFT JOIN clause to the query using the UsersDomain relation
 * @method     ChildDomainQuery rightJoinUsersDomain($relationAlias = null) Adds a RIGHT JOIN clause to the query using the UsersDomain relation
 * @method     ChildDomainQuery innerJoinUsersDomain($relationAlias = null) Adds a INNER JOIN clause to the query using the UsersDomain relation
 *
 * @method     ChildDomainQuery joinWithUsersDomain($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the UsersDomain relation
 *
 * @method     ChildDomainQuery leftJoinWithUsersDomain() Adds a LEFT JOIN clause and with to the query using the UsersDomain relation
 * @method     ChildDomainQuery rightJoinWithUsersDomain() Adds a RIGHT JOIN clause and with to the query using the UsersDomain relation
 * @method     ChildDomainQuery innerJoinWithUsersDomain() Adds a INNER JOIN clause and with to the query using the UsersDomain relation
 *
 * @method     ChildDomainQuery leftJoinTest($relationAlias = null) Adds a LEFT JOIN clause to the query using the Test relation
 * @method     ChildDomainQuery rightJoinTest($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Test relation
 * @method     ChildDomainQuery innerJoinTest($relationAlias = null) Adds a INNER JOIN clause to the query using the Test relation
 *
 * @method     ChildDomainQuery joinWithTest($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Test relation
 *
 * @method     ChildDomainQuery leftJoinWithTest() Adds a LEFT JOIN clause and with to the query using the Test relation
 * @method     ChildDomainQuery rightJoinWithTest() Adds a RIGHT JOIN clause and with to the query using the Test relation
 * @method     ChildDomainQuery innerJoinWithTest() Adds a INNER JOIN clause and with to the query using the Test relation
 *
 * @method     \Models\AccountQuery|\Models\UsersDomainQuery|\Models\TestQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildDomain findOne(ConnectionInterface $con = null) Return the first ChildDomain matching the query
 * @method     ChildDomain findOneOrCreate(ConnectionInterface $con = null) Return the first ChildDomain matching the query, or a new ChildDomain object populated from the query conditions when no match is found
 *
 * @method     ChildDomain findOneById(int $id) Return the first ChildDomain filtered by the id column
 * @method     ChildDomain findOneByAccountId(int $account_id) Return the first ChildDomain filtered by the account_id column
 * @method     ChildDomain findOneByUri(string $uri) Return the first ChildDomain filtered by the uri column
 * @method     ChildDomain findOneByStatus(int $status) Return the first ChildDomain filtered by the status column
 * @method     ChildDomain findOneByIsEnabled(boolean $is_enabled) Return the first ChildDomain filtered by the is_enabled column
 * @method     ChildDomain findOneByLastChecked(string $last_checked) Return the first ChildDomain filtered by the last_checked column
 * @method     ChildDomain findOneByCreatedAt(string $created_at) Return the first ChildDomain filtered by the created_at column
 * @method     ChildDomain findOneByUpdatedAt(string $updated_at) Return the first ChildDomain filtered by the updated_at column *
 * @method     ChildDomain requirePk($key, ConnectionInterface $con = null) Return the ChildDomain by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOne(ConnectionInterface $con = null) Return the first ChildDomain matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDomain requireOneById(int $id) Return the first ChildDomain filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByAccountId(int $account_id) Return the first ChildDomain filtered by the account_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByUri(string $uri) Return the first ChildDomain filtered by the uri column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByStatus(int $status) Return the first ChildDomain filtered by the status column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByIsEnabled(boolean $is_enabled) Return the first ChildDomain filtered by the is_enabled column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByLastChecked(string $last_checked) Return the first ChildDomain filtered by the last_checked column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByCreatedAt(string $created_at) Return the first ChildDomain filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildDomain requireOneByUpdatedAt(string $updated_at) Return the first ChildDomain filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildDomain[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildDomain objects based on current ModelCriteria
 * @method     ChildDomain[]|ObjectCollection findById(int $id) Return ChildDomain objects filtered by the id column
 * @method     ChildDomain[]|ObjectCollection findByAccountId(int $account_id) Return ChildDomain objects filtered by the account_id column
 * @method     ChildDomain[]|ObjectCollection findByUri(string $uri) Return ChildDomain objects filtered by the uri column
 * @method     ChildDomain[]|ObjectCollection findByStatus(int $status) Return ChildDomain objects filtered by the status column
 * @method     ChildDomain[]|ObjectCollection findByIsEnabled(boolean $is_enabled) Return ChildDomain objects filtered by the is_enabled column
 * @method     ChildDomain[]|ObjectCollection findByLastChecked(string $last_checked) Return ChildDomain objects filtered by the last_checked column
 * @method     ChildDomain[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildDomain objects filtered by the created_at column
 * @method     ChildDomain[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildDomain objects filtered by the updated_at column
 * @method     ChildDomain[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class DomainQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\DomainQuery object.
     *
     * @param     string $dbName     The database name
     * @param     string $modelName  The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\Domain', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildDomainQuery object.
     *
     * @param     string   $modelAlias The alias of a model in the query
     * @param     Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildDomainQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildDomainQuery) {
            return $criteria;
        }
        $query = new ChildDomainQuery();
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
     * @return ChildDomain|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DomainTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([
                    $key,
                    '__toString',
                ]) ? (string)$key : $key))) && !$this->formatter
        ) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(DomainTableMap::DATABASE_NAME);
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
     * @return ChildDomain A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, account_id, uri, status, is_enabled, last_checked, created_at, updated_at FROM domain WHERE id = :p0';
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
            /** @var ChildDomain $obj */
            $obj = new ChildDomain();
            $obj->hydrate($row);
            DomainTableMap::addInstanceToPool($obj,
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
     * @return ChildDomain|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DomainTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DomainTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DomainTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DomainTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the account_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountId(1234); // WHERE account_id = 1234
     * $query->filterByAccountId(array(12, 34)); // WHERE account_id IN (12, 34)
     * $query->filterByAccountId(array('min' => 12)); // WHERE account_id > 12
     * </code>
     *
     * @see       filterByAccount()
     *
     * @param     mixed  $accountId  The value to use as filter.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(DomainTableMap::COL_ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(DomainTableMap::COL_ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the uri column
     *
     * Example usage:
     * <code>
     * $query->filterByUri('fooValue');   // WHERE uri = 'fooValue'
     * $query->filterByUri('%fooValue%'); // WHERE uri LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uri        The value to use as filter.
     *                               Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByUri($uri = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uri)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $uri)) {
                $uri = str_replace('*', '%', $uri);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_URI, $uri, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status > 12
     * </code>
     *
     * @param     mixed  $status     The value to use as filter.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(DomainTableMap::COL_STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(DomainTableMap::COL_STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_STATUS, $status, $comparison);
    }

    /**
     * Filter the query on the is_enabled column
     *
     * Example usage:
     * <code>
     * $query->filterByIsEnabled(true); // WHERE is_enabled = true
     * $query->filterByIsEnabled('yes'); // WHERE is_enabled = true
     * </code>
     *
     * @param     boolean|string $isEnabled  The value to use as filter.
     *                                       Non-boolean arguments are converted using the following rules:
     *                                       * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                                       * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *                                       Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string         $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByIsEnabled($isEnabled = null, $comparison = null)
    {
        if (is_string($isEnabled)) {
            $isEnabled = in_array(strtolower($isEnabled), ['false', 'off', '-', 'no', 'n', '0', '']) ? false : true;
        }

        return $this->addUsingAlias(DomainTableMap::COL_IS_ENABLED, $isEnabled, $comparison);
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
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByLastChecked($lastChecked = null, $comparison = null)
    {
        if (is_array($lastChecked)) {
            $useMinMax = false;
            if (isset($lastChecked['min'])) {
                $this->addUsingAlias(DomainTableMap::COL_LAST_CHECKED, $lastChecked['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastChecked['max'])) {
                $this->addUsingAlias(DomainTableMap::COL_LAST_CHECKED, $lastChecked['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_LAST_CHECKED, $lastChecked, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed  $createdAt  The value to use as filter.
     *                               Values can be integers (unix timestamps), DateTime objects, or strings.
     *                               Empty strings are treated as NULL.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(DomainTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(DomainTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed  $updatedAt  The value to use as filter.
     *                               Values can be integers (unix timestamps), DateTime objects, or strings.
     *                               Empty strings are treated as NULL.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(DomainTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(DomainTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DomainTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Models\Account object
     *
     * @param \Models\Account|ObjectCollection $account    The related object(s) to use as filter
     * @param string                           $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildDomainQuery The current query, for fluid interface
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof \Models\Account) {
            return $this
                ->addUsingAlias(DomainTableMap::COL_ACCOUNT_ID, $account->getId(), $comparison);
        } elseif ($account instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DomainTableMap::COL_ACCOUNT_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAccount() only accepts arguments of type \Models\Account or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Account relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function joinAccount($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Account');

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
            $this->addJoinObject($join, 'Account');
        }

        return $this;
    }

    /**
     * Use the Account relation Account object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias  optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType       Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinAccount($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Account', '\Models\AccountQuery');
    }

    /**
     * Filter the query by a related \Models\UsersDomain object
     *
     * @param \Models\UsersDomain|ObjectCollection $usersDomain the related object to use as filter
     * @param string                               $comparison  Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDomainQuery The current query, for fluid interface
     */
    public function filterByUsersDomain($usersDomain, $comparison = null)
    {
        if ($usersDomain instanceof \Models\UsersDomain) {
            return $this
                ->addUsingAlias(DomainTableMap::COL_ID, $usersDomain->getDomainId(), $comparison);
        } elseif ($usersDomain instanceof ObjectCollection) {
            return $this
                ->useUsersDomainQuery()
                ->filterByPrimaryKeys($usersDomain->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByUsersDomain() only accepts arguments of type \Models\UsersDomain or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the UsersDomain relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function joinUsersDomain($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('UsersDomain');

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
            $this->addJoinObject($join, 'UsersDomain');
        }

        return $this;
    }

    /**
     * Use the UsersDomain relation UsersDomain object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias  optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType       Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UsersDomainQuery A secondary query class using the current class as primary query
     */
    public function useUsersDomainQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUsersDomain($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'UsersDomain', '\Models\UsersDomainQuery');
    }

    /**
     * Filter the query by a related \Models\Test object
     *
     * @param \Models\Test|ObjectCollection $test       the related object to use as filter
     * @param string                        $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDomainQuery The current query, for fluid interface
     */
    public function filterByTest($test, $comparison = null)
    {
        if ($test instanceof \Models\Test) {
            return $this
                ->addUsingAlias(DomainTableMap::COL_ID, $test->getDomainId(), $comparison);
        } elseif ($test instanceof ObjectCollection) {
            return $this
                ->useTestQuery()
                ->filterByPrimaryKeys($test->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByTest() only accepts arguments of type \Models\Test or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Test relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function joinTest($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Test');

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
            $this->addJoinObject($join, 'Test');
        }

        return $this;
    }

    /**
     * Use the Test relation Test object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias  optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType       Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\TestQuery A secondary query class using the current class as primary query
     */
    public function useTestQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinTest($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Test', '\Models\TestQuery');
    }

    /**
     * Filter the query by a related User object
     * using the users_domain table as cross reference
     *
     * @param User   $user       the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildDomainQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useUsersDomainQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildDomain $domain Object to remove from the list of results
     *
     * @return $this|ChildDomainQuery The current query, for fluid interface
     */
    public function prune($domain = null)
    {
        if ($domain) {
            $this->addUsingAlias(DomainTableMap::COL_ID, $domain->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the domain table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(DomainTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DomainTableMap::clearInstancePool();
            DomainTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(DomainTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(DomainTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            DomainTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            DomainTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildDomainQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(DomainTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60,
            Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildDomainQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(DomainTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildDomainQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(DomainTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildDomainQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(DomainTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildDomainQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(DomainTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60,
            Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildDomainQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(DomainTableMap::COL_CREATED_AT);
    }

} // DomainQuery
