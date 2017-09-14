<?php

require_once 'FormBuilderTestBase.php';

class PostLinkTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $postLink = $this->formBuilder->postLink('putRouteName', 'Some text', new TestEntity());
        $this->assertContains('<form', $postLink, 'Post link form is not opened');
        $this->assertContains('</form>', $postLink, 'Post link form is not closed');
    }

    public function testTokenInput()
    {
        $postLink = $this->formBuilder->postLink('putRouteName', 'Some text', new TestEntity());
        $message = 'Some issue with hidden token input';

        $this->assertContains('type="hidden"', $postLink, $message);
        $this->assertContains('name="_token"', $postLink, $message);
        //we cant check the csrf_token value because in test case it is null
        $this->assertContains('value="', $postLink, $message);
    }

    public function testMethodInput()
    {
        $message = 'Some issue with hidden method input';

        $postLink = $this->formBuilder->postLink('putRouteName', 'Some text', new TestEntity());
        $this->assertContains('method="post"', $postLink, $message);
        $this->assertContains('type="hidden"', $postLink, $message);
        $this->assertContains('name="_method', $postLink, $message);
        $this->assertContains('value="PUT"', $postLink, $message);

        $postLink = $this->formBuilder->postLink('putRouteName', 'Some text', new TestEntity(), ['method' => 'DELETE']);
        $this->assertContains('method="post"', $postLink, $message);
        $this->assertContains('type="hidden"', $postLink, $message);
        $this->assertContains('name="_method', $postLink, $message);
        $this->assertContains('value="DELETE"', $postLink, $message);
    }

    public function testRouteDetection()
    {
        $message = 'Some issue with route detection';

        $postLink = $this->formBuilder->postLink('putRouteName', 'Some text', new TestEntity());
        $this->assertContains('method="post"', $postLink, $message);
        $this->assertContains('action="/put-url', $postLink, $message);
        $this->assertContains('value="PUT"', $postLink, $message);

        $postLink = $this->formBuilder->postLink('deleteRouteName', 'Some text', new TestEntity());
        $this->assertContains('method="post"', $postLink, $message);
        $this->assertContains('action="/delete-url', $postLink, $message);
        $this->assertContains('value="DELETE"', $postLink, $message);

        $postLink = $this->formBuilder->postLink('TestController@postWithoutRouteName', 'Some text', new TestEntity());
        $this->assertContains('method="post"', $postLink, $message);
        $this->assertNotContains('name="_method"', $postLink, $message);
        $this->assertContains('action="/post-url-without-route-name', $postLink, $message);
        $this->assertNotContains('value="POST"', $postLink, $message);

        $postLink = $this->formBuilder->postLink('TestController@postWithoutRouteName', 'Some text');
        $this->assertContains('method="post"', $postLink, $message);
        $this->assertNotContains('name="_method"', $postLink, $message);
        $this->assertContains('action="/post-url-without-route-name', $postLink, $message);
        $this->assertNotContains('value="POST"', $postLink, $message);
    }

    public function testId()
    {
        $message = 'Some issue with form id';

        $postLink = $this->formBuilder->postLink('', '', null, ['id' => 'test-id']);
        $this->assertContains('id="test-id"', $postLink, $message);

        $postLink = $this->formBuilder->postLink('', '', null, ['id' => 'test-id']);
        $this->assertContains('id="', $postLink, $message);

    }

    public function testClass()
    {
        $message = 'Some issue with form class';

        $postLink = $this->formBuilder->postLink('', '', null, ['class' => 'test-class']);
        $this->assertContains('class="test-class"', $postLink, $message);
    }

    public function testAbsoluteParameter()
    {
        $message = 'Some issue with "absolute" form parameter';

        $postLink = $this->formBuilder->postLink('TestController@getWithoutRouteName', '', null, ['absolute' => true]);
        $this->assertContains('action="http://test.loc/', $postLink, $message);

        $postLink = $this->formBuilder->postLink('TestController@getWithoutRouteName', '', null);
        $this->assertNotContains('action="http://test.loc/', $postLink, $message);

        $postLink = $this->formBuilder->postLink('TestController@getWithoutRouteName', '', null, ['absolute' => false]);
        $this->assertNotContains('action="http://test.loc/', $postLink, $message);
    }

    public function testUrlParameter()
    {
        $message = 'Some issue with "url" form parameter';

        $postLink = $this->formBuilder->postLink(
            'TestController@postWithoutRouteName',
            'Some text',
            new TestEntity(),
            ['url' => '/test-url']
        );
        $this->assertContains('action="/test-url"', $postLink, $message);

        $postLink = $this->formBuilder->postLink(
            '',
            '',
            null,
            ['url' => '/test-url']
        );
        $this->assertContains('action="/test-url"', $postLink, $message);
    }

    public function testEnctype()
    {
        $message = 'Some issue with form enctype';

        $postLink = $this->formBuilder->postLink('TestController@getWithoutRouteName', '', null, ['enctype' => 'multipart/form-data']);
        $this->assertContains('enctype="multipart/form-data"', $postLink, $message);

        $postLink = $this->formBuilder->postLink('TestController@getWithoutRouteName', '', null, ['enctype' => 'another/type']);
        $this->assertContains('enctype="another/type"', $postLink, $message);
    }

    public function testEscape()
    {
        $message = 'Some issue with form escaping';

        $postLink = $this->formBuilder->postLink('', '<a>Test text</a>');
        $this->assertContains('&lt;a&gt;Test text&lt;/a&gt;', $postLink, $message);
        $this->assertNotContains('<a>Test text</a>', $postLink, $message);

        $postLink = $this->formBuilder->postLink('', '<a>Test text</a>', null, ['escaped' => true]);
        $this->assertContains('&lt;a&gt;Test text&lt;/a&gt;', $postLink, $message);
        $this->assertNotContains('<a>Test text</a>', $postLink, $message);

        $postLink = $this->formBuilder->postLink('', '<a>Test text</a>', null, ['escaped' => false]);
        $this->assertNotContains('&lt;a&gt;Test text&lt;/a&gt;', $postLink, $message);
        $this->assertContains('<a>Test text</a>', $postLink, $message);
    }
}