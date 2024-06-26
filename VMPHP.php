<?php

/**
 * VMPHP
 * 
 * Обёртка для запуска и выполнения действий с PHP скриптами для PHP 8.0.0+
 * https://github.com/deathscore13/VMPHP
 */

class VMPHP
{
    private string $binary;

    /**
     * Конструктор
     * 
     * @param ?string $binary           Расположение исполняемого файла PHP
     */
    public function __construct(?string $binary = null)
    {
        if (($binary === null && ($binary = self::findBinary()) === null) || !@file_exists($binary))
            throw new Exception('PHP binary not found');
        
        $this->binary = $binary;
    }

    /**
     * Запуск файла в режиме PHP
     * 
     * @param string $file              Файл для запуска в режиме PHP
     * @param ?string $phpOpt           Опции запуска https://www.php.net/manual/ru/features.commandline.options.php
     * @param ?string $errPath          Директория для записи ошибок (по умолчанию "php")
     * @param ?string $cwd              Абсолютный путь рабочей директории. null - директория текущего процесса
     * @param ?array $env               Переменные среды в виде ['алиас' => 'значение']
     *                                  Linux: env > ~/Desktop/env.txt
     *                                  Windows: set > %homepath%\desktop\env.txt
     * @param ?array $options           Допустимые значения смотрите на https://www.php.net/manual/ru/function.proc-open.php
     * 
     * @return VirtualMachine           Объект VirtualMachine
     */
    public function run(string $file, ?string $phpOpt = null, ?string $errPath = null, ?string $cwd = null, ?array $env = null,
        ?array $options = null): VirtualMachine
    {
        if ($phpOpt === null)
            $phpOpt = '';
        else
            $phpOpt .= ' ';

        if ($errPath === null)
            $errPath = 'php/'.$file;
        else
            $errPath = 'php/'.$errPath;

        return new VirtualMachine($errPath, $this->binary.' '.$phpOpt.$file, $cwd, $env, $options);
    }

    /**
     * Пытается найти путь к исполняемому файлу PHP
     * 
     * @return ?string                  Путь к исполняемому файлу PHP или null если найти не удалось
     */
    public static function findBinary(): ?string
    {
        static $binary = null;

        if ($binary === null)
            $binary = @file_exists(PHP_BINARY) ? PHP_BINARY : PHP_BINDIR.'/php';

        if (PHP_SAPI === 'cli' || PHP_SAPI === 'cli-server')
            return $binary;
        
        $len = strlen($binary);
        $pos = strrpos($binary, '-cgi');
        if (($pos && $len === ($pos + 4)) ||
            (($pos = strrpos($binary, '-fpm')) && $len === ($pos + 4)))
        {
            $bin = substr($binary, 0, $pos);
        }
        
        $cli = ($bin ?? $binary).'-cli';
        if (@file_exists($cli))
            return $cli;
        
        if (isset($bin) && @file_exists($bin))
            return $bin;

        return @file_exists($binary) ? $binary : null;
    }
}