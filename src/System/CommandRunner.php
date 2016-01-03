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

namespace Beni0888\CompressionWizard\System;

use Beni0888\CompressionWizard\Exception\CommandRunnerException;
use Psr\Log\LoggerInterface;

class CommandRunner
{
    protected $command;
    protected $statusCode;
    protected $output;
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }


    /**
     * Executes a command line instruction and stores the resulting output and status code in the object variables.
     * @param string $command Command to execute
     * @throws \Exception
     */
    public function execute($command)
    {
        try {
            $this->command = $command;
            exec($this->command, $this->output, $this->statusCode);
            if ($this->statusCode != 0) {
                throw new CommandRunnerException(
                    sprintf("Command '%s' finished with error: %s", $this->command, implode(' ', $this->output)),
                    $this->getStatusCode()
                );
            }
        } catch (\Exception $ex) {
            throw $ex;
        }

        $this->logCommandExecution();
    }

    /**
     * Logs de current Commad
     */
    public function logCommandExecution()
    {
        if ($this->logger) {
            $commandContext = array(
                'command' => $this->command,
                'status' => $this->getStatusCode(),
                'output' => $this->getOutput()
            );
            $this->logger->debug('Commad executed', $commandContext);
        }
    }
}
