<?php

namespace Mundipagg\Core\Kernel\Helper;

use ReflectionClass;
use ReflectionException;

class Hydrator
{
    /**
     * @param $array
     * @param $obj
     * @return mixed
     * @throws ReflectionException
     */
    public static function hidrator($array, $obj)
    {
        if (!is_array($array)) {
            return $obj;
        }

        $reflection = new ReflectionClass($obj);

        foreach ($array as $key => $value) {
            $propertyFind = self::findProperty($reflection, $key);

            if ($propertyFind === null) {
                continue;
            }

            $reflectionProperty = $reflection->getProperty($propertyFind);

            if ($reflectionProperty->isPrivate() || $reflectionProperty->isProtected()) {
                $reflectionProperty->setAccessible(true);
            }

            $reflectionProperty->setValue($obj, $value);

            if ($reflectionProperty->isPrivate() || $reflectionProperty->isProtected()) {
                $reflectionProperty->setAccessible(false);
            }
        }
        return $obj;
    }

    public static function findProperty(ReflectionClass $reflection, $propertyName)
    {
        if ($reflection->hasProperty($propertyName)) {
            return $propertyName;
        }

        if ($reflection->hasProperty(self::dashesToCamelCase($propertyName))) {
            return self::dashesToCamelCase($propertyName);
        }

        if ($reflection->hasProperty(self::underToCamelCase($propertyName))) {
            return self::underToCamelCase($propertyName);
        }

        return null;
    }

    private static function underToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('_', '', ucwords($string, '_'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    private static function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace('-', '', ucwords($string, '-'));

        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        return $str;
    }

    /**
     * @param object $obj
     * @return array
     * @throws ReflectionException
     */
    public static function extract($obj)
    {
        $reflection = new ReflectionClass($obj);

        $array = array();

        foreach ($reflection->getProperties() as $prop) {
            $accessible = !$prop->isPrivate();
            $prop->setAccessible(true);
            $array[$prop->getName()] = $prop->getValue($obj);
            $prop->setAccessible($accessible);
        }

        return $array;
    }

    /**
     * @param object $obj
     * @return array
     * @throws ReflectionException
     */
    public static function extractRecursive($obj)
    {
        $reflection = new ReflectionClass($obj);

        $array = [];
        foreach ($reflection->getProperties() as $prop) {
            $accessible = !$prop->isPrivate();
            $prop->setAccessible(true);

            $value = $prop->getValue($obj);

            if (is_object($value)) {
                $value = self::extractRecursive($value);
            }

            $array[$prop->getName()] = $value;
            $prop->setAccessible($accessible);
        }

        return $array;
    }
}
