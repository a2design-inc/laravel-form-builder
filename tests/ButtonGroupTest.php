<?php

require_once 'FormBuilderTestBase.php';

class ButtonGroupTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with button group';

        $buttonGroup = $this->formBuilder->buttonGroup();
        $buttonGroup .= $this->formBuilder->button();
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertStringStartsWith('<div', $buttonGroup, $message);
        $this->assertContains('<button', $buttonGroup, $message);
    }

    public function testLabel()
    {
        $message = 'Some issue with button group label';

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertContains('Test label', $buttonGroup, $message);
        $this->assertContains('<label', $buttonGroup, $message);
        $this->assertContains('</label>', $buttonGroup, $message);
    }


    public function testErrors()
    {
        $message = 'Some issue with validation errors in button group';

        $buttonGroup = $this->formBuilder->buttonGroup(['error' => 'Test error message']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertContains('Test error message', $buttonGroup, $message);
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" button group parameter';

        $buttonGroup = $this->formBuilder->buttonGroup(['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertContains('test-attr="test-attr-value"', $buttonGroup, $message);
    }

    public function testCommonbuttonAttributes()
    {
        $message = 'Some issue with attribute ';
        $attrs = ['autofocus', 'disabled'];
        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {

            $buttonGroup = $this->formBuilder->buttonGroup([$attr => true]);
            $buttonGroup .= $this->formBuilder->button('field-name');
            $buttonGroup .= $this->formBuilder->buttonGroupEnd();

            $this->assertContains($attr, $buttonGroup, $message . $attr);

            $buttonGroup = $this->formBuilder->buttonGroup([$attr => false]);
            $buttonGroup .= $this->formBuilder->button('field-name');
            $buttonGroup .= $this->formBuilder->buttonGroupEnd();

            $this->assertNotContains($attr, $buttonGroup, $message . $attr);

            $buttonGroup = $this->formBuilder->buttonGroup();
            $buttonGroup .= $this->formBuilder->button('field-name');
            $buttonGroup .= $this->formBuilder->buttonGroupEnd();

            $this->assertNotContains($attr, $buttonGroup, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testWrappers()
    {
        $message = 'Some issue with button wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertStringStartsWith('<div', $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $buttonGroup, $message);
        $this->assertContains('<label', $buttonGroup, $message);
        $this->assertContains('</label>', $buttonGroup, $message);
        $this->assertContains('Test label', $buttonGroup, $message);
        $this->assertContains('label-after-sign', $buttonGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertStringStartsWith('<div', $buttonGroup, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $buttonGroup, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $buttonGroup, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $buttonGroup, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $buttonGroup, $message);
        $this->assertContains('<label', $buttonGroup, $message);
        $this->assertContains('</label>', $buttonGroup, $message);
        $this->assertContains('Test label', $buttonGroup, $message);
        $this->assertContains('label-after-sign', $buttonGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertStringStartsWith('<div', $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $buttonGroup, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $buttonGroup, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $buttonGroup, $message);
        $this->assertContains('<label', $buttonGroup, $message);
        $this->assertContains('</label>', $buttonGroup, $message);
        $this->assertContains('Test label', $buttonGroup, $message);
        $this->assertContains('label-after-sign', $buttonGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertNotContains('for="', $buttonGroup, $message);

        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('form_group_wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertStringStartsNotWith('<div', $buttonGroup, $message);

        $this->setConfigValueStub('wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => 'Test label']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertNotContains('div', $buttonGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('wrapper', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => false]);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertNotContains('<label', $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $buttonGroup, $message);

        $buttonGroup = $this->formBuilder->buttonGroup(['label' => false]);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertNotContains('<label', $buttonGroup, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $buttonGroup, $message);

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $classParameters = [
            'wrapper-class' => 'test-wrapper-class',
            'form-group-wrapper-class' => 'test-form-group-wrapper-class',
            'label-class' => 'test-label-class',
            'control-label-class' => 'test-control-label-class',
            'form-group-class' => 'test-form-group-class',
            'label-grid-class' => 'test-label-grid-class',
        ];

        $buttonGroup = $this->formBuilder->buttonGroup(array_merge($classParameters, [
            'label' => true
        ]));
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $buttonGroup, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => '<a>Test label</a>']);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $buttonGroup, $message);
        $this->assertNotContains('<a>Test label</a>', $buttonGroup, $message);

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => '<a>Test label</a>', 'label-escaped' => true]);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();

        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $buttonGroup, $message);
        $this->assertNotContains('<a>Test label</a>', $buttonGroup, $message);

        $buttonGroup = $this->formBuilder->buttonGroup(['label-text' => '<a>Test label</a>', 'label-escaped' => false]);
        $buttonGroup .= $this->formBuilder->button('field-name');
        $buttonGroup .= $this->formBuilder->buttonGroupEnd();
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $buttonGroup, $message);
        $this->assertContains('<a>Test label</a>', $buttonGroup, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }
}