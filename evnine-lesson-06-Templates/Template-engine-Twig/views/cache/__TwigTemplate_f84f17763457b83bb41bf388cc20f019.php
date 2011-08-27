<?php

/* form.tpl */
class __TwigTemplate_f84f17763457b83bb41bf388cc20f019 extends Twig_Template
{
    public function display(array $context, array $blocks = array())
    {
        // line 1
        echo "TWIG: ";
        echo (isset($context['ModelsHelloWorld_getHelloWorld']) ? $context['ModelsHelloWorld_getHelloWorld'] : null);
        echo "
";
        // line 2
        echo (isset($context['ModelsHelloWorld_getByeBye']) ? $context['ModelsHelloWorld_getByeBye'] : null);
    }

    public function getTemplateName()
    {
        return "form.tpl";
    }
}
