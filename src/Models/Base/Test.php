<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Domain as ChildDomain;
use Models\DomainQuery as ChildDomainQuery;
use Models\StatusChange as ChildStatusChange;
use Models\StatusChangeQuery as ChildStatusChangeQuery;
use Models\Test as ChildTest;
use Models\TestQuery as ChildTestQuery;
use Models\Map\StatusChangeTableMap;
use Models\Map\TestTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'test' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Test implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\TestTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     *
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     *
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     *
     * @var array
     */
    protected $modifiedColumns = [];

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     *
     * @var array
     */
    protected $virtualColumns = [];

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the domain_id field.
     *
     * @var        int
     */
    protected $domain_id;

    /**
     * The value for the test_type field.
     *
     * @var        int
     */
    protected $test_type;

    /**
     * The value for the status field.
     *
     * @var        boolean
     */
    protected $status;

    /**
     * The value for the last_checked field.
     *
     * @var        DateTime
     */
    protected $last_checked;

    /**
     * @var        ChildDomain
     */
    protected $aDomain;

    /**
     * @var        ObjectCollection|ChildStatusChange[] Collection to store aggregation of ChildStatusChange objects.
     */
    protected $collStatusChanges;
    protected $collStatusChangesPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     *
     * @var ObjectCollection|ChildStatusChange[]
     */
    protected $statusChangesScheduledForDeletion = null;

    /**
     * Initializes internal state of Models\Base\Test object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     *
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean)$b;
    }

    /**
     * Whether this object has been deleted.
     *
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     *
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean)$b;
    }

    /**
     * Sets the modified state for the object to be false.
     *
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = [];
        }
    }

    /**
     * Compares this with another <code>Test</code> instance.  If
     * <code>obj</code> is an instance of <code>Test</code>, delegates to
     * <code>equals(Test)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Test The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string $msg
     * @param  int    $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, [], true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(),
            $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach ($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [domain_id] column value.
     *
     * @return int
     */
    public function getDomainId()
    {
        return $this->domain_id;
    }

    /**
     * Get the [test_type] column value.
     *
     * @return int
     */
    public function getTestType()
    {
        return $this->test_type;
    }

    /**
     * Get the [status] column value.
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [status] column value.
     *
     * @return boolean
     */
    public function isStatus()
    {
        return $this->getStatus();
    }

    /**
     * Get the [optionally formatted] temporal [last_checked] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getLastChecked($format = null)
    {
        if ($format === null) {
            return $this->last_checked;
        } else {
            return $this->last_checked instanceof \DateTimeInterface ? $this->last_checked->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Test The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[TestTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [domain_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Test The current object (for fluent API support)
     */
    public function setDomainId($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->domain_id !== $v) {
            $this->domain_id = $v;
            $this->modifiedColumns[TestTableMap::COL_DOMAIN_ID] = true;
        }

        if ($this->aDomain !== null && $this->aDomain->getId() !== $v) {
            $this->aDomain = null;
        }

        return $this;
    } // setDomainId()

    /**
     * Set the value of [test_type] column.
     *
     * @param int $v new value
     * @return $this|\Models\Test The current object (for fluent API support)
     */
    public function setTestType($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->test_type !== $v) {
            $this->test_type = $v;
            $this->modifiedColumns[TestTableMap::COL_TEST_TYPE] = true;
        }

        return $this;
    } // setTestType()

    /**
     * Sets the value of the [status] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\Test The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), ['false', 'off', '-', 'no', 'n', '0', '']) ? false : true;
            } else {
                $v = (boolean)$v;
            }
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[TestTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Sets the value of [last_checked] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *                  Empty strings are treated as NULL.
     * @return $this|\Models\Test The current object (for fluent API support)
     */
    public function setLastChecked($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_checked !== null || $dt !== null) {
            if ($this->last_checked === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->last_checked->format("Y-m-d H:i:s")) {
                $this->last_checked = $dt === null ? null : clone $dt;
                $this->modifiedColumns[TestTableMap::COL_LAST_CHECKED] = true;
            }
        } // if either are not null

        return $this;
    } // setLastChecked()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row        The row returned by DataFetcher->fetch().
     * @param int     $startcol   0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate  Whether this object is being re-hydrated from the database.
     * @param string  $indexType  The index type of $row. Mostly DataFetcher->getIndexType().
     *                            One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : TestTableMap::translateFieldName('Id',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : TestTableMap::translateFieldName('DomainId',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->domain_id = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : TestTableMap::translateFieldName('TestType',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->test_type = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : TestTableMap::translateFieldName('Status',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (boolean)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : TestTableMap::translateFieldName('LastChecked',
                TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->last_checked = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = TestTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Test'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aDomain !== null && $this->domain_id !== $this->aDomain->getId()) {
            $this->aDomain = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean             $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con  (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(TestTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildTestQuery::create(null,
            $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aDomain = null;
            $this->collStatusChanges = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Test::setDeleted()
     * @see Test::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TestTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildTestQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(TestTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                TestTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aDomain !== null) {
                if ($this->aDomain->isModified() || $this->aDomain->isNew()) {
                    $affectedRows += $this->aDomain->save($con);
                }
                $this->setDomain($this->aDomain);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->statusChangesScheduledForDeletion !== null) {
                if (!$this->statusChangesScheduledForDeletion->isEmpty()) {
                    \Models\StatusChangeQuery::create()
                        ->filterByPrimaryKeys($this->statusChangesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->statusChangesScheduledForDeletion = null;
                }
            }

            if ($this->collStatusChanges !== null) {
                foreach ($this->collStatusChanges as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = [];
        $index = 0;

        $this->modifiedColumns[TestTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TestTableMap::COL_ID . ')');
        }

        // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TestTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++] = 'id';
        }
        if ($this->isColumnModified(TestTableMap::COL_DOMAIN_ID)) {
            $modifiedColumns[':p' . $index++] = 'domain_id';
        }
        if ($this->isColumnModified(TestTableMap::COL_TEST_TYPE)) {
            $modifiedColumns[':p' . $index++] = 'test_type';
        }
        if ($this->isColumnModified(TestTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++] = 'status';
        }
        if ($this->isColumnModified(TestTableMap::COL_LAST_CHECKED)) {
            $modifiedColumns[':p' . $index++] = 'last_checked';
        }

        $sql = sprintf(
            'INSERT INTO test (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'domain_id':
                        $stmt->bindValue($identifier, $this->domain_id, PDO::PARAM_INT);
                        break;
                    case 'test_type':
                        $stmt->bindValue($identifier, $this->test_type, PDO::PARAM_INT);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, (int)$this->status, PDO::PARAM_INT);
                        break;
                    case 'last_checked':
                        $stmt->bindValue($identifier,
                            $this->last_checked ? $this->last_checked->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                          one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                          TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                          Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TestTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getDomainId();
                break;
            case 2:
                return $this->getTestType();
                break;
            case 3:
                return $this->getStatus();
                break;
            case 4:
                return $this->getLastChecked();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType                (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                                            Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array   $alreadyDumpedObjects   List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects  (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray(
        $keyType = TableMap::TYPE_PHPNAME,
        $includeLazyLoadColumns = true,
        $alreadyDumpedObjects = [],
        $includeForeignObjects = false
    ) {

        if (isset($alreadyDumpedObjects['Test'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Test'][$this->hashCode()] = true;
        $keys = TestTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getDomainId(),
            $keys[2] => $this->getTestType(),
            $keys[3] => $this->getStatus(),
            $keys[4] => $this->getLastChecked(),
        ];
        if ($result[$keys[4]] instanceof \DateTime) {
            $result[$keys[4]] = $result[$keys[4]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aDomain) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'domain';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'domain';
                        break;
                    default:
                        $key = 'Domain';
                }

                $result[$key] = $this->aDomain->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects, true);
            }
            if (null !== $this->collStatusChanges) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'statusChanges';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'status_change_logs';
                        break;
                    default:
                        $key = 'StatusChanges';
                }

                $result[$key] = $this->collStatusChanges->toArray(null, false, $keyType, $includeLazyLoadColumns,
                    $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type  The type of fieldname the $name is of:
     *                       one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                       TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                       Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\Models\Test
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = TestTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int   $pos   position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Test
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setDomainId($value);
                break;
            case 2:
                $this->setTestType($value);
                break;
            case 3:
                $this->setStatus($value);
                break;
            case 4:
                $this->setLastChecked($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = TestTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setDomainId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTestType($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setStatus($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLastChecked($arr[$keys[4]]);
        }
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed  $parser  A AbstractParser instance,
     *                        or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data    The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\Models\Test The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TestTableMap::DATABASE_NAME);

        if ($this->isColumnModified(TestTableMap::COL_ID)) {
            $criteria->add(TestTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(TestTableMap::COL_DOMAIN_ID)) {
            $criteria->add(TestTableMap::COL_DOMAIN_ID, $this->domain_id);
        }
        if ($this->isColumnModified(TestTableMap::COL_TEST_TYPE)) {
            $criteria->add(TestTableMap::COL_TEST_TYPE, $this->test_type);
        }
        if ($this->isColumnModified(TestTableMap::COL_STATUS)) {
            $criteria->add(TestTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(TestTableMap::COL_LAST_CHECKED)) {
            $criteria->add(TestTableMap::COL_LAST_CHECKED, $this->last_checked);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildTestQuery::create();
        $criteria->add(TestTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     *
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     *
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object  $copyObj  An object of \Models\Test (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew  Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDomainId($this->getDomainId());
        $copyObj->setTestType($this->getTestType());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setLastChecked($this->getLastChecked());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getStatusChanges() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addStatusChange($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(null); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \Models\Test Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildDomain object.
     *
     * @param  ChildDomain $v
     * @return $this|\Models\Test The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDomain(ChildDomain $v = null)
    {
        if ($v === null) {
            $this->setDomainId(null);
        } else {
            $this->setDomainId($v->getId());
        }

        $this->aDomain = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildDomain object, it will not be re-added.
        if ($v !== null) {
            $v->addTest($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildDomain object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildDomain The associated ChildDomain object.
     * @throws PropelException
     */
    public function getDomain(ConnectionInterface $con = null)
    {
        if ($this->aDomain === null && ($this->domain_id !== null)) {
            $this->aDomain = ChildDomainQuery::create()->findPk($this->domain_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDomain->addTests($this);
             */
        }

        return $this->aDomain;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('StatusChange' == $relationName) {
            return $this->initStatusChanges();
        }
    }

    /**
     * Clears out the collStatusChanges collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addStatusChanges()
     */
    public function clearStatusChanges()
    {
        $this->collStatusChanges = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collStatusChanges collection loaded partially.
     */
    public function resetPartialStatusChanges($v = true)
    {
        $this->collStatusChangesPartial = $v;
    }

    /**
     * Initializes the collStatusChanges collection.
     *
     * By default this just sets the collStatusChanges collection to an empty array (like clearcollStatusChanges());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting  If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initStatusChanges($overrideExisting = true)
    {
        if (null !== $this->collStatusChanges && !$overrideExisting) {
            return;
        }

        $collectionClassName = StatusChangeTableMap::getTableMap()->getCollectionClassName();

        $this->collStatusChanges = new $collectionClassName;
        $this->collStatusChanges->setModel('\Models\StatusChange');
    }

    /**
     * Gets an array of ChildStatusChange objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildTest is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria            $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con      optional connection object
     * @return ObjectCollection|ChildStatusChange[] List of ChildStatusChange objects
     * @throws PropelException
     */
    public function getStatusChanges(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collStatusChangesPartial && !$this->isNew();
        if (null === $this->collStatusChanges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStatusChanges) {
                // return empty collection
                $this->initStatusChanges();
            } else {
                $collStatusChanges = ChildStatusChangeQuery::create(null, $criteria)
                    ->filterByTest($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collStatusChangesPartial && count($collStatusChanges)) {
                        $this->initStatusChanges(false);

                        foreach ($collStatusChanges as $obj) {
                            if (false == $this->collStatusChanges->contains($obj)) {
                                $this->collStatusChanges->append($obj);
                            }
                        }

                        $this->collStatusChangesPartial = true;
                    }

                    return $collStatusChanges;
                }

                if ($partial && $this->collStatusChanges) {
                    foreach ($this->collStatusChanges as $obj) {
                        if ($obj->isNew()) {
                            $collStatusChanges[] = $obj;
                        }
                    }
                }

                $this->collStatusChanges = $collStatusChanges;
                $this->collStatusChangesPartial = false;
            }
        }

        return $this->collStatusChanges;
    }

    /**
     * Sets a collection of ChildStatusChange objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection          $statusChanges A Propel collection.
     * @param      ConnectionInterface $con           Optional connection object
     * @return $this|ChildTest The current object (for fluent API support)
     */
    public function setStatusChanges(Collection $statusChanges, ConnectionInterface $con = null)
    {
        /** @var ChildStatusChange[] $statusChangesToDelete */
        $statusChangesToDelete = $this->getStatusChanges(new Criteria(), $con)->diff($statusChanges);


        $this->statusChangesScheduledForDeletion = $statusChangesToDelete;

        foreach ($statusChangesToDelete as $statusChangeRemoved) {
            $statusChangeRemoved->setTest(null);
        }

        $this->collStatusChanges = null;
        foreach ($statusChanges as $statusChange) {
            $this->addStatusChange($statusChange);
        }

        $this->collStatusChanges = $statusChanges;
        $this->collStatusChangesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related StatusChange objects.
     *
     * @param      Criteria            $criteria
     * @param      boolean             $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related StatusChange objects.
     * @throws PropelException
     */
    public function countStatusChanges(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collStatusChangesPartial && !$this->isNew();
        if (null === $this->collStatusChanges || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collStatusChanges) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getStatusChanges());
            }

            $query = ChildStatusChangeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTest($this)
                ->count($con);
        }

        return count($this->collStatusChanges);
    }

    /**
     * Method called to associate a ChildStatusChange object to this object
     * through the ChildStatusChange foreign key attribute.
     *
     * @param  ChildStatusChange $l ChildStatusChange
     * @return $this|\Models\Test The current object (for fluent API support)
     */
    public function addStatusChange(ChildStatusChange $l)
    {
        if ($this->collStatusChanges === null) {
            $this->initStatusChanges();
            $this->collStatusChangesPartial = true;
        }

        if (!$this->collStatusChanges->contains($l)) {
            $this->doAddStatusChange($l);

            if ($this->statusChangesScheduledForDeletion and $this->statusChangesScheduledForDeletion->contains($l)) {
                $this->statusChangesScheduledForDeletion->remove($this->statusChangesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildStatusChange $statusChange The ChildStatusChange object to add.
     */
    protected function doAddStatusChange(ChildStatusChange $statusChange)
    {
        $this->collStatusChanges[] = $statusChange;
        $statusChange->setTest($this);
    }

    /**
     * @param  ChildStatusChange $statusChange The ChildStatusChange object to remove.
     * @return $this|ChildTest The current object (for fluent API support)
     */
    public function removeStatusChange(ChildStatusChange $statusChange)
    {
        if ($this->getStatusChanges()->contains($statusChange)) {
            $pos = $this->collStatusChanges->search($statusChange);
            $this->collStatusChanges->remove($pos);
            if (null === $this->statusChangesScheduledForDeletion) {
                $this->statusChangesScheduledForDeletion = clone $this->collStatusChanges;
                $this->statusChangesScheduledForDeletion->clear();
            }
            $this->statusChangesScheduledForDeletion[] = clone $statusChange;
            $statusChange->setTest(null);
        }

        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aDomain) {
            $this->aDomain->removeTest($this);
        }
        $this->id = null;
        $this->domain_id = null;
        $this->test_type = null;
        $this->status = null;
        $this->last_checked = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collStatusChanges) {
                foreach ($this->collStatusChanges as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collStatusChanges = null;
        $this->aDomain = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->exportTo(TestTableMap::DEFAULT_STRING_FORMAT);
    }

    /**
     * Code to be run before persisting the object
     *
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     *
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     *
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     *
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     *
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     *
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     *
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     *
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
