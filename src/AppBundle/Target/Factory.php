<?php

namespace AppBundle\Target;

use AppBundle\Exception\TargetNotExistsException;

class Factory {
    /**
     * @param string $target
     * @param array  $arguments
     *
     * @throws TargetNotExistsException
     *
     * @return TargetInterface
     */
    public static function factory($target = '', $arguments = array()) {
        $className = '\\AppBundle\\Target\\'.ucfirst($target);
        if (class_exists($className)) {
            return new $className($arguments);
        }
        throw new TargetNotExistsException($target.' not implemented');
    }
}
