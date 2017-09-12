<?php

require_once 'FormBuilderTestBase.php';

class InputTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        //
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

    public function testCommonInputAttributes()
    {
        //
    }

    public function testClasses()
    {
        //
    }

    public function testOnlyInputParameter()
    {
        //
    }

    public function testInputGroupParameter()
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
}