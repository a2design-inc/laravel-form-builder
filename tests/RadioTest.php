<?php

require_once 'FormBuilderTestBase.php';

class RadioTest extends FormBuilderTestBase
{
    private $options = [
        'option1' => 'Text 1',
        'option2' => 'Text 2',
        'option3' => 'Text 3',
        'some-value' => 'Some value',
    ];

    public function testOpening()
    {
        $message = 'Some issue with radio';

        $radio = $this->formBuilder->radio('testName', 'Test label', ['options' => $this->options]);
        $this->assertContains('<input', $radio, $message);
        $this->assertContains('type="radio"', $radio, $message);
        $this->assertContains('name="testName"', $radio, $message);
        $this->assertContains('value=""', $radio, $message);
    }

    public function testErrors()
    {
        $message = 'Some issue with validation errors in radio';

        $radio = $this->formBuilder->radio('testName', 'Test label', ['error' => 'Test error message']);
        $this->assertContains('Test error message', $radio, $message);

        $this->setError('testName', 'Test error message');

        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertContains('Test error message', $radio, $message);

        $radio = $this->formBuilder->radio('testName', 'Test label', ['error' => false]);
        $this->assertNotContains('Test error message', $radio, $message);

        $this->setError('testName', 'Test error message 2');
        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertNotContains('Test error message 2', $radio, $message);

        $radio = $this->formBuilder->radio('testName', 'Test label', ['all-errors' => true]);
        $this->assertContains('Test error message 2', $radio, $message);

        $radio = $this->formBuilder->radio('testName', 'Test label', [
            'error-form-group-class' => 'test-error-form-group-class'
        ]);
        $this->assertContains('error-form-group-class', $radio, $message);

        $radio = $this->formBuilder->radio('testName', 'Test label', ['error-form-group-class' => 'test-error-class']);
        $this->assertContains('test-error-class', $radio, $message);

        $this->setConfigValueStub('error_form_group_class', 'config-test-error-form-group-class');

        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertContains('config-test-error-form-group-class', $radio, $message);

        $this->resetConfig();
        //reset errors
        $this->resetViewFactory();
    }

    public function testFormControlClass()
    {
        $message = 'Some issue with radio form-control class';

        $this->setConfigValueStub('bootstrap', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertNotContains($this->getFromConfig('form_control_class'), $radio, $message);

        $radio = $this->formBuilder->radio('testName', 'Test label', ['form-control-class' => 'custom-from-control']);
        $this->assertNotContains('custom-from-control', $radio, $message);

        $this->formBuilder->end();
        $this->resetConfig();
    }

    public function testRadioLabelClass()
    {
        $message = 'Some issue with radio radio-label-class parameter';

        $radio = $this->formBuilder->radio('testName', 'Test label', ['radio-label-class' => 'test-radio-label-class']);
        $this->assertNotContains('test-radio-label-class', $radio, $message);
    }

    public function testWrappers()
    {
        $message = 'Some issue with radio wrappers';

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('generate_id', true);
        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('form_group_wrapper', true);
        $this->setConfigValueStub('label_after', 'label-after-sign');

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', 'Test label', [
            'label' => true,
            'options' => $this->options,
        ]);
        $this->assertStringStartsWith('<div', $radio, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $radio, $message);
        $this->assertContains($this->getFromConfig('label_grid_class'), $radio, $message);
        $this->assertContains($this->getFromConfig('input_grid_class'), $radio, $message);
        $this->assertContains('<label', $radio, $message);
        $this->assertContains('for="get-without-route-name-test-entity-test-name"', $radio, $message);
        $this->assertContains('</label>', $radio, $message);
        $this->assertContains('Test label', $radio, $message);
        $this->assertContains('label-after-sign', $radio, $message);

