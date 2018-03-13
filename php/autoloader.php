<?php

function loadClass($class)
{
	$files = array(
		$class . '.php',
		str_replace('_', '/', $class) . '.php',
		str_replace('\\', '/', $class) . '.php'
	);
	foreach (explode(PATH_SEPARATOR, ini_get('include_path')) as $base_path)
	{
		foreach ($files as $file)
		{
			$path = "$base_path/$file";
			//echo $path."<br>";
			if (file_exists($path) && is_readable($path))
			{
				include_once $path;
				return;
			}
		}
	}
}

?>