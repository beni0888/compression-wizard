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


use Psr\Log\LoggerInterface;

class ZipFileExtractor extends AbstractFileExtractor
{

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        parent::__construct($logger);
        $this->setExtractionUtilityPath('/usr/bin/unzip');
    }

    /**
     * Returns the command needed to extract zip compressed file contents.
     * @param string $sourceFile
     * @param string $destinationPath
     * @return string
     */
    public function getExtractionCommand($sourceFile, $destinationPath)
    {
        return $this->extractionUtilityPath.' -o '.$sourceFile.' -d '.$destinationPath;
    }
}