<?php

require_once 'FormBuilderTestBase.php';

class InputTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with input';

        $input = $this->formBuilder->input('testName');
        $this->assertContains('<input', $input, $message);
        $this->assertContains('name="testName"', $input, $message);
    }

    public function testErrors()
    {
        $message = 'Some issue with validation errors in input';

        $input = $this->formBuilder->input('testName', 'Test label', ['error' => 'Test error message']);
        $this->assertContains('Test error message', $input, $message);

        $this->setError('testName', 'Test error message');

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertContains('Test error message', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['error' => false]);
        $this->assertNotContains('Test error message', $input, $message);

        $this->setError('testName', 'Test error message 2');
        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertNotContains('Test error message 2', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['all-errors' => true]);
        $this->assertContains('Test error message 2', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', [
            'error-form-group-class' => 'test-error-form-group-class'
        ]);
        $this->assertContains('error-form-group-class', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['error-form-group-class' => 'test-error-class']);
        $this->assertContains('test-error-class', $input, $message);

        $this->setConfigValueStub('error_form_group_class', 'config-test-error-form-group-class');
        $this->setConfigValueStub('error_class', 'config-test-error-class');

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertContains('config-test-error-class', $input, $message);
        $this->assertContains('config-test-error-form-group-class', $input, $message);

        $this->resetConfig();
        //reset errors
        $this->resetViewFactory();
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" input parameter';

        $input = $this->formBuilder->input('testName', 'Test label', ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $input, $message);
    }

    public function testId()
    {
        $message = 'Some issue with input id';

        $this->setConfigValueStub('generate_id', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        //controller method - entity name - field name
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertNotContains('id="get-without-route-name-test-entity-test-name"', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $input, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testCommonInputAttributes()
    {
        $message = 'Some issue with attribute ';
        $attrs = ['required', 'readonly', 'disabled', 'autofocus'];
        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {
            $input = $this->formBuilder->input('testName', 'Test label', [$attr => true]);
            $this->assertContains($attr, $input, $message . $attr);

            $input = $this->formBuilder->input('testName', 'Test label', [$attr => false]);
            $this->assertNotContains($attr, $input, $message . $attr);

            $input = $this->formBuilder->input('testName', 'Test label');
            $this->assertNotContains($attr, $input, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testType()
    {
        $message = 'Some issue with input type attribute';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label', ['type' => 'test-type']);
        $this->assertContains('type="test-type"', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['type' => 'test-type']);
        $this->assertContains('type="test-type"', $input, $message);

        $typeInheritedInputs = [
            'password',
            'file',
            //html5
            'color',
            'date',
            'datetime',
            'datetimeLocal',
            'email',
            'number',
            'range',
            'search',
            'tel',
            'time',
            'url',
            'month',
            'week',
        ];

        foreach ($typeInheritedInputs as $inputType) {
            $input = $this->formBuilder->$inputType('testName', 'Test label');
            $this->assertContains('type="' . kebab_case($inputType) . '"', $input, $message);
        }

        $this->formBuilder->end();
    }

    public function testFormControlClass()
    {
        $message = 'Some issue with input form-control class';

        $this->setConfigValueStub('bootstrap', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertContains($this->getFromConfig('form_control_class'), $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['form-control-class' => 'custom-from-control']);
        $this->assertNotContains($this->getFromConfig('form_control_class'), $input, $message);
        $this->assertContains('custom-from-control', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertNotContains($this->getFromConfig('form_control_class'), $input, $message);
        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with input classes';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input1 = $this->formBuilder->input('testName', 'Test label', ['class' => 'test-class']);
        $input2 = $this->formBuilder->input('testName', 'Test label');

        $this->assertContains('test-class', $input1, $message);
        $this->assertNotContains('test-class', $input2, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testInputGroupParameter()
    {
        $message = 'Some issue with input group';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input1 = $this->formBuilder->input('testName', 'Test label', ['input-group' => 'test-group']);
        $input2 = $this->formBuilder->input('testName', 'Test label');

        $this->assertContains('test-group', $input1, $message);
        $this->assertNotContains('test-group', $input2, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testOnlyInputParameter()
    {
        $message = 'Some issue with input parameter "only-input"';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label', ['only-input' => true]);
        $this->assertStringStartsWith('<input', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['only-input' => false]);
        $this->assertStringStartsNotWith('<input', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertStringStartsNotWith('<input', $input, $message);

        $this->formBuilder->end();
    }

    public function testWrappers()
    {
        $message = 'Some issue with input wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertStringStartsWith('<div', $input, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $input, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $input, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $input, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $input, $message);
        $this->assertContains('<label', $input, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $input, $message);
        $this->assertContains('</label>', $input, $message);
        $this->assertContains('Test label', $input, $message);
        $this->assertContains('label-after-sign', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertStringStartsWith('<div', $input, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $input, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $input, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $input, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $input, $message);
        $this->assertContains('<label', $input, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $input, $message);
        $this->assertContains('</label>', $input, $message);
        $this->assertContains('Test label', $input, $message);
        $this->assertContains('label-after-sign', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertStringStartsWith('<div', $input, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $input, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $input, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $input, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $input, $message);
        $this->assertContains('<label', $input, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $input, $message);
        $this->assertContains('</label>', $input, $message);
        $this->assertContains('Test label', $input, $message);
        $this->assertContains('label-after-sign', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertNotContains('for="', $input, $message);

        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('form_group_wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertStringStartsNotWith('<div', $input, $message);

        $this->setConfigValueStub('wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertNotContains('div', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('wrapper', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', 'Test label', [
            'label' => false
        ]);
        $this->assertNotContains('<label', $input, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $input, $message);

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $classParameters = [
            'wrapper-class' => 'test-wrapper-class',
            'form-group-wrapper-class' => 'test-form-group-wrapper-class',
            'label-class' => 'test-label-class',
            'control-label-class' => 'test-control-label-class',
            'form-group-class' => 'test-form-group-class',
            'form-control-class' => 'test-form-control-class',
            'label-grid-class' => 'test-label-grid-class',
            'input-grid-class' => 'test-input-grid-class',
        ];

        $input = $this->formBuilder->input('testName', 'Test label', $classParameters);

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $input, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('testName', '<a>Test label</a>');
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $input, $message);
        $this->assertNotContains('<a>Test label</a>', $input, $message);

        $input = $this->formBuilder->input('testName', '<a>Test label</a>', [
            'label-escaped' => true
        ]);
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $input, $message);
        $this->assertNotContains('<a>Test label</a>', $input, $message);

        $input = $this->formBuilder->input('testName', '<a>Test label</a>', [
            'label-escaped' => false
        ]);
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $input, $message);
        $this->assertContains('<a>Test label</a>', $input, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testValueInserting()
    {
        $message = 'Some issue with input values';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->input('fieldWithValue', 'Test label');
        $this->assertContains('value="some-value"', $input, $message);

        $input = $this->formBuilder->input('fieldWithOld', 'Test label');
        $this->assertNotContains('value="some-value"', $input, $message);
        $this->assertContains('value="some-old-value"', $input, $message);

        $input = $this->formBuilder->input('fieldWithOld', 'Test label', ['value' => 'custom-value']);
        $this->assertNotContains('value="some-value"', $input, $message);
        $this->assertNotContains('value="some-old-value"', $input, $message);
        $this->assertContains('value="custom-value"', $input, $message);

        $input = $this->formBuilder->input('fieldWithValue', 'Test label', ['value' => 'custom-value']);
        $this->assertNotContains('value="some-value"', $input, $message);
        $this->assertContains('value="custom-value"', $input, $message);

        $this->formBuilder->end();
    }
}