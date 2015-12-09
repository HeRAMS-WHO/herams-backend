<?php


namespace prime\injection;


trait SetterInjectionTrait
{
    private $locked = false;

    /**
     * @inheritdoc
     */
    public static function listDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function listStaticDependencies()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function listOptionalDependencies()
    {
        return [];
    }

    public function init()
    {
        $this->locked = true;
        parent::init();
    }

    /**
     * Throws an exception if the object is locked.
     * Helper function that can be used by setters.
     */
    protected function locked() {
        if ($this->locked) {
            throw new \BadMethodCallException("This objects' dependencies are locked.");
        }
    }

    /**
     * Pull in dependencies using the given container. This allows for easier usage in case pure DI cannot be used.
     */

    public function injectFrom(Container $container)
    {
        foreach($this->listDependencies() as $setter => $class) {
            $this->$setter($container->get($class));
        }
    }

}