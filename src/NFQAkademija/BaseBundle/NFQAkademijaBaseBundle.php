<?php

namespace NFQAkademija\BaseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NFQAkademijaBaseBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
