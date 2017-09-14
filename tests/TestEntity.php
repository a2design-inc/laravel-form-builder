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
    public $fieldTrue = true;
    public $fieldFalse = false;
    public $fieldTrueString = '1';
    public $fieldFalseString = '0';
}