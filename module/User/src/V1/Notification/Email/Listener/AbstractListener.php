<?php
/**
 * Abstract listener
 *
 * @link
 * @copyright Copyright (c) 2015
 */

namespace User\V1\Notification\Email\Listener;

use Symfony\Component\Process\ProcessBuilder;

class AbstractListener
{
    protected $config;

    protected $viewRenderer;

    protected $mailTransport;

    protected $phpProcessBuilder;

    /**
     * Construct Event
     *
     * @param ProcessBuilder $phpProcessBuilder
     */
    public function __construct(ProcessBuilder $phpProcessBuilder)
    {
        $this->setPhpProcessBuilder($phpProcessBuilder);
    }

    /**
     * @return the $viewRenderer
     */
    public function getViewRenderer()
    {
        return $this->viewRenderer;
    }

    /**
     * @param field_type $viewRenderer
     */
    public function setViewRenderer($viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * @return the $mailTransport
     */
    public function getMailTransport()
    {
        return $this->mailTransport;
    }

    /**
     * @param field_type $mailTransport
     */
    public function setMailTransport($mailTransport)
    {
        $this->mailTransport = $mailTransport;
    }

    /**
     * @return the $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param field_type $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get ProcessBuilder
     *
     * @return ProcessBuilder $phpProcessBuilder
     */
    public function getPhpProcessBuilder()
    {
        return $this->phpProcessBuilder;
    }

    /**
     * @param ProcessBuilder $phpProcessBuilder
     */
    public function setPhpProcessBuilder(ProcessBuilder $phpProcessBuilder)
    {
        $this->phpProcessBuilder = $phpProcessBuilder;
    }
}
