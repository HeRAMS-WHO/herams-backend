<?php


namespace prime\interfaces;


interface Exportable
{

    public function export(): array;

    public static function import($parent, array $data);
}