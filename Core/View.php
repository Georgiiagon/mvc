<?php

namespace Core;

class View
{
	public static function render($view, $params = [])
	{
		$layoutPath = dirname(__DIR__) . "/App/Views/layout.php";
		$viewPath = dirname(__DIR__) . '/App/Views/' . $view . '.php';

        ob_start();
		require $layoutPath;
		$contentLayout = ob_get_contents();
		ob_end_clean();

		extract($params);

		ob_start();
		require $viewPath;
		$contentView = ob_get_contents();
		ob_end_clean();

        extract($params);
        
		echo str_replace('@content', $contentView, $contentLayout);
	}

    private static function getContent($file)
    {
        ob_start();
        require $file;
        $content = ob_get_contents($file);
        ob_end_clean();

        return $content;
    }
}
