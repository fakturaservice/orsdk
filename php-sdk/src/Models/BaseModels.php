<?php
//declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: twl
 * Date: 04-05-2017
 * Time: 10:54
 */

namespace OrSdk\Models;

use InvalidArgumentException;

use OrSdk\Util\BasicEnum;
use OrSdk\Util\dataType;
use OrSdk\Util\ORException;

/**
 * Class BaseModels
 * @package Models
 */
abstract class BaseModels
{
    public $limit;
    public $orderBy;
    public $logical;
    public $aliasMap;
    public $allowed;

    /**
     * BaseDbModels constructor.
     * @param array|null $values
     */
    public function __construct(array $values=null)
    {
        if(isset($values))
            $this->setValues($values);
    }

    /**
     * @return void
     */
    public function empty()
    {
        foreach ($this as $key => $value)
        {
            $value = null;
            $this->$key = $value;
        }
    }
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        foreach ($this as $value)
        {
            if(isset($value))
                return false;
        }
        return true;
    }

    /**
     * @param bool $inverted
     * @param bool $associate
     * @param bool $strict
     * @return array
     */
    public function getEmptyKeys(bool $inverted=false, bool $associate = true, bool $strict=false): array
    {
        $res = array();
        if($strict)
        {
            foreach ($this as $key => $value)
            {
                if(empty($value) !== $inverted)
                {
                    $res[] = ($associate)?$key:$this->getKeyIdx($key);
                }
            }
        }
        else
        {
            foreach ($this as $key => $value)
            {
                if(isset($value) === $inverted)
                {
                    $res[] = ($associate)?$key:$this->getKeyIdx($key);
                }
            }
        }
        return $res;
    }
    
    /**
     * @param bool $snakeCase
     * @return mixed|string
     */
    public function getModelName(bool $snakeCase=false)
    {
        $name       = get_called_class();
        $tmpNames   = explode("\\", $name);
        return ($snakeCase)?$this->camelToSnake(end($tmpNames)):end($tmpNames);
    }

    /**
     * @param bool $snakeCase
     * @return string
     */
    public function getApiName(bool $snakeCase=false): string
    {
        $name       = get_called_class();
        $tmpNames   = explode("\\", $name);
        
        $modName    = ($snakeCase)?$this->camelToSnake(array_pop($tmpNames)):array_pop($tmpNames);
        $namespace  = ($snakeCase)?$this->camelToSnake(array_pop($tmpNames)):array_pop($tmpNames);
        $modType    = ($snakeCase)?$this->camelToSnake(array_pop($tmpNames)):array_pop($tmpNames);
        return $this->camelToSnake("$modType/$modName");
    }
    /**
     * @param string $camelName
     * @param int $matchModel
     * @return string
     */
    public function camelToSnake(string $camelName, int $matchModel = 0) : string
    {
        // camelcase (lower or upper) to underscored
        // e.g. "thisMethodName" -> "this_method_name"
        // e.g. "ThisMethodName" -> "this_method_name"
        if(($matchModel > 0) && (!$this->hasKey($camelName)))
        {
            foreach ($this->getKeyNames() as $keyName)
            {
                similar_text($keyName, $camelName, $p);
                if($p >= $matchModel)
                {
                    $camelName = $keyName;
                    break;
                }
            }
        }
        return strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $camelName));
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key) : bool
    {
        if(is_int($key))
            $key = $this->getKeyName($key);
        return property_exists($this, $key);
    }

    /**
     * @return int
     */
    public function numOfKeys() : int
    {
        return count(array_keys((array)$this));
    }
    
    public function sanitize($val, $type)
    {
        switch($type)
        {
            case dataType::INT:
            case dataType::TINYINT:    return "".(int)round($val);

            case dataType::TINYTEXT:
            case dataType::VARCHAR:
            case dataType::CHAR:
            case dataType::MEDIUMTEXT:
            case dataType::TEXT:       return trim($val);


            case dataType::DATE:       //{try{$dateVal = new \DateTime($val); return $dateVal->format("Y-m-d");}catch(\ORException $e){return null;}}
            case dataType::TIMESTAMP:  //{try{$dateVal = new \DateTime($val); return $dateVal->getTimestamp();}catch(\ORException $e){return null;}}
            case dataType::DATETIME:   //{try{$dateVal = new \DateTime($val); return $dateVal->format("Y-m-d m:i:s");}catch(\ORException $e){return null;}}

            case dataType::ENUM:

            case dataType::ANYTYPE:
            case dataType::BLOB:
            case dataType::LONGBLOB:
            case dataType::DECIMAL:
            case dataType::NULL:
            default:                     return $val;
        }
    }
    /**
     * @param $key
     * @param $val
     */
    public function setValue($key, $val)
    {
        if(is_int($key))
            $key = $this->getKeyName($key);
        if(!$this->hasKey($key))
            throw new InvalidArgumentException("Key does not exist [$key]");
//        $val = $this->sanitize($val, $this->getType($key));
        $this->$key = $val;
    }

    /**
     * @param array $val
     */
    public function setValues(array $val)
    {
        foreach ($this as $key => $value) {
            if(!array_key_exists($key, $val)) continue;
            $value = $val[$key];
//            $value = $this->sanitize($value, $this->getType($key));
            $this->$key = $value;
        }
    }

    /**
     * @param bool $associative
     * @return mixed
     * @throws ORException
     */
    public function getTypes(bool $associative=true)
    {
        $modelName = get_called_class();
        /** @var BasicEnum $enumTypeClassName */
        $classNameStr = $enumTypeClassName = "{$modelName}Types";

        try{
            $types = $enumTypeClassName::getArray($associative);
        } catch (\Exception $e)
        {
            throw new ORException("Class $classNameStr wasn't found", ORException::CH_ALL, ORException::LV_FATAL, 0, $e);
        }

        return $types;
    }

    /**
     * @param $idx
     * @return mixed
     * @throws ORException
     */
    public function getType($idx)
    {
        if(is_string($idx) && $this->hasKey($idx))
            $idx = $this->getKeyIdx($idx);
        return $this->getTypes(false)[$idx];
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        if(is_int($key))
            $key = $this->getKeyName($key);
        if(!$this->hasKey($key))
            throw new InvalidArgumentException("Key does not exist [$key]");
        return $this->$key;
    }

    /**
     * @param bool $associate
     * @param int $offset
     * @param int|null $length
     * @param array|null $exclude
     * @return array
     */
    public function getValues(bool $associate = false, int $offset = 0, int $length = null, array $exclude=null) : array
    {
        if($associate)
            $thisArray = (array)$this;
        else
            $thisArray = array_values((array)$this);

        $values = array_slice($thisArray, $offset, $length, true);
        if(isset($exclude))
        {
            foreach ($exclude as $item)
            {
                if(!$this->hasKey($item))
                    continue; // $item = $this->getKeyIdx($item);
                if(is_int($item))
                    $item = $this->getKeyName($item);
                unset($values[$item]);
            }
        }
        return $values;
    }

    /**
     * @param int $idx
     * @return mixed
     */
    public function getKeyName(int $idx) : string
    {
        if(($idx >= count((array)$this)) || ($idx < 0))
            throw new InvalidArgumentException("Index is out of range [$idx]");
        $thisKeyArr = array_keys((array)$this);
        return $thisKeyArr[$idx];
    }

    /**
     * @param string $key
     * @return int
     */
    public function getKeyIdx(string $key) : int
    {
        if(!property_exists($this, $key))
            throw new InvalidArgumentException("Key does not exist [$key]");
        $idx = array_search($key,array_keys((array)$this));
        if($idx === false)
            throw new InvalidArgumentException("Key does not exist [$key]");
        return $idx;
    }

    /**
     * @param int $offset
     * @param int|null $length
     * @param array|null $exclude
     * @return array
     */
    public function getKeyNames(int $offset = 0, int $length = null, array $exclude=null) : array
    {
        $cols = array_slice(array_keys((array)$this), $offset, $length, true);
        if(isset($exclude))
        {
            foreach ($exclude as $item)
            {
                //It's unlikely the user will exclude with associate array - it's just a precaution.
                if(is_string($item) && $this->hasKey($item))
                    $item = $this->getKeyIdx($item);
                unset($cols[$item]);
            }
        }
        return $cols;
    }

    /**
     * @param array $arrayIdx
     * @return array
     */
    public function getKeyNamesByIdxArr(array $arrayIdx) : array
    {
        $keys = array();
        foreach ($arrayIdx as $idx)
        {
            $keys[] = $this->getKeyName($idx);
        }
        return $keys;
    }

    /**
     * @param null $empty
     * @param array|null $subSetKeys
     */
    public function setAllEmptyTo($empty=null, array $subSetKeys=null){
        $thisArray = (array)$this;

        foreach ($thisArray as $key => $value)
        {
            if(isset($subSetKeys))
                if(!in_array($key, $subSetKeys)) continue;
            if(empty($value ))
                $this->$key = $empty;
        }
    }
    
    /**
     * Convert values containing the string 'null' to actually null
     *
     * @param int $offset
     * @param int|null $length
     * @param array|null $exclude
     */
    public function convertToNull(int $offset = 0, int $length = null, array $exclude=null)
    {
        $vals = $this->getValues(true, $offset,$length, $exclude);
        foreach ($vals as $key => $val)
        {
            if(strtolower($val) == "null")
            {
                $this->setValue($key, null);
            }
        }
    }

}


/**
 * Class OrderDirection
 * @package Models
 */
abstract class OrderDirection
{
    const asc = "asc";
    const desc = "desc";
}
