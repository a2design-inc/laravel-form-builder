<?php

require_once 'FormBuilderTestBase.php';

class SelectTest extends FormBuilderTestBase
{
    private $options = [
        'option1' => 'Text 1',
        'option2' => 'Text 2',
        'option3' => 'Text 3',
        'some-value' => 'Some value',
    ];

    public function testOpening()
    {
        $message = 'Some issue with select';

        $select = $this->formBuilder->select('testName');
        $this->assertContains('<select', $select, $message);
        $this->assertContains('name="testName"', $select, $message);
    }

    public function testErrors()
    {
        $message = 'Some issue with validation errors in select';

        $select = $this->formBuilder->select('testName', 'Test label', ['error' => 'Test error message']);
        $this->assertContains('Test error message', $select, $message);

        $this->setError('testName', 'Test error message');

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertContains('Test error message', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['error' => false]);
        $this->assertNotContains('Test error message', $select, $message);

        $this->setError('testName', 'Test error message 2');
        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertNotContains('Test error message 2', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['all-errors' => true]);
        $this->assertContains('Test error message 2', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', [
            'error-form-group-class' => 'test-error-form-group-class'
        ]);
        $this->assertContains('error-form-group-class', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['error-form-group-class' => 'test-error-class']);
        $this->assertContains('test-error-class', $select, $message);

        $this->setConfigValueStub('error_form_group_class', 'config-test-error-form-group-class');
        $this->setConfigValueStub('error_class', 'config-test-error-class');

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertContains('config-test-error-class', $select, $message);
        $this->assertContains('config-test-error-form-group-class', $select, $message);

