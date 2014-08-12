<?php
/**
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @date 12.08.2014
 */

namespace opus\enum;

use yii\helpers\Inflector;

/**
 * Abstract enumeration class
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\enum
 */
abstract class AbstractEnum
{
    /**
     * Returns all class constants
     * @return array
     */
    public static function getList()
    {
        $reflection = new \ReflectionClass(get_called_class());
        $constants = $reflection->getConstants();
        return $constants;
    }

    /**
     * Returns all constants as value => label
     * @return array
     */
    public static function getListLabels()
    {
        $list = [];
        foreach (static::getList() as $key => $value) {
            $list[$value] = static::getLabel($key);
        }
        return $list;
    }

    /**
     * Converts a string value of a constant (or any value) into a more
     * human-readable format
     *
     * @param string $constant
     * @return string
     */
    public static function getLabel($constant)
    {
        return Inflector::humanize(lcfirst(strtolower($constant)));
    }
} 
