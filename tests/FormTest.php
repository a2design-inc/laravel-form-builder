<?php

require_once 'FormBuilderTestBase.php';

class FormTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $defaultForm = $this->formBuilder->create();

        $this->assertContains('<form', $defaultForm, 'Form is not opened');
    }

    public function testTokenInput()
    {
        $defaultForm = $this->formBuilder->create();
        $message = 'Some issue with token hidden input';

        $this->assertContains('type="hidden"', $defaultForm, $message);
        $this->assertContains('name="_token"', $defaultForm, $message);
        //we cant check the csrf_token value because in test case it is null
        $this->assertContains('value="', $defaultForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'get']);
        $this->assertNotContains('type="hidden"', $getForm, $message);
        $this->assertNotContains('name="_token"', $getForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'GET']);
        $this->assertNotContains('type="hidden"', $getForm, $message);
        $this->assertNotContains('name="_token"', $getForm, $message);
    }

    public function testMethodInput()
    {
        //
    }

    public function testMethodDetection()
    {
        //
    }

    public function testAction()
    {
        //
    }

    public function testId()
    {
        //
    }

    public function testClass()
    {
        //
    }

    public function testAbsoluteParameter()
    {
        //
    }

    public function testUrlParameter()
    {
        //
    }

    public function testFormDirectionParameter()
    {
        //
    }

    public function testAattrsParameter()
    {
        //
    }

    public function testEnctypeParameter()
    {
        //
    }

    public function testHasFilesParameter()
    {
        //
    }

    public function testParameterInheriting()
    {
        //
    }
}