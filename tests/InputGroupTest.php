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
}