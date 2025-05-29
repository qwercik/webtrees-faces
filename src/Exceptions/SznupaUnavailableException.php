<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Exceptions;

use Fisharebest\Webtrees\I18N;

class SznupaUnavailableException extends SznupaException
{
    public function __construct()
    {
        parent::__construct(I18N::translate('LBL_SZNUPA_UNAVAILABLE'));
    }
}
