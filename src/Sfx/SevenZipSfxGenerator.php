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

namespace Beni0888\CompressionWizard\Sfx;

use Beni0888\CompressionWizard\System\CommandRunner;
use Psr\Log\LoggerInterface;

class SevenZipSfxGenerator
{
    const APPEND_OPTION = 'a';
    const SAY_YES_TO_ALL = '-y';

    protected $binaryPath = '/usr/bin';
    protected $zipUtility = '7zr';
    protected $sfxModulePath = '/usr/lib/p7zip/7zsd.sfx';
    protected $commandRunner;
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->commadRunner = new CommandRunner($this->logger);
    }

    /**
     * Generates the 7z command needed to compress the target files.
     * @param array $parameters
     * @return string
     */
    public function get7zCommand(array $parameters = array())
    {
        return rtrim($this->binaryPath.'/'.$this->zipUtility.' '.implode(' ', $parameters));
    }

    /**
     * Generates a 7z file
     * @param string $sourcePath
     * @param string $destinationPath
     */
    public function compressTo7z($sourcePath, $destinationPath)
    {
        $command = $this->get7zCommand(array(
            self::APPEND_OPTION,
            self::SAY_YES_TO_ALL,
            $destinationPath,
            $sourcePath
        ));

        $this->commadRunner->execute($command);
    }

    /**
     * Returns the command needed to generate a self-extracting file.
     * @param string $_7zCompressedFile Path to a 7z compressed file
     * @param string $sfxConfigurationPath Path to sfx configuration file.
     * @param string $destinationPath Path to desired self-extracting destination file.
     * @return string Command to generate a windows executable self-extracting file.
     */
    public function getSfxGeneratorCommand($_7zCompressedFile, $sfxConfigurationPath, $destinationPath)
    {
        return 'cat '.$this->sfxModulePath.' '.$sfxConfigurationPath.' '.$_7zCompressedFile.' > '.$destinationPath;
    }

    /**
     * Returns the last executed command exit code.
     * @return int
     */
    public function getCommandExitCode()
    {
        return $this->commadRunner->getStatusCode();
    }

    /**
     * Returns true if the last executed command finished with an error exit code.
     * @return bool
     */
    public function commandFinishedWithError()
    {
        return !in_array($this->getCommandExitCode(), array(0, 1));
    }

    /**
     * Returns true if the last executed command finished with a warning exit code.
     * @return bool
     */
    public function commandFinishedWithWarnings()
    {
        return ($this->getCommandExitCode() == 1);
    }

    /**
     * Generate a windows executable self-extracting file.
     * @param string $_7zCompressedFile Path to a 7z compressed file.
     * @param SfxConfiguration $configuration Object that contains the configuration to generate the self-extracting file.
     * @param string $destinationPath Path to desired destination self-extracting file.
     */
    public function generateSfxFile($_7zCompressedFile, SfxConfiguration $configuration, $destinationPath)
    {
        $_7zConfigurationTmpfile = tempnam(sys_get_temp_dir(), '7z_');
        file_put_contents($_7zConfigurationTmpfile, $configuration->getConfiguration());
        $this->commadRunner->execute($this->getSfxGeneratorCommand($_7zCompressedFile, $_7zConfigurationTmpfile, $destinationPath));
        unlink($_7zConfigurationTmpfile);
    }
}
