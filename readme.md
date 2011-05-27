[EN] evnine - AJAX PHP DSL controller JQuery Plugin AJAX Navigation + functions AJAX init for CMS API (Joomla, Bitrix, etc). [Easy debug]
================================
(c) 2008-2011 ev9eniy.info 
Dual licensed under the MIT or GPL Version 2 licenses.

Three things in programming for the changes in life:
----
1.	Visual easy understand the complex structure of the controller code 
----
		-no more if, switch, while, etc
		+only true-false
		+Is passed only one parameter for the method. (For UML to PHP generation)

2.	Easy debug 
----
		+beautiful print_r2
		+“frozen” data for debug and unit test (in DB, files, env, etc)
		[rewrite] auto generation of input parameters
		[rewrite] unit-test with automatic generation of tests and store to as serialized data.

3.	Inspection of the changes in only one parameter for the method.
----
		+green – new 
		+gray – in grave

[RU] evnine – AJAX PHP DSL Контроллер JQuery плагин навигации и загрузки функций  для работы с API ЦМС (Joomla, Битрикс итд). [С простой отладкой]
================================
(c) 2008-2011 ev9eniy.info
Двойная лицензия MIT или GPL Version 2

Структура контроллера “дерево” для простой кодогенерации.
----
1.	+Визуальное быстрое понимания сути происходящих в контроллере
----
		-ни каких циклов, переключателей итд
		+только да или нет в логике
		+между базовыми методами только один параметру  (Для удобной генераций кода из UML модели) 

2.	Легкая отладка по точке вxода.
----
		+удобный вывод переменных (array, obj, xml) по print_r2.
		+данные в процессе отладки и тестирования можно заморозить (Таблицы, Файлы, окружение, итд)
		[переписываю код] авто генерация входных параметров 
		[переписываю код] Юнит-тесты создаются сами и хранятся как стерилизованные объекты.
		+Имена в массиве ответа контроллера соответствуют путям к исходникам.
			Это значит, что [ModelsHelloWorld_getHellow]
			был вызван метод класса \models\ModelsHelloWorld->getHellow()

3.	+Визуальное отображения изменений в параметрах переданных от метода к методу.
----
		+Зеленым выделяются новые данные
		+Серым веделяется удалленные данные

4.  +Автоматизация
----
		+Встроенная валидация в контроллере 
			(Указали переменную, в методе получили доступ)
		+Авто генерация URN (URL) исxодя из параметров 
			(Данныx для валидации) принимаемыx методом.
		+Передача в классы любыx переменныx через конфиг
		+Система авто генерации кода для шаблонизаторов.
			PHPShort:  <?=$out['ModelsHelloWorld_getHellow']?> 
			PHP:  <?php echo $out['ModelsHelloWorld_getHellow']; ?>
			Twig: {{ ModelsHelloWorld_getHellow }} 			