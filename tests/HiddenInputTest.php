<?php

require_once 'FormBuilderTestBase.php';

class HiddenInputTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with hidden input';

        $input = $this->formBuilder->hidden('testName');

        $this->assertContains('<input', $input, $message);
        $this->assertContains('name="testName"', $input, $message);
        $this->assertContains('type="hidden"', $input, $message);
        $this->assertNotContains('<div', $input, $message);
        $this->assertNotContains('<label', $input, $message);
    }

    public function testErrors()
    {
        $message = 'Some issue with validation errors in hidden input';

        $input = $this->formBuilder->hidden('testName', ['error' => 'Test error message']);
        $this->assertNotContains('Test error message', $input, $message);

        $this->setError('testName', 'Test error message');

        $input = $this->formBuilder->hidden('testName');
        $this->assertNotContains('Test error message', $input, $message);

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

        $input = $this->formBuilder->hidden('testName');
        //controller method - entity name - field name
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $input, $message);

        $input = $this->formBuilder->hidden('testName', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $input, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->hidden('testName');
        $this->assertNotContains('id="get-without-route-name-test-entity-test-name"', $input, $message);

        $input = $this->formBuilder->hidden('testName', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $input, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with input classes';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input1 = $this->formBuilder->hidden('testName', ['class' => 'test-class']);
        $input2 = $this->formBuilder->hidden('testName');

        $this->assertContains('test-class', $input1, $message);
        $this->assertNotContains('test-class', $input2, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testValueInserting()
    {
        $message = 'Some issue with input values';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $input = $this->formBuilder->hidden('fieldWithValue');
        $this->assertContains('value="some-value"', $input, $message);

        $input = $this->formBuilder->hidden('fieldWithOld');
        $this->assertNotContains('value="some-value"', $input, $message);
        $this->assertContains('value="some-old-value"', $input, $message);

        $input = $this->formBuilder->hidden('fieldWithOld', ['value' => 'custom-value']);
        $this->assertNotContains('value="some-value"', $input, $message);
        $this->assertNotContains('value="some-old-value"', $input, $message);
        $this->assertContains('value="custom-value"', $input, $message);

        $input = $this->formBuilder->hidden('fieldWithValue', ['value' => 'custom-value']);
        $this->assertNotContains('value="some-value"', $input, $message);
        $this->assertContains('value="custom-value"', $input, $message);

        $this->formBuilder->end();
    }
}