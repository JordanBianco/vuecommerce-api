<?php

namespace Database\Factories\Helper;

class FactoryHelper
{
    public static function getRandomModelId($model)
    {
        $count = $model::count();

        if ($count === 0) {
            $id = $model::factory()->create()->id;
        } else {
            $id = rand(1, $count);
        }

        return $id;
    }
}