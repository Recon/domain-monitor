<?php

namespace Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use Models\Account as ChildAccount;
use Models\AccountQuery as ChildAccountQuery;
use Models\Domain as ChildDomain;
use Models\DomainQuery as ChildDomainQuery;
use Models\Test as ChildTest;
use Models\TestQuery as ChildTestQuery;
use Models\User as ChildUser;
use Models\UserQuery as ChildUserQuery;
use Models\UsersDomain as ChildUsersDomain;
use Models\UsersDomainQuery as ChildUsersDomainQuery;
use Models\Map\DomainTableMap;
use Models\Map\TestTableMap;
use Models\Map\UsersDomainTableMap;
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
 * Base class that represents a row from the 'domain' table.
 *
 *
 *
 * @package    propel.generator.Models.Base
 */
abstract class Domain implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Models\\Map\\DomainTableMap';


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
     * The value for the account_id field.
     *
     * @var        int
     */
    protected $account_id;

    /**
     * The value for the uri field.
     *
     * @var        string
     */
    protected $uri;

    /**
     * The value for the status field.
     *
     * Note: this column has a database default value of: 0
     *
     * @var        int
     */
    protected $status;

    /**
     * The value for the is_enabled field.
     *
     * Note: this column has a database default value of: false
     *
     * @var        boolean
     */
    protected $is_enabled;

    /**
     * The value for the last_checked field.
     *
     * @var        DateTime
     */
    protected $last_checked;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * @var        ChildAccount
     */
    protected $aAccount;

    /**
     * @var        ObjectCollection|ChildUsersDomain[] Collection to store aggregation of ChildUsersDomain objects.
     */
    protected $collUsersDomains;
    protected $collUsersDomainsPartial;

    /**
     * @var        ObjectCollection|ChildTest[] Collection to store aggregation of ChildTest objects.
     */
    protected $collTests;
    protected $collTestsPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

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
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     *
     * @var ObjectCollection|ChildUsersDomain[]
     */
    protected $usersDomainsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     *
     * @var ObjectCollection|ChildTest[]
     */
    protected $testsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     *
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->status = 0;
        $this->is_enabled = false;
    }

    /**
     * Initializes internal state of Models\Base\Domain object.
     *
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>Domain</code> instance.  If
     * <code>obj</code> is an instance of <code>Domain</code>, delegates to
     * <code>equals(Domain)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Domain The current object, for fluid interface
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
     * Get the [account_id] column value.
     *
     * @return int
     */
    public function getAccountId()
    {
        return $this->account_id;
    }

    /**
     * Get the [uri] column value.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the [is_enabled] column value.
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->is_enabled;
    }

    /**
     * Get the [is_enabled] column value.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getIsEnabled();
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
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[DomainTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [account_id] column.
     *
     * @param int $v new value
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setAccountId($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->account_id !== $v) {
            $this->account_id = $v;
            $this->modifiedColumns[DomainTableMap::COL_ACCOUNT_ID] = true;
        }

        if ($this->aAccount !== null && $this->aAccount->getId() !== $v) {
            $this->aAccount = null;
        }

        return $this;
    } // setAccountId()

    /**
     * Set the value of [uri] column.
     *
     * @param string $v new value
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setUri($v)
    {
        if ($v !== null) {
            $v = (string)$v;
        }

        if ($this->uri !== $v) {
            $this->uri = $v;
            $this->modifiedColumns[DomainTableMap::COL_URI] = true;
        }

        return $this;
    } // setUri()

    /**
     * Set the value of [status] column.
     *
     * @param int $v new value
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null) {
            $v = (int)$v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[DomainTableMap::COL_STATUS] = true;
        }

        return $this;
    } // setStatus()

    /**
     * Sets the value of the [is_enabled] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param  boolean|integer|string $v The new value
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setIsEnabled($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), ['false', 'off', '-', 'no', 'n', '0', '']) ? false : true;
            } else {
                $v = (boolean)$v;
            }
        }

        if ($this->is_enabled !== $v) {
            $this->is_enabled = $v;
            $this->modifiedColumns[DomainTableMap::COL_IS_ENABLED] = true;
        }

        return $this;
    } // setIsEnabled()

    /**
     * Sets the value of [last_checked] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *                  Empty strings are treated as NULL.
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setLastChecked($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->last_checked !== null || $dt !== null) {
            if ($this->last_checked === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->last_checked->format("Y-m-d H:i:s")) {
                $this->last_checked = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DomainTableMap::COL_LAST_CHECKED] = true;
            }
        } // if either are not null

        return $this;
    } // setLastChecked()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *                  Empty strings are treated as NULL.
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_at->format("Y-m-d H:i:s")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DomainTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *                  Empty strings are treated as NULL.
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_at->format("Y-m-d H:i:s")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[DomainTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

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
        if ($this->status !== 0) {
            return false;
        }

        if ($this->is_enabled !== false) {
            return false;
        }

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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : DomainTableMap::translateFieldName('Id',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : DomainTableMap::translateFieldName('AccountId',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->account_id = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : DomainTableMap::translateFieldName('Uri',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->uri = (null !== $col) ? (string)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : DomainTableMap::translateFieldName('Status',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->status = (null !== $col) ? (int)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : DomainTableMap::translateFieldName('IsEnabled',
                TableMap::TYPE_PHPNAME, $indexType)];
            $this->is_enabled = (null !== $col) ? (boolean)$col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : DomainTableMap::translateFieldName('LastChecked',
                TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->last_checked = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : DomainTableMap::translateFieldName('CreatedAt',
                TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : DomainTableMap::translateFieldName('UpdatedAt',
                TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 8; // 8 = DomainTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\Models\\Domain'), 0, $e);
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
        if ($this->aAccount !== null && $this->account_id !== $this->aAccount->getId()) {
            $this->aAccount = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(DomainTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildDomainQuery::create(null,
            $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aAccount = null;
            $this->collUsersDomains = null;

            $this->collTests = null;

            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Domain::setDeleted()
     * @see Domain::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(DomainTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildDomainQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(DomainTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(DomainTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(DomainTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(DomainTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                DomainTableMap::addInstanceToPool($this);
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

            if ($this->aAccount !== null) {
                if ($this->aAccount->isModified() || $this->aAccount->isNew()) {
                    $affectedRows += $this->aAccount->save($con);
                }
                $this->setAccount($this->aAccount);
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

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = [];
                    foreach ($this->usersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[1] = $this->getId();
                        $entryPk[0] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \Models\UsersDomainQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->usersScheduledForDeletion = null;
                }

            }

            if ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if (!$user->isDeleted() && ($user->isNew() || $user->isModified())) {
                        $user->save($con);
                    }
                }
            }


            if ($this->usersDomainsScheduledForDeletion !== null) {
                if (!$this->usersDomainsScheduledForDeletion->isEmpty()) {
                    \Models\UsersDomainQuery::create()
                        ->filterByPrimaryKeys($this->usersDomainsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->usersDomainsScheduledForDeletion = null;
                }
            }

            if ($this->collUsersDomains !== null) {
                foreach ($this->collUsersDomains as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->testsScheduledForDeletion !== null) {
                if (!$this->testsScheduledForDeletion->isEmpty()) {
                    \Models\TestQuery::create()
                        ->filterByPrimaryKeys($this->testsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->testsScheduledForDeletion = null;
                }
            }

            if ($this->collTests !== null) {
                foreach ($this->collTests as $referrerFK) {
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

        $this->modifiedColumns[DomainTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DomainTableMap::COL_ID . ')');
        }

        // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DomainTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++] = 'id';
        }
        if ($this->isColumnModified(DomainTableMap::COL_ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++] = 'account_id';
        }
        if ($this->isColumnModified(DomainTableMap::COL_URI)) {
            $modifiedColumns[':p' . $index++] = 'uri';
        }
        if ($this->isColumnModified(DomainTableMap::COL_STATUS)) {
            $modifiedColumns[':p' . $index++] = 'status';
        }
        if ($this->isColumnModified(DomainTableMap::COL_IS_ENABLED)) {
            $modifiedColumns[':p' . $index++] = 'is_enabled';
        }
        if ($this->isColumnModified(DomainTableMap::COL_LAST_CHECKED)) {
            $modifiedColumns[':p' . $index++] = 'last_checked';
        }
        if ($this->isColumnModified(DomainTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++] = 'created_at';
        }
        if ($this->isColumnModified(DomainTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++] = 'updated_at';
        }

        $sql = sprintf(
            'INSERT INTO domain (%s) VALUES (%s)',
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
                    case 'account_id':
                        $stmt->bindValue($identifier, $this->account_id, PDO::PARAM_INT);
                        break;
                    case 'uri':
                        $stmt->bindValue($identifier, $this->uri, PDO::PARAM_STR);
                        break;
                    case 'status':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case 'is_enabled':
                        $stmt->bindValue($identifier, (int)$this->is_enabled, PDO::PARAM_INT);
                        break;
                    case 'last_checked':
                        $stmt->bindValue($identifier,
                            $this->last_checked ? $this->last_checked->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier,
                            $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier,
                            $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
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
        $pos = DomainTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getAccountId();
                break;
            case 2:
                return $this->getUri();
                break;
            case 3:
                return $this->getStatus();
                break;
            case 4:
                return $this->getIsEnabled();
                break;
            case 5:
                return $this->getLastChecked();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
                return $this->getUpdatedAt();
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

        if (isset($alreadyDumpedObjects['Domain'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Domain'][$this->hashCode()] = true;
        $keys = DomainTableMap::getFieldNames($keyType);
        $result = [
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAccountId(),
            $keys[2] => $this->getUri(),
            $keys[3] => $this->getStatus(),
            $keys[4] => $this->getIsEnabled(),
            $keys[5] => $this->getLastChecked(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
        ];
        if ($result[$keys[5]] instanceof \DateTime) {
            $result[$keys[5]] = $result[$keys[5]]->format('c');
        }

        if ($result[$keys[6]] instanceof \DateTime) {
            $result[$keys[6]] = $result[$keys[6]]->format('c');
        }

        if ($result[$keys[7]] instanceof \DateTime) {
            $result[$keys[7]] = $result[$keys[7]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aAccount) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'account';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'account';
                        break;
                    default:
                        $key = 'Account';
                }

                $result[$key] = $this->aAccount->toArray($keyType, $includeLazyLoadColumns, $alreadyDumpedObjects,
                    true);
            }
            if (null !== $this->collUsersDomains) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'usersDomains';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'users_domains';
                        break;
                    default:
                        $key = 'UsersDomains';
                }

                $result[$key] = $this->collUsersDomains->toArray(null, false, $keyType, $includeLazyLoadColumns,
                    $alreadyDumpedObjects);
            }
            if (null !== $this->collTests) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'tests';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'tests';
                        break;
                    default:
                        $key = 'Tests';
                }

                $result[$key] = $this->collTests->toArray(null, false, $keyType, $includeLazyLoadColumns,
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
     * @return $this|\Models\Domain
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = DomainTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int   $pos   position in xml schema
     * @param  mixed $value field value
     * @return $this|\Models\Domain
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setAccountId($value);
                break;
            case 2:
                $this->setUri($value);
                break;
            case 3:
                $this->setStatus($value);
                break;
            case 4:
                $this->setIsEnabled($value);
                break;
            case 5:
                $this->setLastChecked($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
                $this->setUpdatedAt($value);
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
        $keys = DomainTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setAccountId($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setUri($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setStatus($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setIsEnabled($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setLastChecked($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setCreatedAt($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setUpdatedAt($arr[$keys[7]]);
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
     * @return $this|\Models\Domain The current object, for fluid interface
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
        $criteria = new Criteria(DomainTableMap::DATABASE_NAME);

        if ($this->isColumnModified(DomainTableMap::COL_ID)) {
            $criteria->add(DomainTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(DomainTableMap::COL_ACCOUNT_ID)) {
            $criteria->add(DomainTableMap::COL_ACCOUNT_ID, $this->account_id);
        }
        if ($this->isColumnModified(DomainTableMap::COL_URI)) {
            $criteria->add(DomainTableMap::COL_URI, $this->uri);
        }
        if ($this->isColumnModified(DomainTableMap::COL_STATUS)) {
            $criteria->add(DomainTableMap::COL_STATUS, $this->status);
        }
        if ($this->isColumnModified(DomainTableMap::COL_IS_ENABLED)) {
            $criteria->add(DomainTableMap::COL_IS_ENABLED, $this->is_enabled);
        }
        if ($this->isColumnModified(DomainTableMap::COL_LAST_CHECKED)) {
            $criteria->add(DomainTableMap::COL_LAST_CHECKED, $this->last_checked);
        }
        if ($this->isColumnModified(DomainTableMap::COL_CREATED_AT)) {
            $criteria->add(DomainTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(DomainTableMap::COL_UPDATED_AT)) {
            $criteria->add(DomainTableMap::COL_UPDATED_AT, $this->updated_at);
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
        $criteria = ChildDomainQuery::create();
        $criteria->add(DomainTableMap::COL_ID, $this->id);

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
     * @param      object  $copyObj  An object of \Models\Domain (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew  Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAccountId($this->getAccountId());
        $copyObj->setUri($this->getUri());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setIsEnabled($this->getIsEnabled());
        $copyObj->setLastChecked($this->getLastChecked());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getUsersDomains() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addUsersDomain($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTests() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTest($relObj->copy($deepCopy));
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
     * @return \Models\Domain Clone of current object.
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
     * Declares an association between this object and a ChildAccount object.
     *
     * @param  ChildAccount $v
     * @return $this|\Models\Domain The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccount(ChildAccount $v = null)
    {
        if ($v === null) {
            $this->setAccountId(null);
        } else {
            $this->setAccountId($v->getId());
        }

        $this->aAccount = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildAccount object, it will not be re-added.
        if ($v !== null) {
            $v->addDomain($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildAccount object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildAccount The associated ChildAccount object.
     * @throws PropelException
     */
    public function getAccount(ConnectionInterface $con = null)
    {
        if ($this->aAccount === null && ($this->account_id !== null)) {
            $this->aAccount = ChildAccountQuery::create()->findPk($this->account_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAccount->addDomains($this);
             */
        }

        return $this->aAccount;
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
        if ('UsersDomain' == $relationName) {
            return $this->initUsersDomains();
        }
        if ('Test' == $relationName) {
            return $this->initTests();
        }
    }

    /**
     * Clears out the collUsersDomains collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsersDomains()
     */
    public function clearUsersDomains()
    {
        $this->collUsersDomains = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collUsersDomains collection loaded partially.
     */
    public function resetPartialUsersDomains($v = true)
    {
        $this->collUsersDomainsPartial = $v;
    }

    /**
     * Initializes the collUsersDomains collection.
     *
     * By default this just sets the collUsersDomains collection to an empty array (like clearcollUsersDomains());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting  If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initUsersDomains($overrideExisting = true)
    {
        if (null !== $this->collUsersDomains && !$overrideExisting) {
            return;
        }

        $collectionClassName = UsersDomainTableMap::getTableMap()->getCollectionClassName();

        $this->collUsersDomains = new $collectionClassName;
        $this->collUsersDomains->setModel('\Models\UsersDomain');
    }

    /**
     * Gets an array of ChildUsersDomain objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDomain is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria            $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con      optional connection object
     * @return ObjectCollection|ChildUsersDomain[] List of ChildUsersDomain objects
     * @throws PropelException
     */
    public function getUsersDomains(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersDomainsPartial && !$this->isNew();
        if (null === $this->collUsersDomains || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersDomains) {
                // return empty collection
                $this->initUsersDomains();
            } else {
                $collUsersDomains = ChildUsersDomainQuery::create(null, $criteria)
                    ->filterByDomain($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collUsersDomainsPartial && count($collUsersDomains)) {
                        $this->initUsersDomains(false);

                        foreach ($collUsersDomains as $obj) {
                            if (false == $this->collUsersDomains->contains($obj)) {
                                $this->collUsersDomains->append($obj);
                            }
                        }

                        $this->collUsersDomainsPartial = true;
                    }

                    return $collUsersDomains;
                }

                if ($partial && $this->collUsersDomains) {
                    foreach ($this->collUsersDomains as $obj) {
                        if ($obj->isNew()) {
                            $collUsersDomains[] = $obj;
                        }
                    }
                }

                $this->collUsersDomains = $collUsersDomains;
                $this->collUsersDomainsPartial = false;
            }
        }

        return $this->collUsersDomains;
    }

    /**
     * Sets a collection of ChildUsersDomain objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection          $usersDomains A Propel collection.
     * @param      ConnectionInterface $con          Optional connection object
     * @return $this|ChildDomain The current object (for fluent API support)
     */
    public function setUsersDomains(Collection $usersDomains, ConnectionInterface $con = null)
    {
        /** @var ChildUsersDomain[] $usersDomainsToDelete */
        $usersDomainsToDelete = $this->getUsersDomains(new Criteria(), $con)->diff($usersDomains);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->usersDomainsScheduledForDeletion = clone $usersDomainsToDelete;

        foreach ($usersDomainsToDelete as $usersDomainRemoved) {
            $usersDomainRemoved->setDomain(null);
        }

        $this->collUsersDomains = null;
        foreach ($usersDomains as $usersDomain) {
            $this->addUsersDomain($usersDomain);
        }

        $this->collUsersDomains = $usersDomains;
        $this->collUsersDomainsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related UsersDomain objects.
     *
     * @param      Criteria            $criteria
     * @param      boolean             $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related UsersDomain objects.
     * @throws PropelException
     */
    public function countUsersDomains(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersDomainsPartial && !$this->isNew();
        if (null === $this->collUsersDomains || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsersDomains) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getUsersDomains());
            }

            $query = ChildUsersDomainQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDomain($this)
                ->count($con);
        }

        return count($this->collUsersDomains);
    }

    /**
     * Method called to associate a ChildUsersDomain object to this object
     * through the ChildUsersDomain foreign key attribute.
     *
     * @param  ChildUsersDomain $l ChildUsersDomain
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function addUsersDomain(ChildUsersDomain $l)
    {
        if ($this->collUsersDomains === null) {
            $this->initUsersDomains();
            $this->collUsersDomainsPartial = true;
        }

        if (!$this->collUsersDomains->contains($l)) {
            $this->doAddUsersDomain($l);

            if ($this->usersDomainsScheduledForDeletion and $this->usersDomainsScheduledForDeletion->contains($l)) {
                $this->usersDomainsScheduledForDeletion->remove($this->usersDomainsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildUsersDomain $usersDomain The ChildUsersDomain object to add.
     */
    protected function doAddUsersDomain(ChildUsersDomain $usersDomain)
    {
        $this->collUsersDomains[] = $usersDomain;
        $usersDomain->setDomain($this);
    }

    /**
     * @param  ChildUsersDomain $usersDomain The ChildUsersDomain object to remove.
     * @return $this|ChildDomain The current object (for fluent API support)
     */
    public function removeUsersDomain(ChildUsersDomain $usersDomain)
    {
        if ($this->getUsersDomains()->contains($usersDomain)) {
            $pos = $this->collUsersDomains->search($usersDomain);
            $this->collUsersDomains->remove($pos);
            if (null === $this->usersDomainsScheduledForDeletion) {
                $this->usersDomainsScheduledForDeletion = clone $this->collUsersDomains;
                $this->usersDomainsScheduledForDeletion->clear();
            }
            $this->usersDomainsScheduledForDeletion[] = clone $usersDomain;
            $usersDomain->setDomain(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Domain is new, it will return
     * an empty collection; or if this Domain has previously
     * been saved, it will retrieve related UsersDomains from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Domain.
     *
     * @param      Criteria            $criteria     optional Criteria object to narrow the query
     * @param      ConnectionInterface $con          optional connection object
     * @param      string              $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildUsersDomain[] List of ChildUsersDomain objects
     */
    public function getUsersDomainsJoinUser(
        Criteria $criteria = null,
        ConnectionInterface $con = null,
        $joinBehavior = Criteria::LEFT_JOIN
    ) {
        $query = ChildUsersDomainQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getUsersDomains($query, $con);
    }

    /**
     * Clears out the collTests collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addTests()
     */
    public function clearTests()
    {
        $this->collTests = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collTests collection loaded partially.
     */
    public function resetPartialTests($v = true)
    {
        $this->collTestsPartial = $v;
    }

    /**
     * Initializes the collTests collection.
     *
     * By default this just sets the collTests collection to an empty array (like clearcollTests());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting  If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTests($overrideExisting = true)
    {
        if (null !== $this->collTests && !$overrideExisting) {
            return;
        }

        $collectionClassName = TestTableMap::getTableMap()->getCollectionClassName();

        $this->collTests = new $collectionClassName;
        $this->collTests->setModel('\Models\Test');
    }

    /**
     * Gets an array of ChildTest objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDomain is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria            $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con      optional connection object
     * @return ObjectCollection|ChildTest[] List of ChildTest objects
     * @throws PropelException
     */
    public function getTests(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collTestsPartial && !$this->isNew();
        if (null === $this->collTests || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTests) {
                // return empty collection
                $this->initTests();
            } else {
                $collTests = ChildTestQuery::create(null, $criteria)
                    ->filterByDomain($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collTestsPartial && count($collTests)) {
                        $this->initTests(false);

                        foreach ($collTests as $obj) {
                            if (false == $this->collTests->contains($obj)) {
                                $this->collTests->append($obj);
                            }
                        }

                        $this->collTestsPartial = true;
                    }

                    return $collTests;
                }

                if ($partial && $this->collTests) {
                    foreach ($this->collTests as $obj) {
                        if ($obj->isNew()) {
                            $collTests[] = $obj;
                        }
                    }
                }

                $this->collTests = $collTests;
                $this->collTestsPartial = false;
            }
        }

        return $this->collTests;
    }

    /**
     * Sets a collection of ChildTest objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection          $tests A Propel collection.
     * @param      ConnectionInterface $con   Optional connection object
     * @return $this|ChildDomain The current object (for fluent API support)
     */
    public function setTests(Collection $tests, ConnectionInterface $con = null)
    {
        /** @var ChildTest[] $testsToDelete */
        $testsToDelete = $this->getTests(new Criteria(), $con)->diff($tests);


        $this->testsScheduledForDeletion = $testsToDelete;

        foreach ($testsToDelete as $testRemoved) {
            $testRemoved->setDomain(null);
        }

        $this->collTests = null;
        foreach ($tests as $test) {
            $this->addTest($test);
        }

        $this->collTests = $tests;
        $this->collTestsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Test objects.
     *
     * @param      Criteria            $criteria
     * @param      boolean             $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Test objects.
     * @throws PropelException
     */
    public function countTests(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collTestsPartial && !$this->isNew();
        if (null === $this->collTests || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTests) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTests());
            }

            $query = ChildTestQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDomain($this)
                ->count($con);
        }

        return count($this->collTests);
    }

    /**
     * Method called to associate a ChildTest object to this object
     * through the ChildTest foreign key attribute.
     *
     * @param  ChildTest $l ChildTest
     * @return $this|\Models\Domain The current object (for fluent API support)
     */
    public function addTest(ChildTest $l)
    {
        if ($this->collTests === null) {
            $this->initTests();
            $this->collTestsPartial = true;
        }

        if (!$this->collTests->contains($l)) {
            $this->doAddTest($l);

            if ($this->testsScheduledForDeletion and $this->testsScheduledForDeletion->contains($l)) {
                $this->testsScheduledForDeletion->remove($this->testsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildTest $test The ChildTest object to add.
     */
    protected function doAddTest(ChildTest $test)
    {
        $this->collTests[] = $test;
        $test->setDomain($this);
    }

    /**
     * @param  ChildTest $test The ChildTest object to remove.
     * @return $this|ChildDomain The current object (for fluent API support)
     */
    public function removeTest(ChildTest $test)
    {
        if ($this->getTests()->contains($test)) {
            $pos = $this->collTests->search($test);
            $this->collTests->remove($pos);
            if (null === $this->testsScheduledForDeletion) {
                $this->testsScheduledForDeletion = clone $this->collTests;
                $this->testsScheduledForDeletion->clear();
            }
            $this->testsScheduledForDeletion[] = clone $test;
            $test->setDomain(null);
        }

        return $this;
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUsers crossRef collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $collectionClassName = UsersDomainTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsersPartial = true;
        $this->collUsers->setModel('\Models\User');
    }

    /**
     * Checks if the collUsers collection is loaded.
     *
     * @return bool
     */
    public function isUsersLoaded()
    {
        return null !== $this->collUsers;
    }

    /**
     * Gets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the users_domain cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildDomain is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria            $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con      Optional connection object
     *
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                }
            } else {

                $query = ChildUserQuery::create(null, $criteria)
                    ->filterByDomain($this);
                $collUsers = $query->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUsers as $obj) {
                        if (!$collUsers->contains($obj)) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the users_domain cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection          $users A Propel collection.
     * @param  ConnectionInterface $con   Optional connection object
     * @return $this|ChildDomain The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($usersScheduledForDeletion as $toDelete) {
            $this->removeUser($toDelete);
        }

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsersPartial = false;
        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the users_domain cross-reference table.
     *
     * @param      Criteria            $criteria Optional query object to filter the query
     * @param      boolean             $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con      Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUsers());
                }

                $query = ChildUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByDomain($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the users_domain cross reference table.
     *
     * @param ChildUser $user
     * @return ChildDomain The current object (for fluent API support)
     */
    public function addUser(ChildUser $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->getUsers()->contains($user)) {
            // only add it if the **same** object is not already associated
            $this->collUsers->push($user);
            $this->doAddUser($user);
        }

        return $this;
    }

    /**
     *
     * @param ChildUser $user
     */
    protected function doAddUser(ChildUser $user)
    {
        $usersDomain = new ChildUsersDomain();

        $usersDomain->setUser($user);

        $usersDomain->setDomain($this);

        $this->addUsersDomain($usersDomain);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isDomainsLoaded()) {
            $user->initDomains();
            $user->getDomains()->push($this);
        } elseif (!$user->getDomains()->contains($this)) {
            $user->getDomains()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the users_domain cross reference table.
     *
     * @param ChildUser $user
     * @return ChildDomain The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) {
            $usersDomain = new ChildUsersDomain();

            $usersDomain->setUser($user);
            if ($user->isDomainsLoaded()) {
                //remove the back reference if available
                $user->getDomains()->removeObject($this);
            }

            $usersDomain->setDomain($this);
            $this->removeUsersDomain(clone $usersDomain);
            $usersDomain->clear();

            $this->collUsers->remove($this->collUsers->search($user));

            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }

            $this->usersScheduledForDeletion->push($user);
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
        if (null !== $this->aAccount) {
            $this->aAccount->removeDomain($this);
        }
        $this->id = null;
        $this->account_id = null;
        $this->uri = null;
        $this->status = null;
        $this->is_enabled = null;
        $this->last_checked = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collUsersDomains) {
                foreach ($this->collUsersDomains as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTests) {
                foreach ($this->collTests as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collUsersDomains = null;
        $this->collTests = null;
        $this->collUsers = null;
        $this->aAccount = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->exportTo(DomainTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildDomain The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[DomainTableMap::COL_UPDATED_AT] = true;

        return $this;
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
