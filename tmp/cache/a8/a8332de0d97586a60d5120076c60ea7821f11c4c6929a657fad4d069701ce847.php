<?php

/* index.twig */
class __TwigTemplate_1ed91fc837195159ee4fa9b728bfbc8a4aa1b00655fca33ea8e0ae731d8dd5ba extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<html>
<head>
    <title>Авторизация</title>
</head>
<body>
    <h1>Авторизация</h1>
    ";
        // line 7
        if ((($context["err"] ?? null) == true)) {
            // line 8
            echo "    err
    ";
        }
        // line 10
        echo "    <form action=\"../index.php\" method=\"POST\">
        <label>Логин</label>
        <input type=\"text\" name=\"login\">
        <br>
        <label>Пароль</label>
        <input type=\"password\"  name=\"password\">
        <br>
        <input type=\"submit\" name=\"input\" value=\"Войти\">
        <input type=\"submit\" name=\"reg\" value=\"Зарегистрироваться\">
    </form>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 10,  33 => 8,  31 => 7,  23 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "index.twig", "C:\\OpenServer\\OSPanel\\domains\\localhost\\PHP_Les17\\templates\\index.twig");
    }
}
