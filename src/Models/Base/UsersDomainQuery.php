<?php

namespace Models\Base;

use \Exception;
use \PDO;
use Models\UsersDomain as ChildUsersDomain;
use Models\UsersDomainQuery as ChildUsersDomainQuery;
use Models\Map\UsersDomainTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'users_domain' table.
 *
 *
 *
 * @method     ChildUsersDomainQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildUsersDomainQuery orderByDomainId($order = Criteria::ASC) Order by the domain_id column
 *
 * @method     ChildUsersDomainQuery groupByUserId() Group by the user_id column
 * @method     ChildUsersDomainQuery groupByDomainId() Group by the domain_id column
 *
 * @method     ChildUsersDomainQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildUsersDomainQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildUsersDomainQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildUsersDomainQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildUsersDomainQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildUsersDomainQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildUsersDomainQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildUsersDomainQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildUsersDomainQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildUsersDomainQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildUsersDomainQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildUsersDomainQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildUsersDomainQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildUsersDomainQuery leftJoinDomain($relationAlias = null) Adds a LEFT JOIN clause to the query using the Domain relation
 * @method     ChildUsersDomainQuery rightJoinDomain($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Domain relation
 * @method     ChildUsersDomainQuery innerJoinDomain($relationAlias = null) Adds a INNER JOIN clause to the query using the Domain relation
 *
 * @method     ChildUsersDomainQuery joinWithDomain($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Domain relation
 *
 * @method     ChildUsersDomainQuery leftJoinWithDomain() Adds a LEFT JOIN clause and with to the query using the Domain relation
 * @method     ChildUsersDomainQuery rightJoinWithDomain() Adds a RIGHT JOIN clause and with to the query using the Domain relation
 * @method     ChildUsersDomainQuery innerJoinWithDomain() Adds a INNER JOIN clause and with to the query using the Domain relation
 *
 * @method     \Models\UserQuery|\Models\DomainQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildUsersDomain findOne(ConnectionInterface $con = null) Return the first ChildUsersDomain matching the query
 * @method     ChildUsersDomain findOneOrCreate(ConnectionInterface $con = null) Return the first ChildUsersDomain matching the query, or a new ChildUsersDomain object populated from the query conditions when no match is found
 *
 * @method     ChildUsersDomain findOneByUserId(int $user_id) Return the first ChildUsersDomain filtered by the user_id column
 * @method     ChildUsersDomain findOneByDomainId(int $domain_id) Return the first ChildUsersDomain filtered by the domain_id column *
 * @method     ChildUsersDomain requirePk($key, ConnectionInterface $con = null) Return the ChildUsersDomain by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersDomain requireOne(ConnectionInterface $con = null) Return the first ChildUsersDomain matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsersDomain requireOneByUserId(int $user_id) Return the first ChildUsersDomain filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildUsersDomain requireOneByDomainId(int $domain_id) Return the first ChildUsersDomain filtered by the domain_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildUsersDomain[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildUsersDomain objects based on current ModelCriteria
 * @method     ChildUsersDomain[]|ObjectCollection findByUserId(int $user_id) Return ChildUsersDomain objects filtered by the user_id column
 * @method     ChildUsersDomain[]|ObjectCollection findByDomainId(int $domain_id) Return ChildUsersDomain objects filtered by the domain_id column
 * @method     ChildUsersDomain[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class UsersDomainQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \Models\Base\UsersDomainQuery object.
     *
     * @param     string $dbName     The database name
     * @param     string $modelName  The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\Models\\UsersDomain', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildUsersDomainQuery object.
     *
     * @param     string   $modelAlias The alias of a model in the query
     * @param     Criteria $criteria   Optional Criteria to build the query from
     *
     * @return ChildUsersDomainQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildUsersDomainQuery) {
            return $criteria;
        }
        $query = new ChildUsersDomainQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param                     array [$user_id, $domain_id] $key Primary key to use for the query
     * @param ConnectionInterface $con  an optional connection object
     *
     * @return ChildUsersDomain|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = UsersDomainTableMap::getInstanceFromPool(serialize([
                    (null === $key[0] || is_scalar($key[0]) || is_callable([
                        $key[0],
                        '__toString',
                    ]) ? (string)$key[0] : $key[0]),
                    (null === $key[1] || is_scalar($key[1]) || is_callable([
                        $key[1],
                        '__toString',
                    ]) ? (string)$key[1] : $key[1]),
                ])))) && !$this->formatter
        ) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(UsersDomainTableMap::DATABASE_NAME);
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
     * @return ChildUsersDomain A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT user_id, domain_id FROM users_domain WHERE user_id = :p0 AND domain_id = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildUsersDomain $obj */
            $obj = new ChildUsersDomain();
            $obj->hydrate($row);
            UsersDomainTableMap::addInstanceToPool($obj, serialize([
                (null === $key[0] || is_scalar($key[0]) || is_callable([
                    $key[0],
                    '__toString',
                ]) ? (string)$key[0] : $key[0]),
                (null === $key[1] || is_scalar($key[1]) || is_callable([
                    $key[1],
                    '__toString',
                ]) ? (string)$key[1] : $key[1]),
            ]));
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
     * @return ChildUsersDomain|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(UsersDomainTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(UsersDomainTableMap::COL_DOMAIN_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(UsersDomainTableMap::COL_USER_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(UsersDomainTableMap::COL_DOMAIN_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed  $userId     The value to use as filter.
     *                               Use scalar values for equality.
     *                               Use array values for in_array() equivalent.
     *                               Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(UsersDomainTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(UsersDomainTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersDomainTableMap::COL_USER_ID, $userId, $comparison);
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
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
     */
    public function filterByDomainId($domainId = null, $comparison = null)
    {
        if (is_array($domainId)) {
            $useMinMax = false;
            if (isset($domainId['min'])) {
                $this->addUsingAlias(UsersDomainTableMap::COL_DOMAIN_ID, $domainId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($domainId['max'])) {
                $this->addUsingAlias(UsersDomainTableMap::COL_DOMAIN_ID, $domainId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(UsersDomainTableMap::COL_DOMAIN_ID, $domainId, $comparison);
    }

    /**
     * Filter the query by a related \Models\User object
     *
     * @param \Models\User|ObjectCollection $user       The related object(s) to use as filter
     * @param string                        $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUsersDomainQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \Models\User) {
            return $this
                ->addUsingAlias(UsersDomainTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UsersDomainTableMap::COL_USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType      Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias  optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType       Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\Models\UserQuery');
    }

    /**
     * Filter the query by a related \Models\Domain object
     *
     * @param \Models\Domain|ObjectCollection $domain     The related object(s) to use as filter
     * @param string                          $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildUsersDomainQuery The current query, for fluid interface
     */
    public function filterByDomain($domain, $comparison = null)
    {
        if ($domain instanceof \Models\Domain) {
            return $this
                ->addUsingAlias(UsersDomainTableMap::COL_DOMAIN_ID, $domain->getId(), $comparison);
        } elseif ($domain instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(UsersDomainTableMap::COL_DOMAIN_ID, $domain->toKeyValue('PrimaryKey', 'Id'),
                    $comparison);
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
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildUsersDomain $usersDomain Object to remove from the list of results
     *
     * @return $this|ChildUsersDomainQuery The current query, for fluid interface
     */
    public function prune($usersDomain = null)
    {
        if ($usersDomain) {
            $this->addCond('pruneCond0', $this->getAliasedColName(UsersDomainTableMap::COL_USER_ID),
                $usersDomain->getUserId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(UsersDomainTableMap::COL_DOMAIN_ID),
                $usersDomain->getDomainId(), Criteria::NOT_EQUAL);
            $this->combine(['pruneCond0', 'pruneCond1'], Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the users_domain table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(UsersDomainTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            UsersDomainTableMap::clearInstancePool();
            UsersDomainTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(UsersDomainTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(UsersDomainTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            UsersDomainTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            UsersDomainTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // UsersDomainQuery
