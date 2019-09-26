<?php

declare(strict_types=1);

namespace Zhaqq\XmlRpc;

use Zhaqq\XmlRpc\Exception\SupervisorException;

/**
 * @see http://supervisord.org/api.html
 * Class Client.
 */
class Client
{
    // supervisor xml-rpc methods
    const GET_API_VERSION         = 'getAPIVersion';
    const GET_SUPERVISOR_VERSION  = 'getSupervisorVersion';
    const GET_IDENTIFICATION      = 'getIdentification';
    const GET_STATE               = 'getState';
    const GET_PID                 = 'getPID';
    const READ_LOG                = 'readLog';
    const CLEAR_LOG               = 'clearLog';
    const SHUTDOWN                = 'shutdown';
    const RESTART                 = 'restart';
    const GET_PROCESS_INFO        = 'getProcessInfo';
    const GET_ALL_PROCESS_INFO    = 'getAllProcessInfo';
    const START_PROCESS           = 'startProcess';
    const STOP_PROCESS            = 'stopProcess';
    const START_PROCESS_GROUP     = 'startProcessGroup';
    const STOP_PROCESS_GROUP      = 'stopProcessGroup';
    const START_ALL_PROCESS       = 'startAllProcesses';
    const STOP_ALL_PROCESS        = 'stopAllProcesses';
    const SEND_PROCESS_STDIN      = 'sendProcessStdin';
    const SEND_REMOTE_COMM_EVENT  = 'sendRemoteCommEvent';
    const ADD_PROCESS_GROUP       = 'addProcessGroup';
    const REMOVE_PROCESS_GROUP    = 'removeProcessGroup';
    const READ_PROCESS_STDOUT_LOG = 'readProcessStdoutLog';
    const READ_PROCESS_STDERR_LOG = 'readProcessStderrLog';
    const TAIL_PROCESS_STDOUT_LOG = 'tailProcessStdoutLog';
    const TAIL_PROCESS_STDERR_LOG = 'tailProcessStderrLog';
    const CLEAR_PROCESS_LOGS      = 'clearProcessLogs';
    const CLEAR_ALL_PROCESS_LOGS  = 'clearAllProcessLogs';
    const SYSTEM_LIST_METHODS     = 'system.listMethods';
    const SYSTEM_METHOD_HELP      = 'system.methodHelp';
    const SYSTEM_METHOD_SIGNATURE = 'system.methodSignature';
    const SYSTEM_MULTI_CALL       = 'system.multicall';

    /**
     * @var array
     */
    protected $headers = [
        'Content-Type' => 'text/xml',
    ];

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $request;

