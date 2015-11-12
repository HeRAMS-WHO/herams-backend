<?php

namespace prime\traits;

use yii\helpers\ArrayHelper;

trait JsonMemoryDataSourceTrait
{
    private static $_jsonMemoryData;

    public static function instantiate($row)
    {
        return new static();
    }

    public static function findOne($key)
    {
        self::loadData();
        $result = static::instantiate(self::$_jsonMemoryData[$key]);
        static::populateRecord($result, self::$_jsonMemoryData[$key]);
        return $result;
    }

    public static function findAll()
    {
        self::loadData();
        $result = [];
        foreach(self::$_jsonMemoryData as $key => $dummy) {
            $result[] = static::findOne($key);
        }
        return $result;
    }

    public static function populateRecord($record, $row)
    {
        foreach ($row as $name => $value) {
            if ($record->canSetProperty($name)) {
                $record->$name = $value;
            }
        }
    }

    protected static function loadData()
    {
        if(!isset(self::$_jsonMemoryData)) {
            $source = static::getSource();

            $data = json_decode(file_get_contents(\Yii::getAlias($source['file'])), true);
            if (isset($source['dataPath'])) {
                $data = ArrayHelper::getValue($data, $source['dataPath']);
            }

            self::$_jsonMemoryData = [];
            foreach ($data as $row) {

                if (isset($source['attributeMap'])) {
                    $rowData = [];
                    foreach ($source['attributeMap'] as $attribute => $path) {
                        $rowData[$attribute] = ArrayHelper::getValue($row, $path);
                    }
                } else {
                    $rowData = $row;
                }
                self::$_jsonMemoryData[ArrayHelper::getValue($row, $source['keyPath'])] = $rowData;
            }
        }
    }

    /**
     * @return array
     * [
     *  'file' => (string) full file path,
     *  'keyPath' => (string) the path to the key inside a row
     *  'dataPath' => (string, optional) the path to the rows in the data,
     *  'attributeMap' => (array, optional) map from attribute name to attribute path in row
     * ]
     */
    abstract protected static function getSource();

    protected function getCache()
    {

    }
}