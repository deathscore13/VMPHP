# VMPHP
### Обёртка для запуска и выполнения действий с PHP скриптами для VirtualMachine<br><br>

Советую открыть **`VMPHP.php`** и почитать описания методов

<br><br>
### Установка
Переместить папку с проектом в директорию **`run`** проекта [VirtualMachine](https://github.com/deathscore13/VirtualMachine)

<br><br>
### Пример использования
**`test.php`**
```php
// подключение класса для вывода данных
require('VirtualMachine/Write.php');

// подключение утилиты для пропуска мусора из вывода
require('VirtualMachine/TrashSkip.php');

// создание объекта VMWrite
$w = new VMWrite();

// установка точки для пропуска мусора из вывода
VMTrashSkip::child($w);

// вывод данных
$w->write('1');

// вывод данных
$w->write('2');
```
**`main.php`**
```php
// подключение VirtualMachine, оболочка загружается автоматически при использовании
require('VirtualMachine/VirtualMachine.php');

// подключение утилиты для пропуска мусора из вывода
require('VirtualMachine/TrashSkip.php');

// создание объекта VMPHP
$vm = new VMPHP();

// открытие test.php в дочернем процессе
$test = $vm->run('test2.php');

// пропуск мусора из вывода
VMTrashSkip::parent($test);

// вывод: 1
echo($test->read().PHP_EOL);

// вывод: 2
echo($test->read().PHP_EOL);
```
