<?php
declare(strict_types=1);

namespace prime\components;

use Box\Spout\Common\Entity\Style\Style;

class StyleRegistry extends \Box\Spout\Writer\XLSX\Manager\Style\StyleRegistry
{
    private $default;
    public function registerStyle(Style $style)
    {
        if (!isset($this->default)) {
            $this->default = parent::registerStyle($style)
            ;
        }
        return $this->default;
    }
}
