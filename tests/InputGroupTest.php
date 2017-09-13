<?php

require_once 'FormBuilderTestBase.php';

class InputGroupTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with input group';

        $inputGroup = $this->formBuilder->inputGroup();
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertStringStartsWith('<div', $inputGroup, $message);
        $this->assertContains('<input', $inputGroup, $message);
        $this->assertContains('field-name', $inputGroup, $message);
    }

    public function testLabel()
    {
        $message = 'Some issue with input group label';

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertContains('Test label', $inputGroup, $message);
        $this->assertContains('<label', $inputGroup, $message);
        $this->assertContains('</label>', $inputGroup, $message);
    }


    public function testErrors()
    {
        $message = 'Some issue with validation errors in input group';

        $inputGroup = $this->formBuilder->inputGroup(['error' => 'Test error message']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertContains('Test error message', $inputGroup, $message);
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" input group parameter';

        $inputGroup = $this->formBuilder->inputGroup(['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertContains('test-attr="test-attr-value"', $inputGroup, $message);
    }

    public function testCommonInputAttributes()
    {
        $message = 'Some issue with attribute ';
        $attrs = ['autofocus', 'disabled'];
        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {

            $inputGroup = $this->formBuilder->inputGroup([$attr => true]);
            $inputGroup .= $this->formBuilder->input('field-name');
            $inputGroup .= $this->formBuilder->inputGroupEnd();

            $this->assertContains($attr, $inputGroup, $message . $attr);

            $inputGroup = $this->formBuilder->inputGroup([$attr => false]);
            $inputGroup .= $this->formBuilder->input('field-name');
            $inputGroup .= $this->formBuilder->inputGroupEnd();

            $this->assertNotContains($attr, $inputGroup, $message . $attr);

            $inputGroup = $this->formBuilder->inputGroup();
            $inputGroup .= $this->formBuilder->input('field-name');
            $inputGroup .= $this->formBuilder->inputGroupEnd();

            $this->assertNotContains($attr, $inputGroup, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testFormControlClass()
    {
        $message = 'Some issue with input group form-control class';

        $this->setConfigValueStub('bootstrap', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup();
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertContains($this->getFromConfig('form_control_class'), $inputGroup, $message);

        $inputGroup = $this->formBuilder->inputGroup(['form-control-class' => 'custom-from-control']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertNotContains($this->getFromConfig('form_control_class'), $inputGroup, $message);
        $this->assertContains('custom-from-control', $inputGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $inputGroup = $this->formBuilder->inputGroup();
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertNotContains($this->getFromConfig('form_control_class'), $inputGroup, $message);
        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with input group class parameter';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['class' => 'test-class']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertContains('test-class', $inputGroup, $message);

        $this->formBuilder->end();
    }

    public function testWrappers()
    {
        $message = 'Some issue with input wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertStringStartsWith('<div', $inputGroup, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $inputGroup, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $inputGroup, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $inputGroup, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $inputGroup, $message);
        $this->assertContains('<label', $inputGroup, $message);
        $this->assertContains('</label>', $inputGroup, $message);
        $this->assertContains('Test label', $inputGroup, $message);
        $this->assertContains('label-after-sign', $inputGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertStringStartsWith('<div', $inputGroup, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $inputGroup, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $inputGroup, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $inputGroup, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $inputGroup, $message);
        $this->assertContains('<label', $inputGroup, $message);
        $this->assertContains('</label>', $inputGroup, $message);
        $this->assertContains('Test label', $inputGroup, $message);
        $this->assertContains('label-after-sign', $inputGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertStringStartsWith('<div', $inputGroup, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $inputGroup, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $inputGroup, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $inputGroup, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $inputGroup, $message);
        $this->assertContains('<label', $inputGroup, $message);
        $this->assertContains('</label>', $inputGroup, $message);
        $this->assertContains('Test label', $inputGroup, $message);
        $this->assertContains('label-after-sign', $inputGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertNotContains('for="', $inputGroup, $message);

        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('form_group_wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertStringStartsNotWith('<div', $inputGroup, $message);

        $this->setConfigValueStub('wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => 'Test label']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertNotContains('div', $inputGroup, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('wrapper', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => false]);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertNotContains('<label', $inputGroup, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $inputGroup, $message);

        $inputGroup = $this->formBuilder->inputGroup(['label' => false]);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertNotContains('<label', $inputGroup, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $inputGroup, $message);

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $classParameters = [
            'wrapper-class' => 'test-wrapper-class',
            'form-group-wrapper-class' => 'test-form-group-wrapper-class',
            'label-class' => 'test-label-class',
            'control-label-class' => 'test-control-label-class',
            'form-group-class' => 'test-form-group-class',
            'form-control-class' => 'test-form-control-class',
            'label-grid-class' => 'test-label-grid-class',
            'input-grid-class' => 'test-input-grid-class',
        ];

        $inputGroup = $this->formBuilder->inputGroup(array_merge($classParameters, [
            'label' => true
        ]));
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $inputGroup, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => '<a>Test label</a>']);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $inputGroup, $message);
        $this->assertNotContains('<a>Test label</a>', $inputGroup, $message);

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => '<a>Test label</a>', 'label-escaped' => true]);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();

        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $inputGroup, $message);
        $this->assertNotContains('<a>Test label</a>', $inputGroup, $message);

        $inputGroup = $this->formBuilder->inputGroup(['label-text' => '<a>Test label</a>', 'label-escaped' => false]);
        $inputGroup .= $this->formBuilder->input('field-name');
        $inputGroup .= $this->formBuilder->inputGroupEnd();
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $inputGroup, $message);
        $this->assertContains('<a>Test label</a>', $inputGroup, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }
}