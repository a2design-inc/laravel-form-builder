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
}