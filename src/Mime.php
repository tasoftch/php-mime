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

namespace TASoft\Util;


use Generator;

class Mime
{
    private $library;

    /**
     * Return the singleton instance of Mime
     * @return Mime
     */
    public static function sharedMime(): ?Mime
	{
        static $mime = NULL;
        if(!$mime)
            $mime = new static();
        return $mime;
    }

    public function __construct()
    {
        $sep = DIRECTORY_SEPARATOR;
        $this->library = require __DIR__ . "$sep..{$sep}lib{$sep}mime-types.php";
    }

    /**
     * Yield all found mime suggestions for given mime
     *
     * @param string $extension
     * @return Generator
     */
    public function yieldMimeForExtension(string $extension): Generator {
        $f = $this->library["e2m"][$extension] ?? [];
        foreach($f as $_)
            yield $_;
    }

    /**
     * Find mime for path extension
     *
     * @param string $extension
     * @return string|null
     */
    public function getMimeForExtension(string $extension): ?string {
        foreach($this->yieldMimeForExtension($extension) as $mime)
            return $mime;
        return NULL;
    }

    /**
     * Lists all matching mime types for an extension
     *
     * @param string $extension
     * @return array|null
     */
    public function getMimesForExtension(string $extension): ?array {
        $list = NULL;
        foreach($this->yieldMimeForExtension($extension) as $mime)
            $list[] = $mime;
        return $list;
    }

    /**
     * Yields all extensions for a given mime type.
     *
     * @param string $mime
     * @return Generator
     */
    public function yieldExtensionForMime(string $mime) {
        $f = $this->library["m2e"][$mime] ?? [];
        foreach($f as $_)
            yield $_;
    }

    /**
     * Get extension for mime
     *
     * @param string $mime
     * @return string|null
     */
    public function getExtensionForMime(string $mime): ?string {
        foreach($this->yieldExtensionForMime($mime) as $ext)
            return $ext;
        return NULL;
    }

    /**
     * List all extensions for a mime type
     *
     * @param string $mime
     * @return array|null
     */
    public function getExtensionsForMime(string $mime): ?array {
        $list = NULL;
        foreach($this->yieldExtensionForMime($mime) as $ext)
            $list[] = $ext;
        return $list;
    }
}