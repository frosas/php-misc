<?php

namespace Frosas;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Property
{
    static function getter($propertyPath)
    {
        $accessor = PropertyAccess::getPropertyAccessor();
        return function($element) use ($accessor, $propertyPath) {
            return $accessor->getValue($element, $propertyPath);
        };
    }

    static function finder($propertyPath)
    {
        $getter = static::getter($propertyPath);
        return function($element) use ($getter) {
            try {
                return $getter($element);
            } catch (Property\NoSuchPropertyException $e) {
            }
        };
    }
}
