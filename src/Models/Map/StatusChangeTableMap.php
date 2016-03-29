<?php

namespace Models\Map;

use Models\StatusChange;
use Models\StatusChangeQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'status_change_log' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class StatusChangeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Models.Map.StatusChangeTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'status_change_log';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Models\\StatusChange';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Models.StatusChange';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 5;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 5;

    /**
     * the column name for the id field
     */
    const COL_ID = 'status_change_log.id';

    /**
     * the column name for the test_id field
     */
    const COL_TEST_ID = 'status_change_log.test_id';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'status_change_log.date';

    /**
     * the column name for the old_status field
     */
    const COL_OLD_STATUS = 'status_change_log.old_status';

    /**
     * the column name for the new_status field
     */
    const COL_NEW_STATUS = 'status_change_log.new_status';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = [
        self::TYPE_PHPNAME   => ['Id', 'TestId', 'Date', 'OldStatus', 'NewStatus',],
        self::TYPE_CAMELNAME => ['id', 'testId', 'date', 'oldStatus', 'newStatus',],
        self::TYPE_COLNAME   => [
            StatusChangeTableMap::COL_ID,
            StatusChangeTableMap::COL_TEST_ID,
            StatusChangeTableMap::COL_DATE,
            StatusChangeTableMap::COL_OLD_STATUS,
            StatusChangeTableMap::COL_NEW_STATUS,
        ],
        self::TYPE_FIELDNAME => ['id', 'test_id', 'date', 'old_status', 'new_status',],
        self::TYPE_NUM       => [0, 1, 2, 3, 4,],
    ];

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = [
        self::TYPE_PHPNAME   => ['Id' => 0, 'TestId' => 1, 'Date' => 2, 'OldStatus' => 3, 'NewStatus' => 4,],
        self::TYPE_CAMELNAME => ['id' => 0, 'testId' => 1, 'date' => 2, 'oldStatus' => 3, 'newStatus' => 4,],
        self::TYPE_COLNAME   => [
            StatusChangeTableMap::COL_ID         => 0,
            StatusChangeTableMap::COL_TEST_ID    => 1,
            StatusChangeTableMap::COL_DATE       => 2,
            StatusChangeTableMap::COL_OLD_STATUS => 3,
            StatusChangeTableMap::COL_NEW_STATUS => 4,
        ],
        self::TYPE_FIELDNAME => ['id' => 0, 'test_id' => 1, 'date' => 2, 'old_status' => 3, 'new_status' => 4,],
        self::TYPE_NUM       => [0, 1, 2, 3, 4,],
    ];

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('status_change_log');
        $this->setPhpName('StatusChange');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\Models\\StatusChange');
        $this->setPackage('Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('test_id', 'TestId', 'INTEGER', 'test', 'id', true, null, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', false, null, null);
        $this->addColumn('old_status', 'OldStatus', 'BOOLEAN', true, 1, null);
        $this->addColumn('new_status', 'NewStatus', 'BOOLEAN', true, 1, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Test', '\\Models\\Test', RelationMap::MANY_TO_ONE, [
            0 =>
                [
                    0 => ':test_id',
                    1 => ':id',
                ],
        ], 'CASCADE', 'CASCADE', null, false);
    } // buildRelations()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row        resultset row.
     * @param int    $offset     The 0-based offset for reading from the resultset row.
     * @param string $indexType  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id',
                TableMap::TYPE_PHPNAME, $indexType)] === null
        ) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id',
            TableMap::TYPE_PHPNAME,
            $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id',
            TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([
            $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id',
                TableMap::TYPE_PHPNAME, $indexType)],
            '__toString',
        ]) ? (string)$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id',
            TableMap::TYPE_PHPNAME,
            $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id',
            TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row        resultset row.
     * @param int    $offset     The 0-based offset for reading from the resultset row.
     * @param string $indexType  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int)$row[$indexType == TableMap::TYPE_NUM
            ? 0 + $offset
            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? StatusChangeTableMap::CLASS_DEFAULT : StatusChangeTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row        row returned by DataFetcher->fetch().
     * @param int    $offset     The 0-based offset for reading from the resultset row.
     * @param string $indexType  The index type of $row. Mostly DataFetcher->getIndexType().
     *                           One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (StatusChange object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = StatusChangeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = StatusChangeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + StatusChangeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = StatusChangeTableMap::OM_CLASS;
            /** @var StatusChange $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            StatusChangeTableMap::addInstanceToPool($obj, $key);
        }

        return [$obj, $col];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = [];

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = StatusChangeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = StatusChangeTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var StatusChange $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                StatusChangeTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                           rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(StatusChangeTableMap::COL_ID);
            $criteria->addSelectColumn(StatusChangeTableMap::COL_TEST_ID);
            $criteria->addSelectColumn(StatusChangeTableMap::COL_DATE);
            $criteria->addSelectColumn(StatusChangeTableMap::COL_OLD_STATUS);
            $criteria->addSelectColumn(StatusChangeTableMap::COL_NEW_STATUS);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.test_id');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.old_status');
            $criteria->addSelectColumn($alias . '.new_status');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     *
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(StatusChangeTableMap::DATABASE_NAME)->getTable(StatusChangeTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(StatusChangeTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(StatusChangeTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new StatusChangeTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a StatusChange or Criteria object OR a primary key value.
     *
     * @param mixed                $values Criteria or StatusChange object or primary key or array of primary keys
     *                                     which is used to create the DELETE statement
     * @param  ConnectionInterface $con    the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                                     if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                                     rethrown wrapped into a PropelException.
     */
    public static function doDelete($values, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StatusChangeTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Models\StatusChange) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(StatusChangeTableMap::DATABASE_NAME);
            $criteria->add(StatusChangeTableMap::COL_ID, (array)$values, Criteria::IN);
        }

        $query = StatusChangeQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            StatusChangeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array)$values as $singleval) {
                StatusChangeTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the status_change_log table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return StatusChangeQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a StatusChange or Criteria object.
     *
     * @param mixed               $criteria Criteria or StatusChange object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con      the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                                      rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(StatusChangeTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from StatusChange object
        }

        if ($criteria->containsKey(StatusChangeTableMap::COL_ID) && $criteria->keyContainsValue(StatusChangeTableMap::COL_ID)) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . StatusChangeTableMap::COL_ID . ')');
        }


        // Set the correct dbName
        $query = StatusChangeQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // StatusChangeTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
StatusChangeTableMap::buildTableMap();
