<?php

namespace AppBundle\Target;

use AppBundle\Exception\TargetNotExistsException;

class Factory {

    /**
     * @param string $target
     * @param array $arguments
     * @return TargetInterface
     * @throws TargetNotExistsException
     */
    public static function factory($target='', $arguments=[])
    {
        $className = '\\AppBundle\\Target\\' . ucfirst($target);
        if (class_exists($className)) {
            return new $className($arguments);
        }
        throw new TargetNotExistsException($target . ' not implemented');
    }
}