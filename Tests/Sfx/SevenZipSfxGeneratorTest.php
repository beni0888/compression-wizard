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

namespace Beni0888\CompressionWizard\Tests\Sfx;

use Mockery as m;

class SevenZipSfxGeneratorTest extends \PHPUnit_Framework_TestCase {

    protected $sut;

    public function setUp()
    {
        $this->sut = m::mock('Beni0888\CompressionWizard\Sfx\SevenZipSfxGenerator')->makePartial();
    }

    public function testGet7zCommand()
    {
        $this->assertEquals('/usr/bin/7zr', $this->sut->get7zCommand());
        $this->assertEquals('/usr/bin/7zr a -y /tmp/foo.exe', $this->sut->get7zCommand(array(
            'a', '-y', '/tmp/foo.exe'
        )));$this->assertEquals('/usr/bin/7zr a -y /tmp/foo.exe foo.7z', $this->sut->get7zCommand(array(
            'a', '-y', '/tmp/foo.exe', 'foo.7z'
        )));
    }

    public function testGetSfxGeneratorCommand()
    {
        $this->assertEquals('cat /usr/lib/p7zip/7zsd.sfx /tmp/sfxConfiguration.txt foo.7z > foo.exe',
            $this->sut->getSfxGeneratorCommand('foo.7z', '/tmp/sfxConfiguration.txt', 'foo.exe'));
        $this->assertEquals('cat /usr/lib/p7zip/7zsd.sfx /tmp/sfxConfiguration.txt foo.7z > /home/tecnico/foo.exe',
            $this->sut->getSfxGeneratorCommand('foo.7z', '/tmp/sfxConfiguration.txt', '/home/tecnico/foo.exe'));
    }

    /**
     * @param int $statusCode Last executed command status code.
     * @param $expectedResult
     * @dataProvider providerForTestCommandFinishedWithError
     */
    public function testCommandFinishedWithError($statusCode, $expectedResult)
    {
        $this->sut->shouldReceive('getCommandExitCode')->andReturn($statusCode);
        $this->assertEquals($expectedResult, $this->sut->commandFinishedWithError());
    }

    public function providerForTestCommandFinishedWithError()
    {
        return array(
            array(0, false),
            array(1, false),
            array(2, true),
            array(3, true),
            array(-1, true)
        );
    }

    /**
     * @param int $statusCode Last executed command status code.
     * @param $expectedResult
     * @dataProvider providerForTestCommandFinishedWithWarnings
     */
    public function testCommandFinishedWithWarnings($statusCode, $expectedResult)
    {
        $this->sut->shouldReceive('getCommandExitCode')->andReturn($statusCode);
        $this->assertEquals($expectedResult, $this->sut->commandFinishedWithWarnings());
    }

    public function providerForTestCommandFinishedWithWarnings()
    {
        return array(
            array(0, false),
            array(1, true),
            array(2, false),
            array(3, false),
            array(-1, false)
        );
    }
}
