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

namespace Beni0888\SfxWizard\Tests\Sfx;

use Beni0888\SfxWizard\Sfx\SfxConfiguration;

class SfxConfigurationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param array $configurationValues
     * @param string $expectedConfiguration
     * @dataProvider dataProviderForTestGetConfiguration
     */
    public function testGetConfiguration(array $configurationValues, $expectedConfiguration)
    {
        $sut = new SfxConfiguration($configurationValues['Title']);
        unset($configurationValues['Title']);
        foreach ($configurationValues as $key => $value) {
            $setMethod = 'set'.$key;
            $sut->{$setMethod}($value);
        }

        $this->assertEquals($expectedConfiguration, $sut->getConfiguration());
    }

    public function dataProviderForTestGetConfiguration()
    {
        $data = array();

        /*
         * Data set
         */
        $configurationValues = array(
            'Title' => 'Foo title'
        );
        $configuration = <<<CONFIGURATION
;!@Install@!UTF-8!
Title="Foo title"
CancelPrompt="Are you sure you want to cancel the process?"
ExtractDialogText="Please wait..."
ExtractPathText="Please, specify the path to the folder where extract the files"
ExtractTitle="Extracting files..."
GUIFlags="8+32+64+256+4096"
GUIMode="1"
InstallPath="%%S"
OverwriteMode="2"
;!@InstallEnd@!
CONFIGURATION;
        $data[] = array($configurationValues, $configuration);

        /*
         * Data set
         */
        $configurationValues = array(
            'Title' => 'Foo title',
            'RunProgram' => 'fake-program.exe'
        );
        $configuration = <<<CONFIGURATION
;!@Install@!UTF-8!
Title="Foo title"
CancelPrompt="Are you sure you want to cancel the process?"
ExtractDialogText="Please wait..."
ExtractPathText="Please, specify the path to the folder where extract the files"
ExtractTitle="Extracting files..."
GUIFlags="8+32+64+256+4096"
GUIMode="1"
InstallPath="%%S"
OverwriteMode="2"
RunProgram="nowait:\\"fake-program.exe\\""
;!@InstallEnd@!
CONFIGURATION;
        $data[] = array($configurationValues, $configuration);

        /*
         * Data set
         */
        $configurationValues = array(
            'Title' => 'Foo title',
            'RunProgram' => 'fake-program.exe',
            'FinishMessage' => 'The end!'
        );
        $configuration = <<<CONFIGURATION
;!@Install@!UTF-8!
Title="Foo title"
CancelPrompt="Are you sure you want to cancel the process?"
ExtractDialogText="Please wait..."
ExtractPathText="Please, specify the path to the folder where extract the files"
ExtractTitle="Extracting files..."
GUIFlags="8+32+64+256+4096"
GUIMode="1"
InstallPath="%%S"
OverwriteMode="2"
RunProgram="nowait:\\"fake-program.exe\\""
FinishMessage="The end!"
;!@InstallEnd@!
CONFIGURATION;
        $data[] = array($configurationValues, $configuration);

        /*
         * Data set
         */
        $configurationValues = array(
            'Title' => 'Foo title',
            'CancelPrompt' => 'Are you sure?',
            'ExtractDialogText' => 'Please wait...',
            'ExtractPathText' => 'Path to files:',
            'ExtractTitle' => 'Extracting...',
            'GUIFlags' => '1234',
            'GUIMode' => '0',
            'InstallPath' => '/home/user',
            'OverwriteMode' => '1',
            'RunProgram' => 'fake-program.exe',
            'FinishMessage' => 'The end!'
        );
        $configuration = <<<CONFIGURATION
;!@Install@!UTF-8!
Title="Foo title"
CancelPrompt="Are you sure?"
ExtractDialogText="Please wait..."
ExtractPathText="Path to files:"
ExtractTitle="Extracting..."
GUIFlags="1234"
GUIMode="0"
InstallPath="/home/user"
OverwriteMode="1"
RunProgram="nowait:\\"fake-program.exe\\""
FinishMessage="The end!"
;!@InstallEnd@!
CONFIGURATION;
        $data[] = array($configurationValues, $configuration);

        return $data;
    }
}
