<?php

declare(strict_types=1);

namespace herams\common\enums;

enum HydrateSource
{
    case webForm;
    case json;
    case database;
}
