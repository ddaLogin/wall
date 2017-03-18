<?php
/**
 * Created by PhpStorm.
 * User: Денисов Данила
 * Date: 18.03.2017
 * Time: 15:36
 */

namespace app\Interfaces;


interface Validatable
{
    /**
     * return validation rules
     * @return array
     */
    public function rules();
}