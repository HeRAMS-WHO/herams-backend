<?php


namespace prime\components;


use prime\interfaces\FacilityListInterface;

class Serializer extends \yii\rest\Serializer
{
    public function serialize($data)
    {
        if ($data instanceof FacilityListInterface) {
            return $this->serializeFacilities($data);
        }
        return parent::serialize($data);
    }

    protected function serializeFacilities(FacilityListInterface $data)
    {
        $result = [];
        for($i = 0; $i < $data->getLength(); $i++) {
            $result[] = $data->get($i);
        }

        return $this->serialize($result);

    }


}