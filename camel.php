<?php

use FondBot\Helpers\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

require 'vendor/autoload.php';

$filesystem = new Filesystem(new Local('tests'));

$files = $filesystem->listContents('', true);

collect($files)
    ->filter(function ($item) {
        return
            $item['type'] === 'file' &&
            isset($item['extension']) &&
            $item['extension'] === 'php' &&
            Str::endsWith($item['filename'], 'Test');
    })
    ->each(function ($item) use (&$filesystem) {
        $contents = $filesystem->read($item['path']);

        $contents = preg_replace_callback(
            '/function(.*?)\(/s',
            function (array $matches) {
                $value = trim($matches[1]);

                if (strpos($value, '__') === 0) {
                    return 'function '.$value.'(';
                }

                $value = ucwords(str_replace(['-', '_'], ' ', $value));
                $value = str_replace(' ', '', $value);
                $value = lcfirst($value);


                return 'function '.$value.'(';
            },
            $contents);

//        dump($contents);

        $filesystem->update($item['path'], $contents);
    });

