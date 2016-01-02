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

namespace Beni0888\CompressionWizard\Tests\Unzip;


class AbstractFileExtractorTest extends \PHPUnit_Framework_TestCase
{

    protected $sut;

    public function setUp()
    {
        $this->sut = \Mockery::mock('Beni0888\SfxWizard\Unzip\AbstractFileExtractor')->makePartial();
    }

    /**
     * @param $statusCode
     * @param $finishedWithError
     * @dataProvider providerFormTestCommadFinishedWithError
     */
    public function testCommadFinishedWithError($statusCode, $finishedWithError)
    {
        $this->sut->shouldReceive('getCommandStatusCode')->andReturn($statusCode);
        $this->assertEquals($finishedWithError, $this->sut->commandFinishedWithError());
    }

    public function providerFormTestCommadFinishedWithError()
    {
        return array(
            array(0, false),
            array(1, true),
            array(2, true),
            array(-1, true),
        );
    }
}
