<?php

require_once 'FormBuilderTestBase.php';

class ButtonTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with button';

        $button = $this->formBuilder->button('Button text');
        $this->assertContains('<button', $button, $message);
        $this->assertContains('Button text', $button, $message);
        $this->assertContains('type="submit', $button, $message);
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" button parameter';

        $button = $this->formBuilder->button('', ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $button, $message);
    }

    public function testCommonButtonAttributes()
    {
        $message = 'Some issue with button attribute ';
        $booleanAttrs = ['disabled', 'autofocus'];
        $attrs = ['type', 'name', 'value', 'form'];

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($booleanAttrs as $attr) {
            $button = $this->formBuilder->button('', [$attr => true]);
            $this->assertContains($attr, $button, $message . $attr);

            $button = $this->formBuilder->button('', [$attr => false]);
            $this->assertNotContains($attr, $button, $message . $attr);

            $button = $this->formBuilder->button('');
            $this->assertNotContains($attr, $button, $message . $attr);
        }

        foreach ($attrs as $attr) {
            $button = $this->formBuilder->button('', [$attr => $attr . '-value']);
            $this->assertContains($attr . '="' . $attr . '-value"', $button, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testClass()
    {
        $message = 'Some issue with button classes';

        $this->setConfigValueStub('btn_class', 'test-btn-class');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $button = $this->formBuilder->button();
        $this->assertContains('test-btn-class', $button, $message);

        $button = $this->formBuilder->button('', ['btn-class' => 'custom-btn-class']);
        $this->assertContains('custom-btn-class', $button, $message);
        $this->assertNotContains('test-btn-class', $button, $message);

        $button = $this->formBuilder->button('', ['class' => 'custom-class']);
        $this->assertContains('custom-class', $button, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testLabel()
    {
        $message = 'Some issue with button label';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $button = $this->formBuilder->button();
        $this->assertNotContains('<label', $button, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $button, $message);

        $button = $this->formBuilder->button('', ['label' => true]);
        $this->assertContains('<label', $button, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $button, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $button, $message);

        $button = $this->formBuilder->button('', ['label-text' => 'Test label']);
        $this->assertContains('<label', $button, $message);
        $this->assertContains('Test label', $button, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $button, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $button, $message);

        $this->formBuilder->end();
    }

    public function testEscaping()
    {
        $message = 'Some issue with button escaping';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $button = $this->formBuilder->button('<a>Test text</a>');
        $this->assertContains('&lt;a&gt;Test text&lt;/a&gt;', $button, $message);
        $this->assertNotContains('<a>Test text</a>', $button, $message);

        $button = $this->formBuilder->button('<a>Test text</a>', ['escaped' => true]);
        $this->assertContains('&lt;a&gt;Test text&lt;/a&gt;', $button, $message);
        $this->assertNotContains('<a>Test text</a>', $button, $message);

        $button = $this->formBuilder->button('<a>Test text</a>', ['escaped' => false]);
        $this->assertNotContains('&lt;a&gt;Test text&lt;/a&gt;', $button, $message);
        $this->assertContains('<a>Test text</a>', $button, $message);

        $this->formBuilder->end();
    }
}