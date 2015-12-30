<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions licensed under the MIT license.
 */

namespace Beni0888\CompressionWizard\FileSystem;


use Beni0888\SfxWizard\Exception\FileSystemException;

class FileSystem
{

    /**
     * Generates a temporary directory.
     * @return string
     * @throws FileSystemException
     */
    public function createTempDir()
    {
        $tempfile = tempnam(sys_get_temp_dir(), '');
        if (file_exists($tempfile)) {
            unlink($tempfile);
        }
        mkdir($tempfile);
        if (!is_dir($tempfile)) {
            throw new FileSystemException('It was impossible to generate the temporary directory : '.$tempfile);
        }
        return $tempfile;
    }

    /**
     * Remove a directory and its contents if the directory is not empty.
     * @param string $directory
     */
    function removeDirectory($directory)
    {
        if (is_dir($directory)) {
            $objects = scandir($directory);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($directory . "/" . $object) == "dir") {
                        $this->removeDirectory($directory . "/" . $object);
                    } else {
                        unlink($directory."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($directory);
        }
    }

    /**
     * Remove a file, this method is a native php unlink function wrapper.
     * @param $file
     */
    function removeFile($file)
    {
        unlink($file);
    }
}