        $this->resetConfig();
        //reset errors
        $this->resetViewFactory();
    }

    public function testAttrsParameter()
    {
        $message = 'Some issue with "attrs" select parameter';

        $select = $this->formBuilder->select('testName', 'Test label', ['attrs' => [
            'test-attr' => 'test-attr-value'
        ]]);

        $this->assertContains('test-attr="test-attr-value"', $select, $message);
    }

    public function testId()
    {
        $message = 'Some issue with select id';

        $this->setConfigValueStub('generate_id', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        //controller method - entity name - field name
        $this->assertContains('id="get-without-route-name-test-entity-test-name"', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $select, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertNotContains('id="get-without-route-name-test-entity-test-name"', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['id' => 'custom-test-id']);
        $this->assertContains('id="custom-test-id"', $select, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testCommonSelectAttributes()
    {
        $message = 'Some issue with attribute ';
        $attrs = ['required', 'multiple', 'disabled', 'autofocus'];
        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        foreach ($attrs as $attr) {
            $select = $this->formBuilder->select('testName', 'Test label', [$attr => true]);
            $this->assertContains($attr, $select, $message . $attr);

            $select = $this->formBuilder->select('testName', 'Test label', [$attr => false]);
            $this->assertNotContains($attr, $select, $message . $attr);

            $select = $this->formBuilder->select('testName', 'Test label');
            $this->assertNotContains($attr, $select, $message . $attr);
        }

        $this->formBuilder->end();
    }

    public function testFormControlClass()
    {
        $message = 'Some issue with select form-control class';

        $this->setConfigValueStub('bootstrap', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertContains($this->getFromConfig('form_control_class'), $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['form-control-class' => 'custom-from-control']);
        $this->assertNotContains($this->getFromConfig('form_control_class'), $select, $message);
        $this->assertContains('custom-from-control', $select, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());
        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertNotContains($this->getFromConfig('form_control_class'), $select, $message);
        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testClass()
    {
        $message = 'Some issue with select classes';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select1 = $this->formBuilder->select('testName', 'Test label', ['class' => 'test-class']);
        $select2 = $this->formBuilder->select('testName', 'Test label');

        $this->assertContains('test-class', $select1, $message);
        $this->assertNotContains('test-class', $select2, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testWrappers()
    {
        $message = 'Some issue with select wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertStringStartsWith('<div', $select, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $select, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $select, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $select, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $select, $message);
        $this->assertContains('<label', $select, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $select, $message);
        $this->assertContains('</label>', $select, $message);
        $this->assertContains('Test label', $select, $message);
        $this->assertContains('label-after-sign', $select, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertStringStartsWith('<div', $select, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $select, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $select, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $select, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $select, $message);
        $this->assertContains('<label', $select, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $select, $message);
        $this->assertContains('</label>', $select, $message);
        $this->assertContains('Test label', $select, $message);
        $this->assertContains('label-after-sign', $select, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertStringStartsWith('<div', $select, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $select, $message);
        $this->assertContains($this->getFromConfig('control_label_class'), $select, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $select, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $select, $message);
        $this->assertContains('<label', $select, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $select, $message);
        $this->assertContains('</label>', $select, $message);
        $this->assertContains('Test label', $select, $message);
        $this->assertContains('label-after-sign', $select, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertNotContains('for="', $select, $message);

        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('form_group_wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertStringStartsNotWith('<div', $select, $message);

        $this->setConfigValueStub('wrapper', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label');
        $this->assertNotContains('div', $select, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('wrapper', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', 'Test label', [
            'label' => false
        ]);
        $this->assertNotContains('<label', $select, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $select, $message);

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

        $select = $this->formBuilder->select('testName', 'Test label', $classParameters);

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $select, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $select = $this->formBuilder->select('testName', '<a>Test label</a>');
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $select, $message);
        $this->assertNotContains('<a>Test label</a>', $select, $message);

        $select = $this->formBuilder->select('testName', '<a>Test label</a>', [
            'label-escaped' => true
        ]);
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $select, $message);
        $this->assertNotContains('<a>Test label</a>', $select, $message);

        $select = $this->formBuilder->select('testName', '<a>Test label</a>', [
            'label-escaped' => false
        ]);
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $select, $message);
        $this->assertContains('<a>Test label</a>', $select, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testOptions()
    {
        $message = 'Some issue with select options';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $options = $this->options;
        $this->options;

        $select = $this->formBuilder->select('someField', 'Test label', ['options' => $options]);

        $this->assertContains('<option', $select, $message);
        $this->assertContains('value="option1"', $select, $message);
        $this->assertContains('value="option2"', $select, $message);
        $this->assertContains('value="option3"', $select, $message);
        $this->assertContains('Text 1', $select, $message);
        $this->assertContains('Text 2', $select, $message);
        $this->assertContains('Text 3', $select, $message);

        $select = $this->formBuilder->select('someField', 'Test label', [
            'options' => $options,
            'value' => 'option2'
        ]);

        $optionPos = strpos($select, 'value="option2"');
        $selectedPos = strpos($select, 'selected');
        $textPos = strpos($select, 'Text 2');

        $this->assertContains('selected', $select, $message);
        $this->assertTrue($optionPos < $selectedPos && $textPos > $selectedPos);

        $select = $this->formBuilder->select('fieldWithValue', 'Test label', [
            'options' => $options,
            'value' => 'option2'
        ]);

        $optionPos = strpos($select, 'value="option2"');
        $selectedPos = strpos($select, 'selected');
        $textPos = strpos($select, 'Text 2');

        $this->assertContains('selected', $select, $message);
        $this->assertTrue($optionPos < $selectedPos && $textPos > $selectedPos);

        $select = $this->formBuilder->select('fieldWithValue', 'Test label', [
            'options' => $options,
        ]);

        $optionPos = strpos($select, 'value="some-value"');
        $selectedPos = strpos($select, 'selected');
        $textPos = strpos($select, 'Some value');

        $this->assertContains('selected', $select, $message);
        $this->assertTrue($optionPos < $selectedPos && $textPos > $selectedPos);

        $this->formBuilder->end();
    }

    public function testEmptyParameter()
    {
        $message = 'Some issue with select empty option';
        $options = $this->options;

        $select = $this->formBuilder->select('testName', 'Test label', ['empty' => true, 'options' => $options]);
        $this->assertContains('<option value="">', $select, $message);

        $select = $this->formBuilder->select('testName', 'Test label', ['empty' => false, 'options' => $options]);
        $this->assertNotContains('<option value="">', $select, $message);
    }
}