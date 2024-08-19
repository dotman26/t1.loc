<?php

namespace view;

class View
{
    private $templatesPath;

    private $extraVars = [];

    public function __construct(string $templatesPath)
    {
        $this->templatesPath = $templatesPath;
    }

    public function setVar(string $name, $value): void
    {
        $this->extraVars[$name] = $value;
    }

    public function render(string $templateName, array $vars = [], bool $ajax = false, int $code = 200)
    {
        if ($ajax == true) {
            $this->renderPartial($templateName, $vars, $code);
        } else {
            $this->renderHtml($templateName, $vars, $code);
        }

    }

    public function renderHtml(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);

        extract($this->extraVars);
        extract($vars);

        ob_start();

        include $this->templatesPath . '/layout.php';

        $buffer = ob_get_contents();

        ob_end_clean();

        echo $buffer;
    }

    public function renderPartial(string $templateName, array $vars = [], int $code = 200)
    {
        http_response_code($code);

        extract($this->extraVars);
        extract($vars);

        ob_start();

        include $this->templatesPath . '/ajax.php';

        $buffer = ob_get_contents();

        ob_end_clean();

        echo $buffer;
    }
}