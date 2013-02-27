<?php

namespace FFN\MonBundle\Common;

/**
 * RegexpSubValidator
 *
 * @author Frederic Leger <leger.frederic@gmail.com>
 */
interface SubValidator {
    public function getName();
    public function validate($criteria, $content);
}