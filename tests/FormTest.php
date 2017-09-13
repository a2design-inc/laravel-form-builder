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
        $this->assertContains('method="post"', $putForm, $message);
        $this->assertContains('type="hidden"', $putForm, $message);
        $this->assertContains('name="_method', $putForm, $message);
        $this->assertContains('value="PUT"', $putForm, $message);

        $deleteForm = $this->formBuilder->create('', null, ['method' => 'DELETE']);
        $this->assertContains('method="post"', $deleteForm, $message);
        $this->assertContains('type="hidden"', $deleteForm, $message);
        $this->assertContains('name="_method', $deleteForm, $message);
        $this->assertContains('value="DELETE"', $deleteForm, $message);

        $putForm = $this->formBuilder->create('', null, ['method' => 'put']);
        $this->assertContains('method="post"', $putForm, $message);
        $this->assertContains('type="hidden"', $putForm, $message);
        $this->assertContains('name="_method', $putForm, $message);
        $this->assertContains('value="PUT"', $putForm, $message);

        $deleteForm = $this->formBuilder->create('', null, ['method' => 'delete']);
        $this->assertContains('method="post"', $deleteForm, $message);
        $this->assertContains('type="hidden"', $deleteForm, $message);
        $this->assertContains('name="_method', $deleteForm, $message);
        $this->assertContains('value="DELETE"', $deleteForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'get']);
        $this->assertContains('method="get"', $getForm, $message);
        $this->assertNotContains('name="_method"', $getForm, $message);

        $postForm = $this->formBuilder->create('', null, ['method' => 'post']);
        $this->assertContains('method="post"', $postForm, $message);
        $this->assertNotContains('name="_method"', $postForm, $message);

        $getForm = $this->formBuilder->create('', null, ['method' => 'GET']);
        $this->assertContains('method="get"', $getForm, $message);
        $this->assertNotContains('name="_method"', $getForm, $message);

        $postForm = $this->formBuilder->create('', null, ['method' => 'POST']);
        $this->assertContains('method="post"', $postForm, $message);
        $this->assertNotContains('name="_method"', $postForm, $message);
    }

    public function testRouteDetection()
    {
        $message = 'Some issue with route detection';

        $putForm = $this->formBuilder->create('putRouteName');
        $this->assertContains('method="post"', $putForm, $message);
        $this->assertContains('action="/put-url', $putForm, $message);
        $this->assertContains('value="PUT"', $putForm, $message);

        $deleteForm = $this->formBuilder->create('deleteRouteName');
        $this->assertContains('method="post"', $deleteForm, $message);
        $this->assertContains('action="/delete-url', $deleteForm, $message);
        $this->assertContains('value="DELETE"', $deleteForm, $message);

        $getForm = $this->formBuilder->create('getRouteName');
        $this->assertNotContains('method="post"', $getForm, $message);
        $this->assertContains('method="get"', $getForm, $message);
        $this->assertContains('action="/get-url', $getForm, $message);
        $this->assertNotContains('value="GET"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName');
        $this->assertNotContains('method="post"', $getForm, $message);
        $this->assertContains('method="get"', $getForm, $message);
        $this->assertContains('action="/get-url-without-route-name', $getForm, $message);
        $this->assertNotContains('value="GET"', $getForm, $message);

        $postForm = $this->formBuilder->create('TestController@postWithoutRouteName');
        $this->assertContains('method="post"', $postForm, $message);
        $this->assertNotContains('name="_method"', $postForm, $message);
        $this->assertContains('action="/post-url-without-route-name', $postForm, $message);
        $this->assertNotContains('value="POST"', $postForm, $message);

        $postForm = $this->formBuilder->create('TestController@postWithoutRouteName', new TestEntity());
        $this->assertContains('method="post"', $postForm, $message);
        $this->assertNotContains('name="_method"', $postForm, $message);
        $this->assertContains('action="/post-url-without-route-name', $postForm, $message);
        $this->assertNotContains('value="POST"', $postForm, $message);


        $getForm = $this->formBuilder->create('getRouteName', new TestEntity());
        $this->assertNotContains('method="post"', $getForm, $message);
        $this->assertContains('method="get"', $getForm, $message);
        $this->assertContains('action="/get-url', $getForm, $message);
        $this->assertNotContains('value="GET"', $getForm, $message);

    }

    public function testAction()
    {
        $message = 'Some issue with action parameter';

        $defaultForm = $this->formBuilder->create('', null, ['url' => '/test-url']);
        $this->assertContains('action="/test-url"', $defaultForm, $message);

        $getForm = $this->formBuilder->create('getRouteName', null, ['url' => '/test-url']);
        $this->assertContains('action="/test-url"', $getForm, $message);
    }

    public function testId()
    {
        $message = 'Some issue with form id';

        $this->setConfigValueStub('generate_id', true);

        $defaultForm = $this->formBuilder->create('', null, ['id' => 'test-id']);
        $this->assertContains('id="test-id"', $defaultForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName');
        //name of controller action and name of entity
        $this->assertContains('id="get-without-route-name-test"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        //name of controller action and name of entity
        $this->assertContains('id="get-without-route-name-test-entity"', $getForm, $message);

        $this->setConfigValueStub('generate_id', false);

        $defaultForm = $this->formBuilder->create('', null, ['id' => 'test-id']);
        $this->assertContains('id="test-id"', $defaultForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName');
        //name of controller action and name of entity
        $this->assertNotContains('id="', $getForm, $message);

        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with form class';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use-grid', true);

        $defaultForm = $this->formBuilder->create('', null, ['class' => 'test-class', 'form-direction-class' => 'test-form-direction-class']);
        $this->assertContains('test-class', $defaultForm, $message);
        $this->assertContains('test-form-direction-class', $defaultForm, $message);

        $defaultForm = $this->formBuilder->create();
        $this->assertContains('form-horizontal', $defaultForm, $message);

        $this->setConfigValueStub('bootstrap', false);
        $this->setConfigValueStub('use-grid', false);

        $defaultForm = $this->formBuilder->create('', null, ['class' => 'test-class', 'form-direction-class' => 'test-form-direction-class']);
        $this->assertContains('test-class', $defaultForm, $message);
        $this->assertNotContains('test-form-direction-class', $defaultForm, $message);

        $defaultForm = $this->formBuilder->create();
        $this->assertNotContains('form-horizontal', $defaultForm, $message);

        $this->resetConfig();
    }

    public function testAbsoluteParameter()
    {
        $message = 'Some issue with "absolute" form parameter';

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['absolute' => true]);
        $this->assertContains('action="http://test.loc/', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $this->assertNotContains('action="http://test.loc/', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['absolute' => false]);
        $this->assertNotContains('action="http://test.loc/', $getForm, $message);
    }

    public function testUrlParameter()
    {
        $message = 'Some issue with "url" form parameter';

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $this->assertContains('action="/get-url-without-route-name', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['url' => '/custom-url']);
        $this->assertNotContains('action="/get-url-without-route-name', $getForm, $message);
        $this->assertContains('action="/custom-url', $getForm, $message);
    }

    public function testAattrsParameter()
    {
        $message = 'Some issue with "attrs" form parameter';

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $getForm, $message);
    }

    public function testEnctype()
    {
        $message = 'Some issue with form enctype';

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['enctype' => 'multipart/form-data']);
        $this->assertContains('enctype="multipart/form-data"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['file' => true]);
        $this->assertContains('enctype="multipart/form-data"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['has-files' => true]);
        $this->assertContains('enctype="multipart/form-data"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['file' => false]);
        $this->assertNotContains('enctype="multipart/form-data"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), ['has-files' => false]);
        $this->assertNotContains('enctype="multipart/form-data"', $getForm, $message);

        $getForm = $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $this->assertNotContains('enctype="multipart/form-data"', $getForm, $message);
    }

    public function testClosing()
    {
        $this->assertEquals('</form>', $this->formBuilder->end());
    }

    public function testFormInheriting()
    {
        $message = 'Some issue with form parameters inheriting';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity(), [
            //example of non-inherited parameter
            'id' => 'test-form-id',
            //example of inherited parameter
            'required' => true,
        ]);

        $input = $this->formBuilder->input('testName', 'Test label');
        $this->assertNotContains('test-form-id', $input, $message);
        $this->assertContains('required', $input, $message);

        $this->formBuilder->end();
    }
}