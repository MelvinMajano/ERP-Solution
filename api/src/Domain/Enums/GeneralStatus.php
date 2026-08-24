<?php

namespace Domain\Enums;

enum GeneralStatus: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'inactive';
}