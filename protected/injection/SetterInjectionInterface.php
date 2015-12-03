<?php


namespace prime\injection;


interface SetterInjectionInterface
{

    /**
     * @return array  An array of setter => Class / Interfaces that objects of this class depend on.
     */
    public static function listDependencies();

    /**
     * @return array  An array of setter => Class / Interfaces that static functions of this class depend on.
     */
    public static function listStaticDependencies();

    /**
     * @return array An array of setter => Class / Interfaces that objects of this class can use.
     */
    public static function listOptionalDependencies();

    /**
     * This function is called after all dependencies have been injected.
     * After this has been called all setters must no longer be callable.
     * @return void
     */
    public function init();

}