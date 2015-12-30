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

namespace Beni0888\CompressionWizard\Autorun;

use Beni0888\SfxWizard\FileSystem\FileSystem;

class ExeFileGenerator
{
    protected $sevenZip;
    protected $rarExtractor;
    protected $zipExtractor;
    protected $fileSystem;

    /**
     * @param SevenZip $sevenZip
     * @param RarFileExtractor $rarExtractor
     * @param ZipFileExtractor $zipExtractor
     */
    public function __construct(
        SevenZip $sevenZip,
        RarFileExtractor $rarExtractor,
        ZipFileExtractor $zipExtractor
    ) {
        $this->sevenZip = $sevenZip;
        $this->rarExtractor = $rarExtractor;
        $this->zipExtractor = $zipExtractor;
        $this->fileSystem = new FileSystem();
    }

    /**
     * Generates a windows autoexecutable self-extracting file.
     * @param string $title Title to show in the self-extrating program prompt.
     * @param string $sourceFile Path to source file.
     * @param string $destinationFile Path to desired destination file.
     */
    public function generateExeFile($title, $sourceFile, $destinationFile)
    {
        $sourceFileInfo = new FileManager($sourceFile);

        switch($sourceFileInfo->getFileType()) {
            case FileType::RAR:
            case FileType::ZIP:
                $this->generateExeFromCompressedFile($sourceFileInfo, $title, $sourceFile, $destinationFile);
                break;
            case FileType::RUNNABLE:
                $this->generateExeFromRunnableFile($sourceFileInfo, $title, $sourceFile, $destinationFile);
                break;
            default:
                $this->generateExeFromNeitherCompressedNorRunnableFile($title, $sourceFile, $destinationFile);
        }
    }

    /**
     * Generates a windows autoexecutable self-extracting file from a compressed file.
     * @param FileManager $fileInfo
     * @param string $title Title to show in the self-extracting dialog.
     * @param string $sourceFile Path to source file
     * @param string $destinationFile Path to desired destination file.
     */
    public function generateExeFromCompressedFile(FileManager $fileInfo, $title, $sourceFile, $destinationFile)
    {
        $tempDir = $this->fileSystem->createTempDir();
        if ($fileInfo->getFileType() == FileType::RAR) {
            $fileExtractor = $this->rarExtractor;
        } else {
            $fileExtractor = $this->zipExtractor;
        }
        $fileExtractor->extract($sourceFile, $tempDir);

        $sfxConfig = new SfxConfiguration($title);
        $this->setFinishMessageForNonRunnableFiles($sfxConfig);
        $_7zCompressedFile = $sourceFile.'.7z';
        $this->sevenZip->compressTo7z($tempDir.'/*', $_7zCompressedFile);
        $this->sevenZip->generateSfxFile($_7zCompressedFile, $sfxConfig, $destinationFile);

        $this->fileSystem->removeDirectory($tempDir);
        $this->fileSystem->removeFile($_7zCompressedFile);
    }

    /**
     * Generates a windows autoexecutable self-extracting file from a runnable file.
     * @param FileManager $fileInfo
     * @param string $title Title to show in the self-extracting dialog.
     * @param string $sourceFile Path to source file
     * @param string $destinationFile Path to desired destination file.
     */
    public function generateExeFromRunnableFile(FileManager $fileInfo, $title, $sourceFile, $destinationFile)
    {
        $sfxConfig = new SfxConfiguration($title);
        $sfxConfig->setRunProgram($fileInfo->getFilename());
        $_7zCompressedFile = $sourceFile.'.7z';
        $this->sevenZip->compressTo7z($sourceFile, $_7zCompressedFile);
        $this->sevenZip->generateSfxFile($_7zCompressedFile, $sfxConfig, $destinationFile);

        $this->fileSystem->removeFile($_7zCompressedFile);
    }

    /**
     * Generates a windows autoexecutable self-extracting file from a content that is not runnable or compressed file.
     * @param string $title Title to show in the self-extracting dialog.
     * @param string $sourceFile Path to source file
     * @param string $destinationFile Path to desired destination file.
     */
    public function generateExeFromNeitherCompressedNorRunnableFile($title, $sourceFile, $destinationFile)
    {
        $sfxConfig = new SfxConfiguration($title);
        $this->setFinishMessageForNonRunnableFiles($sfxConfig);
        $_7zCompressedFile = $sourceFile.'.7z';
        $this->sevenZip->compressTo7z($sourceFile, $_7zCompressedFile);

        $this->sevenZip->generateSfxFile($_7zCompressedFile, $sfxConfig, $destinationFile);

        $this->fileSystem->removeFile($_7zCompressedFile);
    }

    /**
     * Sets the installer finish message for those non runnable contents.
     * @param SfxConfiguration $configuration
     */
    public function setFinishMessageForNonRunnableFiles(SfxConfiguration &$configuration)
    {
        $configuration->setFinishMessage('Los archivos se descomprimieron correctamente en el directorio que usted seleccionó.' . PHP_EOL
            .'Abra dicha carpeta para tener acceso a sus archivos.');
    }
}