        $this->setConfigValueStub('bootstrap', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertStringStartsWith('<div', $radio, $message);
        $this->assertNotContains($this->getFromConfig('form_group_class'), $radio, $message);
        $this->assertNotContains($this->getFromConfig('control_label_class'), $radio, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $radio, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $radio, $message);
        $this->assertContains('<label', $radio, $message);
        $this->assertContains('</label>', $radio, $message);
        $this->assertContains('Test label', $radio, $message);
        $this->assertContains('label-after-sign', $radio, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('bootstrap', true);
        $this->setConfigValueStub('use_grid', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertStringStartsWith('<div', $radio, $message);
        $this->assertContains($this->getFromConfig('form_group_class'), $radio, $message);
        $this->assertNotContains($this->getFromConfig('label_grid_class'), $radio, $message);
        $this->assertNotContains($this->getFromConfig('input_grid_class'), $radio, $message);
        $this->assertNotContains($this->getFromConfig('offset_input_grid_class'), $radio, $message);
        $this->assertContains('<label', $radio, $message);
        $this->assertContains('</label>', $radio, $message);
        $this->assertContains('Test label', $radio, $message);

        $this->formBuilder->end();

        $this->setConfigValueStub('use_grid', true);
        $this->setConfigValueStub('generate_id', false);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', 'Test label');
        $this->assertNotContains('id="', $radio, $message);

        $this->setConfigValueStub('generate_id', true);

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', 'Test label', [
            'radio-label' => false,
            'label' => false
        ]);
        $this->assertNotContains('<label', $radio, $message);
        $this->assertContains($this->getFromConfig('offset_input_grid_class'), $radio, $message);

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $classParameters = [
            'wrapper-class' => 'test-wrapper-class',
            'form-group-wrapper-class' => 'test-form-group-wrapper-class',
            'form-group-class' => 'test-form-group-class',
        ];

        $radio = $this->formBuilder->radio('testName', 'Test label', $classParameters);

        foreach ($classParameters as $classParameter) {
            $this->assertContains($classParameter, $radio, $message);
        }

        $this->formBuilder->end();

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('testName', '<a>Test label</a>');
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $radio, $message);
        $this->assertNotContains('<a>Test label</a>', $radio, $message);

        $radio = $this->formBuilder->radio('testName', '<a>Test label</a>', [
            'label-escaped' => true
        ]);
        $this->assertContains('&lt;a&gt;Test label&lt;/a&gt;', $radio, $message);
        $this->assertNotContains('<a>Test label</a>', $radio, $message);

        $radio = $this->formBuilder->radio('testName', '<a>Test label</a>', [
            'label-escaped' => false
        ]);
        $this->assertNotContains('&lt;a&gt;Test label&lt;/a&gt;', $radio, $message);
        $this->assertContains('<a>Test label</a>', $radio, $message);

        $this->formBuilder->end();

        $this->resetConfig();
    }

    public function testOptions()
    {
        $message = 'Some issue with radio options';

        $this->formBuilder->create('TestController@getWithoutRouteName', new TestEntity());

        $radio = $this->formBuilder->radio('someField', 'Test label', ['options' => $this->options]);

        $this->assertContains('<input', $radio, $message);
        $this->assertContains('value="option1"', $radio, $message);
        $this->assertContains('value="option2"', $radio, $message);
        $this->assertContains('value="option3"', $radio, $message);
        $this->assertContains('Text 1', $radio, $message);
        $this->assertContains('Text 2', $radio, $message);
        $this->assertContains('Text 3', $radio, $message);

        $radio = $this->formBuilder->radio('someField', 'Test label', [
            'options' => $this->options,
            'value' => 'option2'
        ]);

        $optionPos = strpos($radio, 'value="option2"');
        $checkedPos = strpos($radio, 'checked');
        $textPos = strpos($radio, 'Text 2');

        $this->assertContains('checked', $radio, $message);
        $this->assertTrue($optionPos < $checkedPos && $textPos > $checkedPos);

        $radio = $this->formBuilder->radio('fieldWithValue', 'Test label', [
            'options' => $this->options,
            'value' => 'option2'
        ]);

        $optionPos = strpos($radio, 'value="option2"');
        $checkedPos = strpos($radio, 'checked');
        $textPos = strpos($radio, 'Text 2');

        $this->assertContains('checked', $radio, $message);
        $this->assertTrue($optionPos < $checkedPos && $textPos > $checkedPos);

        $radio = $this->formBuilder->radio('fieldWithValue', 'Test label', [
            'options' => $this->options,
        ]);

        $optionPos = strpos($radio, 'value="some-value"');
        $checkedPos = strpos($radio, 'checked');
        $textPos = strpos($radio, 'Some value');

        $this->assertContains('checked', $radio, $message);
        $this->assertTrue($optionPos < $checkedPos && $textPos > $checkedPos);

        $this->formBuilder->end();
    }
}