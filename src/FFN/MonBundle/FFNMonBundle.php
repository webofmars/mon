<?php

namespace FFN\MonBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FFNMonBundle extends Bundle
{
  public function getParent(){
    return 'FOSUserBundle';
  }
}
