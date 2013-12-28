<?php

namespace FFN\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FFNUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
