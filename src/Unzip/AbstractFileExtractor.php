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

namespace Beni0888\CompressionWizard\Unzip;


use Beni0888\CompressionWizard\System\CommandRunner;
use Psr\Log\LoggerInterface;

abstract class AbstractFileExtractor
{
    protected $logger;
    protected $extractionUtilityPath;
    protected $commandRunner;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->commandRunner = new CommandRunner($this->logger);
    }

    /**
     * Sets the path to the extraction command line utility.
     * @param string $extractionUtilityPath Path to extraction command line utility.
     */
    public function setExtractionUtilityPath($extractionUtilityPath)
    {
        $this->extractionUtilityPath = $extractionUtilityPath;
    }

    /**
     * Abstract method that returns the command needed to extract the compressed file contents.
     * @param string $sourceFile
     * @param string $destinationPath
     * @return string
     */
    abstract public function getExtractionCommand($sourceFile, $destinationPath);

    /**
     * Extract de content of a compressed file.
     * @param string $sourceFile
     * @param string $destinationPath
     */
    public function extract($sourceFile, $destinationPath)
    {
        $this->commandRunner->execute($this->getExtractionCommand($sourceFile, $destinationPath));
    }

    /**
     * Returns the executed command status code.
     * @return int
     */
    public function getCommandStatusCode()
    {
        return $this->commandRunner->getStatusCode();
    }

    /**
     * Check if the unrar operation finished with an error status code.
     * @return bool
     */
    public function commandFinishedWithError()
    {
        return ($this->getCommandStatusCode() != 0);
    }
}
