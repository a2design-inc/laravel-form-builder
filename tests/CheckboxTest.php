<?php

require_once 'FormBuilderTestBase.php';

class CheckboxTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with checkbox';

        $checkbox = $this->formBuilder->checkbox('testName');
        $this->assertContains('<input', $checkbox, $message);
        $this->assertContains('type="checkbox"', $checkbox, $message);
        $this->assertContains('name="testName"', $checkbox, $message);
        $this->assertContains('value="1"', $checkbox, $message);
        $this->assertContains('value="0"', $checkbox, $message);
    }

    public function testErrors()
    {
        $message = 'Some issue with validation errors in checkbox';

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['error' => 'Test error message']);
        $this->assertContains('Test error message', $checkbox, $message);

        $this->setError('testName', 'Test error message');

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertContains('Test error message', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['error' => false]);
        $this->assertNotContains('Test error message', $checkbox, $message);

        $this->setError('testName', 'Test error message 2');
        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertNotContains('Test error message 2', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['all-errors' => true]);
        $this->assertContains('Test error message 2', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', [
            'error-form-group-class' => 'test-error-form-group-class'
        ]);
        $this->assertContains('error-form-group-class', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['error-form-group-class' => 'test-error-class']);
        $this->assertContains('test-error-class', $checkbox, $message);

        $this->setConfigValueStub('error_form_group_class', 'config-test-error-form-group-class');
        $this->setConfigValueStub('error_class', 'config-test-error-class');

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertContains('config-test-error-class', $checkbox, $message);
        $this->assertContains('config-test-error-form-group-class', $checkbox, $message);

        $this->resetConfig();
        //reset errors
        $this->resetViewFactory();
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" checkbox parameter';

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $checkbox, $message);
    }

    public function testId()
    {
        $message = 'Some issue with checkbox id';

        $this->setConfigValueStub('generate_id', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        //controller method - entity name - field name
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $checkbox, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertNotContains('id="get-without-route-name-test-entity-test-name"', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $checkbox, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testCommoncheckboxAttributes()
    {
        $message = 'Some issue with attribute ';
        $attrs = ['required', 'readonly', 'disabled', 'autofocus', 'checked'];
        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {
            $checkbox = $this->formBuilder->checkbox('testName', 'Test label', [$attr => true]);
            $this->assertContains($attr, $checkbox, $message . $attr);

            $checkbox = $this->formBuilder->checkbox('testName', 'Test label', [$attr => false]);
            $this->assertNotContains($attr, $checkbox, $message . $attr);

            $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
            $this->assertNotContains($attr, $checkbox, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testFormControlClass()
    {
        $message = 'Some issue with checkbox form-control class';

        $this->setConfigValueStub('bootstrap', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertNotContains($this->getFromConfig('form_control_class'), $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['form-control-class' => 'custom-from-control']);
        $this->assertNotContains('custom-from-control', $checkbox, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with checkbox classes';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox1 = $this->formBuilder->checkbox('testName', 'Test label', ['class' => 'test-class']);
        $checkbox2 = $this->formBuilder->checkbox('testName', 'Test label');
        $checkbox3 = $this->formBuilder->checkbox('testName', 'Test label', ['checkbox-label-class' => 'test-checkbox-label-class']);

        $this->assertContains('test-class', $checkbox1, $message);
        $this->assertNotContains('test-class', $checkbox2, $message);
        $this->assertContains('test-checkbox-label-class', $checkbox3, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testWrappers()
    {
        $message = 'Some issue with checkbox wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['label' => true]);
        $this->assertStringStartsWith('<div', $checkbox, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $checkbox, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $checkbox, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $checkbox, $message);
        $this->assertContains('<label', $checkbox, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $checkbox, $message);
        $this->assertContains('</label>', $checkbox, $message);
        $this->assertContains('Test label', $checkbox, $message);
        $this->assertContains('label-after-sign', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', ['checkbox-label' => false]);
        $this->assertContains($this->getFromConfig('form_group_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $checkbox, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $checkbox, $message);
        $this->assertNotContains('<label', $checkbox, $message);
        $this->assertNotContains('for="get-without-route-name-test-entity-test-name"', $checkbox, $message);
        $this->assertNotContains('</label>', $checkbox, $message);
        $this->assertNotContains('Test label', $checkbox, $message);
        $this->assertNotContains('label-after-sign', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertStringStartsWith('<div', $checkbox, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $checkbox, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $checkbox, $message);
        $this->assertContains('<label', $checkbox, $message);
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $checkbox, $message);
        $this->assertContains('</label>', $checkbox, $message);
        $this->assertContains('Test label', $checkbox, $message);
        $this->assertNotContains('label-after-sign', $checkbox, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertStringStartsWith('<div', $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $checkbox, $message);
        $this->assertContains('<label', $checkbox, $message);
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $checkbox, $message);
        $this->assertContains('</label>', $checkbox, $message);
        $this->assertContains('Test label', $checkbox, $message);
        $this->assertNotContains('label-after-sign', $checkbox, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertStringStartsWith('<div', $checkbox, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $checkbox, $message);
        $this->assertNotContains($this->getFromConfig('offset_input_grid_class'), $checkbox, $message);
        $this->assertContains('<label', $checkbox, $message);
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $checkbox, $message);
        $this->assertContains('</label>', $checkbox, $message);
        $this->assertContains('Test label', $checkbox, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label');
        $this->assertNotContains('id="', $checkbox, $message);

        $this->setConfigValueStub('generate_id', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', [
            'checkbox-label' => false
        ]);
        $this->assertNotContains('<label', $checkbox, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $checkbox, $message);

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $classParameters = [
            'wrapper-class' => 'test-wrapper-class',
            'form-group-wrapper-class' => 'test-form-group-wrapper-class',
            'form-group-class' => 'test-form-group-class',
        ];

        $checkbox = $this->formBuilder->checkbox('testName', 'Test label', $classParameters);

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $checkbox, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('testName', '<a>Test label</a>');
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $checkbox, $message);
        $this->assertNotContains('<a>Test label</a>', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', '<a>Test label</a>', [
            'label-escaped' => true
        ]);
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $checkbox, $message);
        $this->assertNotContains('<a>Test label</a>', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('testName', '<a>Test label</a>', [
            'label-escaped' => false
        ]);
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $checkbox, $message);
        $this->assertContains('<a>Test label</a>', $checkbox, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testValueInserting()
    {
        $message = 'Some issue with checkbox values';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $checkbox = $this->formBuilder->checkbox('fieldWithValue', 'Test label');
        $this->assertContains('checked', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('fieldWithOld', 'Test label');
        $this->assertContains('checked', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('someField', 'Test label');
        $this->assertNotContains('checked', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('fieldTrue', 'Test label');
        $this->assertContains('checked', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('fieldFalse', 'Test label');
        $this->assertNotContains('checked', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('fieldTrueString', 'Test label');
        $this->assertContains('checked', $checkbox, $message);

        $checkbox = $this->formBuilder->checkbox('fieldFalseString', 'Test label');
        $this->assertNotContains('checked', $checkbox, $message);

        $this->formBuilder->end();
    }
}