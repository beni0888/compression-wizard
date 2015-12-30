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

namespace Beni0888\SfxWizard\Sfx;


class SfxConfiguration 
{
    protected $title = "";
    protected $cancelPrompt = "Are you sure you want to cancel the process?";
    protected $extractDialogText = "Please wait...";
    protected $extractPathText = "Please, specify the path to the folder where extract the files";
    protected $extractTitle = "Extracting files...";
    protected $guiFlags = "8+32+64+256+4096";
    protected $guiMode = "1";
    protected $installPath = "%%S";
    protected $overwriteMode = "2";
    protected $runProgram = null;
    protected $finishMessage = null;

    /**
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCancelPrompt()
    {
        return $this->cancelPrompt;
    }

    /**
     * @param string $cancelPrompt
     * @return $this
     */
    public function setCancelPrompt($cancelPrompt)
    {
        $this->cancelPrompt = $cancelPrompt;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtractDialogText()
    {
        return $this->extractDialogText;
    }

    /**
     * @param string $extractDialogText
     * @return $this
     */
    public function setExtractDialogText($extractDialogText)
    {
        $this->extractDialogText = $extractDialogText;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtractTitle()
    {
        return $this->extractTitle;
    }

    /**
     * @param string $extractTitle
     * @return $this
     */
    public function setExtractTitle($extractTitle)
    {
        $this->extractTitle = $extractTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuiFlags()
    {
        return $this->guiFlags;
    }

    /**
     * @param string $guiFlags
     * @return $this
     */
    public function setGuiFlags($guiFlags)
    {
        $this->guiFlags = $guiFlags;
        return $this;
    }

    /**
     * @return string
     */
    public function getGuiMode()
    {
        return $this->guiMode;
    }

    /**
     * @param string $guiMode
     * @return $this
     */
    public function setGuiMode($guiMode)
    {
        $this->guiMode = $guiMode;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstallPath()
    {
        return $this->installPath;
    }

    /**
     * @param string $installPath
     * @return $this
     */
    public function setInstallPath($installPath)
    {
        $this->installPath = $installPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getOverwriteMode()
    {
        return $this->overwriteMode;
    }

    /**
     * @param string $overwriteMode
     * @return $this
     */
    public function setOverwriteMode($overwriteMode)
    {
        $this->overwriteMode = $overwriteMode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRunProgram()
    {
        return $this->runProgram;
    }

    /**
     * @param string $runProgram
     * @return $this
     */
    public function setRunProgram($runProgram)
    {
        $this->runProgram = $runProgram;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFinishMessage()
    {
        return $this->finishMessage;
    }

    /**
     * @param string $finishMessage
     * @return $this
     */
    public function setFinishMessage($finishMessage)
    {
        $this->finishMessage = $finishMessage;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtractPathText()
    {
        return $this->extractPathText;
    }

    /**
     * @param string $extractPathText
     * @return $this
     */
    public function setExtractPathText($extractPathText)
    {
        $this->extractPathText = $extractPathText;
        return $this;
    }



    /**
     * Return the configuration generated based on object's variables values.
     * @return string
     */
    public function getConfiguration()
    {
        $configuration = <<<CONFIGURATION
;!@Install@!UTF-8!
Title="{$this->title}"
CancelPrompt="{$this->cancelPrompt}"
ExtractDialogText="{$this->extractDialogText}"
ExtractPathText="{$this->extractPathText}"
ExtractTitle="{$this->extractTitle}"
GUIFlags="{$this->guiFlags}"
GUIMode="{$this->guiMode}"
InstallPath="{$this->installPath}"
OverwriteMode="{$this->overwriteMode}"

CONFIGURATION;

        if ($this->runProgram != null) {
            $configuration .= <<<RUNPROGRAM
RunProgram="nowait:\\"{$this->runProgram}\\""

RUNPROGRAM;
        }

        if ($this->finishMessage != null) {
            $configuration .= <<<FINISH_MESSAGE
FinishMessage="{$this->finishMessage}"

FINISH_MESSAGE;
        }

        $configuration .= <<<END
;!@InstallEnd@!
END;

        return $configuration;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getConfiguration();
    }
}