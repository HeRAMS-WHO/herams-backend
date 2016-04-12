<?php

namespace prime\objects;

class RGBColor extends \Primal\Color\RGBColor
{
    public function __toString()
    {
        return $this->toCSS($alpha = null);
    }

    public function lighten($percent)
    {
        $result = $this->toHSL();

        $result->luminance = min(100, $result->luminance + $percent);

        $result = $result->toRGB();
        return new self($result->red, $result->green, $result->blue, $result->alpha);
    }
}