<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

define('APACHE_MIME_TYPES_URL','http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types');

$ext = [];
$mimes = [];

foreach (explode("\n", @file_get_contents(APACHE_MIME_TYPES_URL)) as $line) {
    if(isset($line[0]) && $line[0] != '#') {
        $line = preg_replace("/\s+/", ' ', $line);
        $cells = explode(" ", $line);
        $mime = array_shift($cells);
        foreach($cells as $cell) {
            $ext[strtolower($cell)][] = strtolower($mime);
            $mimes[ strtolower($mime) ][] = strtolower($cell);
        }
    }
}

if(!isset($ext["php"])) {
    foreach([
                'text/php',
                'text/x-php',
                'application/php',
                'application/x-php',
                'application/x-httpd-php',
                'application/x-httpd-php-source'
            ] as $m) {
        $ext["php"][] = $m;
        $mimes[$m][] = 'php';
    }
}

ksort($ext);
ksort($mimes);
$str = "<?php\nreturn['e2m'=>[";

foreach($ext as $e => $ms) {
    $str .= "'$e'=>['" . implode("'],['", $ms) . "'],";
}

$str .= "],'m2e'=>[";
foreach($mimes as $e => $ms) {
    $str .= "'$e'=>['" . implode("'],['", $ms) . "'],";
}
$str .= "]";

$str .= "];\n";

$target = __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "mime-types.php";
file_put_contents($target, $str);
echo "Done", PHP_EOL;