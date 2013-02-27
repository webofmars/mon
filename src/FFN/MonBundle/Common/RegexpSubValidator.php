<?php

namespace FFN\MonBundle\Common;

/**
 * RegexpSubValidator
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
class RegexpSubValidator implements SubValidator {
    
    public function getName() {
        return get_class($this);
    }
    
    
    public function validate($criteria, $content) {
        
        if (preg_match($criteria, $content) === 1) {
            return true;
        }
        return false;
    }
}