    /**
     * Client constructor.
     *
     * @param string $dns
     * @param string $username
     * @param string $password
     */
    public function __construct($dns = '127.0.0.1:9001', $username = '', $password = '')
    {
        $this->setUrl($dns);
        $username && $this->withHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));
        $this->setRequest(new \GuzzleHttp\Client());
    }

    /**
     * @return array|bool|string
     */
    public function getApiVersion()
    {
        return $this->__call(static::GET_API_VERSION);
    }

    /**
     * @return array|bool|string
     */
    public function getSupervisorVersion()
    {
        return $this->__call(self::GET_SUPERVISOR_VERSION);
    }

    /**
     * @return array|bool|string
     */
    public function getIdentification()
    {
        return $this->__call(self::GET_IDENTIFICATION);
    }

    /**
     * @return array|bool|string
     */
    public function getState()
    {
        return $this->__call(self::GET_STATE);
    }

    /**
     * @return array|bool|string
     */
    public function getPid()
    {
        return $this->__call(self::GET_PID);
    }

    /**
     * 查看系统日志
     *
     * @param $offset
     * @param $length
     *
     * @return array|bool|string
     */
    public function readLog($offset, $length)
    {
        return $this->__call(self::READ_LOG, [$offset, $length]);
    }

    /**
     * @return array|bool|string
     */
    public function clearLog()
    {
        return $this->__call(self::CLEAR_LOG);
    }

    /**
     * @return array|bool|string
     */
    public function shutdown()
    {
        return $this->__call(self::SHUTDOWN);
    }

    /**
     * @return array|bool|string
     */
    public function restart()
    {
        return $this->__call(self::RESTART);
    }

    /**
     * @param $name
     *
     * @return array|bool|string
     */
    public function getProcessInfo($name)
    {
        return $this->__call(self::GET_PROCESS_INFO, [$name]);
    }

    /**
     * @return array|bool|string
     */
    public function getAllProcessInfo()
    {
        return $this->__call(self::GET_ALL_PROCESS_INFO);
    }

    /**
     * @param $name
     * @param bool $wait
     *
     * @return array|bool|string
     */
    public function startProcess($name, $wait = true)
    {
        return $this->__call(self::START_PROCESS, [$name, $wait]);
    }

    /**
     * @param $name
     * @param bool $wait
     *
     * @return array|bool|string
     */
    public function stopProcess($name, $wait = true)
    {
        return $this->__call(self::STOP_PROCESS, [$name, $wait]);
    }

    /**
     * @param $name
     * @param bool $wait
     *
     * @return array|bool|string
     */
    public function startProcessGroup($name, $wait = true)
    {
        return $this->__call(self::START_PROCESS_GROUP, [$name, $wait]);
    }

    /**
     * @param $name
     * @param bool $wait
     *
     * @return array|bool|string
     */
    public function stopProcessGroup($name, $wait = true)
    {
        return $this->__call(self::STOP_PROCESS_GROUP, [$name, $wait]);
    }

    /**
     * @param bool $wait
     *
     * @return array|bool|string
     */
    public function startAllProcesses($wait = true)
    {
        return $this->__call(self::START_ALL_PROCESS, [$wait]);
    }

    /**
     * @param bool $wait
     *
     * @return array|bool|string
     */
    public function stopAllProcesses($wait = true)
    {
        return $this->__call(self::STOP_ALL_PROCESS, [$wait]);
    }

    /**
     * @param $name
     * @param $chars
     *
     * @return array|bool|string
     */
    public function sendProcessStdin($name, $chars)
    {
        return $this->__call(self::SEND_PROCESS_STDIN, [$name, $chars]);
    }

    /**
     * @param $type
     * @param $data
     *
     * @return array|bool|string
     */
    public function sendRemoteCommEvent($type, $data)
    {
        return $this->__call(self::SEND_REMOTE_COMM_EVENT, [$type, $data]);
    }

    /**
     * @param $name
     *
     * @return array|bool|string
     */
    public function addProcessGroup($name)
    {
        return $this->__call(self::ADD_PROCESS_GROUP, [$name]);
    }

    /**
     * @param $name
     *
     * @return array|bool|string
     */
    public function removeProcessGroup($name)
    {
        return $this->__call(self::REMOVE_PROCESS_GROUP, [$name]);
    }

    /**
     * @param $name
     * @param $offset
     * @param $length
     *
     * @return array|bool|string
     */
    public function readProcessStdoutLog($name, $offset, $length)
    {
        return $this->__call(self::READ_PROCESS_STDOUT_LOG, [$name, $offset, $length]);
    }

    /**
     * @param $name
     * @param $offset
     * @param $length
     *
     * @return array|bool|string
     */
    public function readProcessStderrLog($name, $offset, $length)
    {
        return $this->__call(self::READ_PROCESS_STDERR_LOG, [$name, $offset, $length]);
    }

    /**
     * @param $name
     * @param $offset
     * @param $length
     *
     * @return array|bool|string
     */
    public function tailProcessStdoutLog($name, $offset, $length)
    {
        return $this->__call(self::TAIL_PROCESS_STDOUT_LOG, [$name, $offset, $length]);
    }

    /**
     * @param $name
     * @param $offset
     * @param $length
     *
     * @return array|bool|string
     */
    public function tailProcessStderrLog($name, $offset, $length)
    {
        return $this->__call(self::TAIL_PROCESS_STDERR_LOG, [$name, $offset, $length]);
    }

    /**
     * @param $name
     *
     * @return array|bool|string
     */
    public function clearProcessLogs($name)
    {
        return $this->__call(self::CLEAR_PROCESS_LOGS, $name);
    }

    /**
     * @return array|bool|string
     */
    public function clearAllProcessLogs()
    {
        return $this->__call(static::CLEAR_ALL_PROCESS_LOGS);
    }

    /**
     * @return array|bool|string
     */
    public function listMethods()
    {
        return $this->__call(static::SYSTEM_LIST_METHODS);
    }

    /**
     * @param $name
     *
     * @return array|bool|string
     */
    public function methodHelp($name)
    {
        return $this->__call(static::SYSTEM_METHOD_HELP, ['supervisor.' . $name]);
    }

    /**
     * @param $name
     *
     * @return array|bool|string
     */
    public function methodSignature($name)
    {
        return $this->__call(static::SYSTEM_METHOD_SIGNATURE, ['supervisor.' . $name]);
    }

    /**
     * @param array $calls
     *
     * @return array|bool|string
     */
    public function multiCall(array $calls)
    {
        return $this->__call(static::SYSTEM_MULTI_CALL, [$calls]);
    }

    /**
     * @param $name
     * @param array $arguments
     *
     * @return array|bool|string
     */
    public function __call($name, $arguments = [])
    {
        if (false === strpos($name, '.')) {
            $name = 'supervisor.' . $name;
        }
        $response = $this->getRequest()->post($this->getUrl(), [
            'headers' => $this->getHeaders(),
            'body'    => \xmlrpc_encode_request($name, $arguments),
        ]);
        $response = \xmlrpc_decode(trim((string)$response->getBody()));
        if (!$response) {
            throw new SupervisorException('Invalid response from ' . $this->getUrl());
        }
        if (is_array($response) && xmlrpc_is_fault($response)) {
            throw new SupervisorException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url . '/RPC2';
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getRequest(): \GuzzleHttp\Client
    {
        return $this->request;
    }

    /**
     * @param \GuzzleHttp\Client $request
     */
    public function setRequest(\GuzzleHttp\Client $request)
    {
        $this->request = $request;
    }
}
