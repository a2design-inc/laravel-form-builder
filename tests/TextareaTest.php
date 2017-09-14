<?php

require_once 'FormBuilderTestBase.php';

class TextareaTest extends FormBuilderTestBase
{
    public function testOpening()
    {
        $message = 'Some issue with textarea';

        $textarea = $this->formBuilder->textarea('testName');
        $this->assertContains('<textarea', $textarea, $message);
        $this->assertContains('name="testName"', $textarea, $message);
    }

    public function testErrors()
    {
        $message = 'Some issue with validation errors in textarea';

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['error' => 'Test error message']);
        $this->assertContains('Test error message', $textarea, $message);

        $this->setError('testName', 'Test error message');

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertContains('Test error message', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['error' => false]);
        $this->assertNotContains('Test error message', $textarea, $message);

        $this->setError('testName', 'Test error message 2');
        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertNotContains('Test error message 2', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['all-errors' => true]);
        $this->assertContains('Test error message 2', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', [
            'error-form-group-class' => 'test-error-form-group-class'
        ]);
        $this->assertContains('error-form-group-class', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['error-form-group-class' => 'test-error-class']);
        $this->assertContains('test-error-class', $textarea, $message);

        $this->setConfigValueStub('error_form_group_class', 'config-test-error-form-group-class');
        $this->setConfigValueStub('error_class', 'config-test-error-class');

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertContains('config-test-error-class', $textarea, $message);
        $this->assertContains('config-test-error-form-group-class', $textarea, $message);

        $this->resetConfig();
        //reset errors
        $this->resetViewFactory();
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" textarea parameter';

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $textarea, $message);
    }

    public function testId()
    {
        $message = 'Some issue with textarea id';

        $this->setConfigValueStub('generate_id', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        //controller method - entity name - field name
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $textarea, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertNotContains('id="get-without-route-name-test-entity-test-name"', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $textarea, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testCommonTextareaAttributes()
    {
        $message = 'Some issue with attribute ';
        $attrs = ['required', 'readonly', 'disabled', 'autofocus', 'cols', 'rows', 'placeholder', 'maxlength'];
        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {
            $textarea = $this->formBuilder->textarea('testName', 'Test label', [$attr => true]);
            $this->assertContains($attr, $textarea, $message . $attr);

            $textarea = $this->formBuilder->textarea('testName', 'Test label', [$attr => false]);
            $this->assertNotContains($attr, $textarea, $message . $attr);

            $textarea = $this->formBuilder->textarea('testName', 'Test label');
            $this->assertNotContains($attr, $textarea, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testFormControlClass()
    {
        $message = 'Some issue with textarea form-control class';

        $this->setConfigValueStub('bootstrap', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertContains($this->getFromConfig('form_control_class'), $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', 'Test label', ['form-control-class' => 'custom-from-control']);
        $this->assertNotContains($this->getFromConfig('form_control_class'), $textarea, $message);
        $this->assertContains('custom-from-control', $textarea, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertNotContains($this->getFromConfig('form_control_class'), $textarea, $message);
        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with textarea classes';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea1 = $this->formBuilder->textarea('testName', 'Test label', ['class' => 'test-class']);
        $textarea2 = $this->formBuilder->textarea('testName', 'Test label');

        $this->assertContains('test-class', $textarea1, $message);
        $this->assertNotContains('test-class', $textarea2, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testWrappers()
    {
        $message = 'Some issue with textarea wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertStringStartsWith('<div', $textarea, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $textarea, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $textarea, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $textarea, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $textarea, $message);
        $this->assertContains('<label', $textarea, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $textarea, $message);
        $this->assertContains('</label>', $textarea, $message);
        $this->assertContains('Test label', $textarea, $message);
        $this->assertContains('label-after-sign', $textarea, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertStringStartsWith('<div', $textarea, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $textarea, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $textarea, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $textarea, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $textarea, $message);
        $this->assertContains('<label', $textarea, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $textarea, $message);
        $this->assertContains('</label>', $textarea, $message);
        $this->assertContains('Test label', $textarea, $message);
        $this->assertContains('label-after-sign', $textarea, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertStringStartsWith('<div', $textarea, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $textarea, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $textarea, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $textarea, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $textarea, $message);
        $this->assertContains('<label', $textarea, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $textarea, $message);
        $this->assertContains('</label>', $textarea, $message);
        $this->assertContains('Test label', $textarea, $message);
        $this->assertContains('label-after-sign', $textarea, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertNotContains('for="', $textarea, $message);

        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('form_group_wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertStringStartsNotWith('<div', $textarea, $message);

        $this->setConfigValueStub('wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label');
        $this->assertNotContains('div', $textarea, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('wrapper', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', 'Test label', [
            'label' => false
        ]);
        $this->assertNotContains('<label', $textarea, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $textarea, $message);

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

        $textarea = $this->formBuilder->textarea('testName', 'Test label', $classParameters);

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $textarea, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('testName', '<a>Test label</a>');
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $textarea, $message);
        $this->assertNotContains('<a>Test label</a>', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', '<a>Test label</a>', [
            'label-escaped' => true
        ]);
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $textarea, $message);
        $this->assertNotContains('<a>Test label</a>', $textarea, $message);

        $textarea = $this->formBuilder->textarea('testName', '<a>Test label</a>', [
            'label-escaped' => false
        ]);
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $textarea, $message);
        $this->assertContains('<a>Test label</a>', $textarea, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testValueInserting()
    {
        $message = 'Some issue with textarea values';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $textarea = $this->formBuilder->textarea('fieldWithValue', 'Test label');
        $this->assertContains('>some-value</textarea>', $textarea, $message);

        $textarea = $this->formBuilder->textarea('fieldWithOld', 'Test label');
        $this->assertNotContains('>some-value</textarea>', $textarea, $message);
        $this->assertContains('>some-old-value</textarea>', $textarea, $message);

        $textarea = $this->formBuilder->textarea('fieldWithOld', 'Test label', ['value' => 'custom-value']);
        $this->assertNotContains('>some-value</textarea>', $textarea, $message);
        $this->assertNotContains('>some-old-value</textarea>', $textarea, $message);
        $this->assertContains('>custom-value</textarea>', $textarea, $message);

        $textarea = $this->formBuilder->textarea('fieldWithValue', 'Test label', ['value' => 'custom-value']);
        $this->assertNotContains('>some-value</textarea>', $textarea, $message);
        $this->assertContains('>custom-value</textarea>', $textarea, $message);

        $this->formBuilder->end();
    }
}