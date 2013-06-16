<?php

namespace m2miageGre\energyProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use m2miageGre\energyProjectBundle\Model\Capteur;
use m2miageGre\energyProjectBundle\Model\CapteurPeer;
use m2miageGre\energyProjectBundle\Model\CapteurQuery;
use m2miageGre\energyProjectBundle\Model\HouseHold;
use m2miageGre\energyProjectBundle\Model\HouseHoldQuery;
use m2miageGre\energyProjectBundle\Model\Mesure;
use m2miageGre\energyProjectBundle\Model\MesureQuery;

abstract class BaseCapteur extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'm2miageGre\\energyProjectBundle\\Model\\CapteurPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CapteurPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinit loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the capteur_name field.
     * @var        string
     */
    protected $capteur_name;

    /**
     * The value for the version field.
     * @var        string
     */
    protected $version;

    /**
     * The value for the household_id field.
     * @var        int
     */
    protected $household_id;

    /**
     * @var        HouseHold
     */
    protected $aHouseHold;

    /**
     * @var        PropelObjectCollection|Mesure[] Collection to store aggregation of Mesure objects.
     */
    protected $collMesures;
    protected $collMesuresPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $mesuresScheduledForDeletion = null;

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
     * Get the [capteur_name] column value.
     *
     * @return string
     */
    public function getCapteurName()
    {
        return $this->capteur_name;
    }

    /**
     * Get the [version] column value.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get the [household_id] column value.
     *
     * @return int
     */
    public function getHouseholdId()
    {
        return $this->household_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return Capteur The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CapteurPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [capteur_name] column.
     *
     * @param string $v new value
     * @return Capteur The current object (for fluent API support)
     */
    public function setCapteurName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->capteur_name !== $v) {
            $this->capteur_name = $v;
            $this->modifiedColumns[] = CapteurPeer::CAPTEUR_NAME;
        }


        return $this;
    } // setCapteurName()

    /**
     * Set the value of [version] column.
     *
     * @param string $v new value
     * @return Capteur The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[] = CapteurPeer::VERSION;
        }


        return $this;
    } // setVersion()

    /**
     * Set the value of [household_id] column.
     *
     * @param int $v new value
     * @return Capteur The current object (for fluent API support)
     */
    public function setHouseholdId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->household_id !== $v) {
            $this->household_id = $v;
            $this->modifiedColumns[] = CapteurPeer::HOUSEHOLD_ID;
        }

        if ($this->aHouseHold !== null && $this->aHouseHold->getId() !== $v) {
            $this->aHouseHold = null;
        }


        return $this;
    } // setHouseholdId()

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
        // otherwise, everything was equal, so return true
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
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->capteur_name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->version = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->household_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            return $startcol + 4; // 4 = CapteurPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Capteur object", $e);
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

        if ($this->aHouseHold !== null && $this->household_id !== $this->aHouseHold->getId()) {
            $this->aHouseHold = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CapteurPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CapteurPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aHouseHold = null;
            $this->collMesures = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CapteurPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CapteurQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CapteurPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
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
                CapteurPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aHouseHold !== null) {
                if ($this->aHouseHold->isModified() || $this->aHouseHold->isNew()) {
                    $affectedRows += $this->aHouseHold->save($con);
                }
                $this->setHouseHold($this->aHouseHold);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->mesuresScheduledForDeletion !== null) {
                if (!$this->mesuresScheduledForDeletion->isEmpty()) {
                    MesureQuery::create()
                        ->filterByPrimaryKeys($this->mesuresScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->mesuresScheduledForDeletion = null;
                }
            }

            if ($this->collMesures !== null) {
                foreach ($this->collMesures as $referrerFK) {
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
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CapteurPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CapteurPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CapteurPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CapteurPeer::CAPTEUR_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`capteur_name`';
        }
        if ($this->isColumnModified(CapteurPeer::VERSION)) {
            $modifiedColumns[':p' . $index++]  = '`version`';
        }
        if ($this->isColumnModified(CapteurPeer::HOUSEHOLD_ID)) {
            $modifiedColumns[':p' . $index++]  = '`household_id`';
        }

        $sql = sprintf(
            'INSERT INTO `capteur` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`capteur_name`':
                        $stmt->bindValue($identifier, $this->capteur_name, PDO::PARAM_STR);
                        break;
                    case '`version`':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_STR);
                        break;
                    case '`household_id`':
                        $stmt->bindValue($identifier, $this->household_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggreagated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their coresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aHouseHold !== null) {
                if (!$this->aHouseHold->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aHouseHold->getValidationFailures());
                }
            }


            if (($retval = CapteurPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collMesures !== null) {
                    foreach ($this->collMesures as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CapteurPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCapteurName();
                break;
            case 2:
                return $this->getVersion();
                break;
            case 3:
                return $this->getHouseholdId();
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
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Capteur'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Capteur'][$this->getPrimaryKey()] = true;
        $keys = CapteurPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCapteurName(),
            $keys[2] => $this->getVersion(),
            $keys[3] => $this->getHouseholdId(),
        );
        if ($includeForeignObjects) {
            if (null !== $this->aHouseHold) {
                $result['HouseHold'] = $this->aHouseHold->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collMesures) {
                $result['Mesures'] = $this->collMesures->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CapteurPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCapteurName($value);
                break;
            case 2:
                $this->setVersion($value);
                break;
            case 3:
                $this->setHouseholdId($value);
                break;
        } // switch()
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
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CapteurPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCapteurName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setVersion($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setHouseholdId($arr[$keys[3]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CapteurPeer::DATABASE_NAME);

        if ($this->isColumnModified(CapteurPeer::ID)) $criteria->add(CapteurPeer::ID, $this->id);
        if ($this->isColumnModified(CapteurPeer::CAPTEUR_NAME)) $criteria->add(CapteurPeer::CAPTEUR_NAME, $this->capteur_name);
        if ($this->isColumnModified(CapteurPeer::VERSION)) $criteria->add(CapteurPeer::VERSION, $this->version);
        if ($this->isColumnModified(CapteurPeer::HOUSEHOLD_ID)) $criteria->add(CapteurPeer::HOUSEHOLD_ID, $this->household_id);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CapteurPeer::DATABASE_NAME);
        $criteria->add(CapteurPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
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
     * @param object $copyObj An object of Capteur (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCapteurName($this->getCapteurName());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setHouseholdId($this->getHouseholdId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getMesures() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMesure($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
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
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Capteur Clone of current object.
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
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CapteurPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CapteurPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a HouseHold object.
     *
     * @param             HouseHold $v
     * @return Capteur The current object (for fluent API support)
     * @throws PropelException
     */
    public function setHouseHold(HouseHold $v = null)
    {
        if ($v === null) {
            $this->setHouseholdId(NULL);
        } else {
            $this->setHouseholdId($v->getId());
        }

        $this->aHouseHold = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the HouseHold object, it will not be re-added.
        if ($v !== null) {
            $v->addCapteur($this);
        }


        return $this;
    }


    /**
     * Get the associated HouseHold object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return HouseHold The associated HouseHold object.
     * @throws PropelException
     */
    public function getHouseHold(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aHouseHold === null && ($this->household_id !== null) && $doQuery) {
            $this->aHouseHold = HouseHoldQuery::create()->findPk($this->household_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aHouseHold->addCapteurs($this);
             */
        }

        return $this->aHouseHold;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Mesure' == $relationName) {
            $this->initMesures();
        }
    }

    /**
     * Clears out the collMesures collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Capteur The current object (for fluent API support)
     * @see        addMesures()
     */
    public function clearMesures()
    {
        $this->collMesures = null; // important to set this to null since that means it is uninitialized
        $this->collMesuresPartial = null;

        return $this;
    }

    /**
     * reset is the collMesures collection loaded partially
     *
     * @return void
     */
    public function resetPartialMesures($v = true)
    {
        $this->collMesuresPartial = $v;
    }

    /**
     * Initializes the collMesures collection.
     *
     * By default this just sets the collMesures collection to an empty array (like clearcollMesures());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMesures($overrideExisting = true)
    {
        if (null !== $this->collMesures && !$overrideExisting) {
            return;
        }
        $this->collMesures = new PropelObjectCollection();
        $this->collMesures->setModel('Mesure');
    }

    /**
     * Gets an array of Mesure objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Capteur is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Mesure[] List of Mesure objects
     * @throws PropelException
     */
    public function getMesures($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMesuresPartial && !$this->isNew();
        if (null === $this->collMesures || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMesures) {
                // return empty collection
                $this->initMesures();
            } else {
                $collMesures = MesureQuery::create(null, $criteria)
                    ->filterByCapteur($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMesuresPartial && count($collMesures)) {
                      $this->initMesures(false);

                      foreach($collMesures as $obj) {
                        if (false == $this->collMesures->contains($obj)) {
                          $this->collMesures->append($obj);
                        }
                      }

                      $this->collMesuresPartial = true;
                    }

                    $collMesures->getInternalIterator()->rewind();
                    return $collMesures;
                }

                if($partial && $this->collMesures) {
                    foreach($this->collMesures as $obj) {
                        if($obj->isNew()) {
                            $collMesures[] = $obj;
                        }
                    }
                }

                $this->collMesures = $collMesures;
                $this->collMesuresPartial = false;
            }
        }

        return $this->collMesures;
    }

    /**
     * Sets a collection of Mesure objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $mesures A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Capteur The current object (for fluent API support)
     */
    public function setMesures(PropelCollection $mesures, PropelPDO $con = null)
    {
        $mesuresToDelete = $this->getMesures(new Criteria(), $con)->diff($mesures);

        $this->mesuresScheduledForDeletion = unserialize(serialize($mesuresToDelete));

        foreach ($mesuresToDelete as $mesureRemoved) {
            $mesureRemoved->setCapteur(null);
        }

        $this->collMesures = null;
        foreach ($mesures as $mesure) {
            $this->addMesure($mesure);
        }

        $this->collMesures = $mesures;
        $this->collMesuresPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Mesure objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Mesure objects.
     * @throws PropelException
     */
    public function countMesures(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMesuresPartial && !$this->isNew();
        if (null === $this->collMesures || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMesures) {
                return 0;
            }

            if($partial && !$criteria) {
                return count($this->getMesures());
            }
            $query = MesureQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCapteur($this)
                ->count($con);
        }

        return count($this->collMesures);
    }

    /**
     * Method called to associate a Mesure object to this object
     * through the Mesure foreign key attribute.
     *
     * @param    Mesure $l Mesure
     * @return Capteur The current object (for fluent API support)
     */
    public function addMesure(Mesure $l)
    {
        if ($this->collMesures === null) {
            $this->initMesures();
            $this->collMesuresPartial = true;
        }
        if (!in_array($l, $this->collMesures->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMesure($l);
        }

        return $this;
    }

    /**
     * @param	Mesure $mesure The mesure object to add.
     */
    protected function doAddMesure($mesure)
    {
        $this->collMesures[]= $mesure;
        $mesure->setCapteur($this);
    }

    /**
     * @param	Mesure $mesure The mesure object to remove.
     * @return Capteur The current object (for fluent API support)
     */
    public function removeMesure($mesure)
    {
        if ($this->getMesures()->contains($mesure)) {
            $this->collMesures->remove($this->collMesures->search($mesure));
            if (null === $this->mesuresScheduledForDeletion) {
                $this->mesuresScheduledForDeletion = clone $this->collMesures;
                $this->mesuresScheduledForDeletion->clear();
            }
            $this->mesuresScheduledForDeletion[]= clone $mesure;
            $mesure->setCapteur(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->capteur_name = null;
        $this->version = null;
        $this->household_id = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volumne/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collMesures) {
                foreach ($this->collMesures as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aHouseHold instanceof Persistent) {
              $this->aHouseHold->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collMesures instanceof PropelCollection) {
            $this->collMesures->clearIterator();
        }
        $this->collMesures = null;
        $this->aHouseHold = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CapteurPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

}
