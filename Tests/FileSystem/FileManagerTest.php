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

namespace Beni0888\CompressionWizard\Tests\FileSystem;

use Beni0888\SfxWizard\FileSystem\FileManager;
use Beni0888\SfxWizard\FileSystem\FileType;

class FileManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $sut;

    /**
     * @param $file
     * @param $expectedType
     * @dataProvider providerForTestGetFileType
     */
    public function testGetFileType($file, $expectedType)
    {
        $this->sut = new FileManager($file);
        $this->assertEquals($expectedType, $this->sut->getFileType($file));
    }

    public function providerForTestGetFileType()
    {
        return array(
            array('foo.zip', FileType::ZIP),
            array('foo.rar', FileType::RAR),
            array('foo.msi', FileType::RUNNABLE),
            array('foo.exe', FileType::RUNNABLE),
            array('foo.com', FileType::RUNNABLE),
            array('foo.jpg', FileType::OTHER),
            array('/fake/path/foo.zip', FileType::ZIP)
        );
    }
}
