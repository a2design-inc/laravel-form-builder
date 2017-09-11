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
        $message = 'Some issue with hidden token input';

        $this->assertContains('type="hidden"', $defaultForm, $message);
        $this->assertContains('name="_token"', $defaultForm, $message);
        //we cant check the csrf_token value because in test case it is null
        $this->assertContains('value="', $defaultForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'get']);
        $this->assertNotContains('name="_token"', $getForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'GET']);
        $this->assertNotContains('name="_token"', $getForm, $message);
    }

    public function testMethodInput()
    {
        $defaultForm = $this->formBuilder->create();
        $message = 'Some issue with hidden method input';

        $this->assertNotContains('name="_method"', $defaultForm, $message);

        $putForm = $this->formBuilder->create('', null, ['method' => 'PUT']);
        $this->assertContains('type="hidden"', $putForm, $message);
        $this->assertContains('name="_method', $putForm, $message);
        $this->assertContains('value="PUT"', $putForm, $message);

        $deleteForm = $this->formBuilder->create('', null, ['method' => 'DELETE']);
        $this->assertContains('type="hidden"', $deleteForm, $message);
        $this->assertContains('name="_method', $deleteForm, $message);
        $this->assertContains('value="DELETE"', $deleteForm, $message);

        $putForm = $this->formBuilder->create('', null, ['method' => 'put']);
        $this->assertContains('type="hidden"', $putForm, $message);
        $this->assertContains('name="_method', $putForm, $message);
        $this->assertContains('value="PUT"', $putForm, $message);

        $deleteForm = $this->formBuilder->create('', null, ['method' => 'delete']);
        $this->assertContains('type="hidden"', $deleteForm, $message);
        $this->assertContains('name="_method', $deleteForm, $message);
        $this->assertContains('value="DELETE"', $deleteForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'get']);
        $this->assertNotContains('name="_method"', $getForm, $message);

        $postForm = $this->formBuilder->create('', null, ['method' => 'post']);
        $this->assertNotContains('name="_method"', $postForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'GET']);
        $this->assertNotContains('name="_method"', $getForm, $message);

        $postForm = $this->formBuilder->create('', null, ['method' => 'POST']);
        $this->assertNotContains('name="_method"', $postForm, $message);
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