<?php

namespace frontend\components;

trait ModelTrait
{

    public function attributes () {
        $attributes = [];
        foreach (self::rules() as $d) {
            if (is_string(reset($d))) continue;
            foreach (reset($d) as $k) $attributes[$k] = $k;
        };
        return array_values($attributes);
    }

};
