<?php

declare(strict_types=1);

namespace Zhaqq\XmlRpc\Monitor;

use Zhaqq\XmlRpc\Client;

/**
 * Class ProcessManager.
 */
abstract class ProcessManager
{
    const RUNNING = 'RUNNING';

    /**
     * 进程名称.
     *
     * @var array
     */
    protected $processes = [];

    /**
     * @var Client
     */
    protected $rpcClient;

    /**
     * ProcessManager constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setRpcClient(new Client(
            $config['dns'],
            $config['username'] ?? '',
            $config['password'] ?? ''
        ));
    }

    abstract public function watch();

    /**
     * @param $state
     *
     * @return bool
     */
    protected function isFatal($state)
    {
        if (self::RUNNING !== $state) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getProcesses(): array
    {
        return $this->processes;
    }

    /**
     * @param string $process
     *
     * @return $this
     */
    public function addProcess(string $process)
    {
        $this->processes[] = $process;

        return $this;
    }

    /**
     * @param array $process
     */
    public function setProcesses(array $process)
    {
        $this->processes = $process;
    }

    /**
     * @return Client
     */
    public function getRpcClient(): Client
    {
        return $this->rpcClient;
    }

    /**
     * @param Client $rpcClient
     */
    public function setRpcClient(Client $rpcClient): void
    {
        $this->rpcClient = $rpcClient;
    }
}
