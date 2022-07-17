<?php

class FileLog
{
    static public function WriteFileLog($filePath, $content)
    {
        $hostPath = $GLOBALS['hostPath'];
        $now = date('Y-m-d-H');
        $path = "{$hostPath}/log/{$filePath}";
        if (! is_dir($path))
        {
            if (! mkdir($path, 0777, true))
            {
                return array(
                    'status' => false,
                    'errorMessage' => 'create File Error',
                    'path' => $path,
                );
            }
        }

        $fullPath = "{$path}/{$now}";
        $open = fopen($fullPath, 'a');
        $write = fwrite($open, "{$content} \n");
        fclose($open);

        return array(
            'status' => $open && $write ? true : false,
            'open' => $open ? true : false,
            'write' => $write ? true : false,
            'path' => $fullPath,
            'content' => $content,
        );
    }
}