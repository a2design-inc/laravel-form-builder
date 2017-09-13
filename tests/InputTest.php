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

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $this->setConfigValueStub('generate_id', true);

        $input = $this->formBuilder->input('testName', 'Test label');
        //controller method - entity name - field name
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $input, $message);

        $input = $this->formBuilder->input('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $input, $message);

        $this->setConfigValueStub('generate_id', false);

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
        //
    }

    public function testLabel()
    {
        //
    }

    public function testFormInheriting()
    {
        //
    }

    public function testValueInserting()
    {
        //
    }
}