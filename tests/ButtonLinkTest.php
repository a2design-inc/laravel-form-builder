<?php

require_once 'FormBuilderTestBase.php';

class ButtonLinkTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with button';

        $button = $this->formBuilder->buttonLink('Button text', '/url');

        $this->assertContains('<a', $button, $message);
        $this->assertContains('Button text', $button, $message);
        $this->assertContains('href="/url"', $button, $message);
        $this->assertContains('id="buttontext"', $button, $message);
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" button parameter';

        $button = $this->formBuilder->buttonLink('', '', ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $button, $message);
    }


    public function testClass()
    {
        $message = 'Some issue with button link classes';

        $this->setConfigValueStub('btn_class', 'test-btn-class');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $button = $this->formBuilder->buttonLink();
        $this->assertContains('test-btn-class', $button, $message);

        $button = $this->formBuilder->buttonLink('', '', ['btn-class' => 'custom-btn-class']);
        $this->assertContains('custom-btn-class', $button, $message);
        $this->assertNotContains('test-btn-class', $button, $message);

        $button = $this->formBuilder->buttonLink('', '', ['class' => 'custom-class']);
        $this->assertContains('custom-class', $button, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testLabel()
    {
        $message = 'Some issue with button link label';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $button = $this->formBuilder->buttonLink();
        $this->assertNotContains('<label', $button, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $button, $message);

        $button = $this->formBuilder->buttonLink('', '', ['label' => true]);
        $this->assertContains('<label', $button, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $button, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $button, $message);

        $button = $this->formBuilder->buttonLink('', '', ['label-text' => 'Test label']);
        $this->assertContains('<label', $button, $message);
        $this->assertContains('Test label', $button, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $button, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $button, $message);

        $this->formBuilder->end();
    }

    public function testEscaping()
    {
        $message = 'Some issue with button link escaping';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $button = $this->formBuilder->buttonLink('<a>Test text</a>');
        $this->assertContains('&lt;a&gt;Test text&lt;/a&gt;', $button, $message);
        $this->assertNotContains('<a>Test text</a>', $button, $message);

        $button = $this->formBuilder->buttonLink('<a>Test text</a>', '', ['escaped' => true]);
        $this->assertContains('&lt;a&gt;Test text&lt;/a&gt;', $button, $message);
        $this->assertNotContains('<a>Test text</a>', $button, $message);

        $button = $this->formBuilder->buttonLink('<a>Test text</a>', '', ['escaped' => false]);
        $this->assertNotContains('&lt;a&gt;Test text&lt;/a&gt;', $button, $message);
        $this->assertContains('<a>Test text</a>', $button, $message);

        $this->formBuilder->end();
    }

    public function testCommonAttributes()
    {
        $message = 'Some issue with button link attribute ';
        $attrs = ['name', 'name', 'target'];

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {
            $button = $this->formBuilder->buttonLink('', '', [$attr => $attr . '-value']);
            $this->assertContains($attr . '="' . $attr . '-value"', $button, $message . $attr);
        }

        $this->formBuilder->end();
    }
}