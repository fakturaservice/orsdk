<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved 
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

/**
 * Created by PhpStorm.
 * User: twl
 * Date: 01-09-2017
 * Time: 08:29
 */
namespace OrSdk\Util;


use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

abstract class BasicEnum {
    private static $constCacheArray = NULL;

    /**
     * @return mixed
     * @throws ORException
     */
    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            try{
                $reflect = new ReflectionClass($calledClass);
                self::$constCacheArray[$calledClass] = $reflect->getConstants();
            }
            catch (ReflectionException $e){
                throw new ORException(
                    $e->getMessage(),
                    ORException::CH_ALL,
                    ORException::LV_FATAL,
                    $e->getCode()
                );
            }

        }
        return self::$constCacheArray[$calledClass];
    }

    /**
     * @param      $name
     * @param bool $strict
     *
     * @return bool
     * @throws ORException
     */
    public static function hasKey($name, bool $strict = false): bool
    {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    /**
     * @param      $value
     * @param bool $strict
     *
     * @return bool
     * @throws ORException
     */
    public static function hasValue($value, bool $strict = true): bool
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }

    /**
     * @return int
     * @throws ORException
     */
    public static function countConstants(): int
    {
        return count(self::getConstants());
    }

    /**
     * @param $i
     *
     * @return mixed
     * @throws ORException
     */
    public static function getValByIndex($i) {
        $constants = self::getConstants();
        $keys = array_keys($constants);
        return $constants[$keys[$i]];
    }

    /*** No set function!!! Remember, this is an static enum class! **
     *
     * @param $i
     *
     * @return int|string
     * @throws ORException
     */

    public static function getKeyByIndex($i) {

        if( ($i >= self::countConstants())
            || ($i < 0))
        {
            throw new InvalidArgumentException("Index out of range [$i]");
        }
        $constants = self::getConstants();
        $keys = array_keys($constants);
        return $keys[$i];
    }

    /**
     * @return array
     * @throws ORException
     */
    public static function getAllKeys(): array
    {
        $constants = self::getConstants();
        return array_keys($constants);
    }

    /**
     * @param bool $associative
     *
     * @return array|mixed
     * @throws ORException
     */
    public static function getArray(bool $associative = false) {
        $array = self::getConstants();
        if(!$associative)
            $array = array_values($array);
        return $array;
    }
}