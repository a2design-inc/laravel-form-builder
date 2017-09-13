<?php

use Illuminate\Database\Eloquent\Model;

/**
 * Class TestModel
 *
 * For model mocking
 */
class TestEntity extends Model
{
    public $fieldWithValue = 'some-value';
    public $fieldWithOld = 'some-value';
}