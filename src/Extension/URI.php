<?php

namespace League\Plates\Extension;

use League\Plates\Engine;
use LogicException;

/**
 * Extension that adds a number of URI checks.
 */
class URI implements ExtensionInterface
{
    /**
     * Instance of the current template.
     * @var Template
     */
    public $template;

    /**
     * The request URI.
     * @var string
     */
    protected $uri;

    /**
     * The request URI as an array.
     * @var array
     */
    protected $parts;

    /**
     * Create new URI instance.
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->parts = explode('/', $this->uri);
    }

    /**
     * Register extension functions.
     * @return null
     */
    public function register(Engine $engine)
    {
        $engine->registerFunction('uri', array($this, 'runUri'));
    }

    /**
     * Perform URI check.
     * @param  integer|string $var1
     * @param  string         $var2
     * @param  string         $var3
     * @param  string         $var4
     * @return boolean|string
     */
    public function runUri($var1 = null, $var2 = null, $var3 = null, $var4 = null)
    {
        if (is_null($var1)) {
            return $this->getUri();
        }

        if (is_numeric($var1) and is_null($var2) and is_null($var3) and is_null($var4)) {
            return $this->getUriSegment($var1);
        }

        if (is_numeric($var1) and is_string($var2) and is_null($var3) and is_null($var4)) {
            return $this->checkUriSegmentMatch($var1, $var2);
        }

        if (is_numeric($var1) and is_string($var2) and is_string($var3) and is_null($var4)) {
            return $this->checkUriSegmentMatch($var1, $var2, $var3);
        }

        if (is_numeric($var1) and is_string($var2) and is_string($var3) and is_string($var4)) {
            return $this->checkUriSegmentMatch($var1, $var2, $var3, $var4);
        }

        if (is_string($var1) and is_null($var2) and is_null($var3) and is_null($var4)) {
            return $this->checkUriRegexMatch($var1);
        }

        if (is_string($var1) and is_string($var2) and is_null($var3) and is_null($var4)) {
            return $this->checkUriRegexMatch($var1, $var2);
        }

        if (is_string($var1) and is_string($var2) and is_string($var3) and is_null($var4)) {
            return $this->checkUriRegexMatch($var1, $var2, $var3);
        }

        throw new LogicException('Invalid use of the uri function.');
    }

    /**
     * Get the URI.
     * @return string
     */
    protected function getUri()
    {
        return $this->uri;
    }

    /**
     * Get a URI segment.
     * @param  integer $key
     * @return string
     */
    protected function getUriSegment($key)
    {
        return $this->parts[$key];
    }

    /**
     * Perform a URI segment match.
     * @param  integer  $key
     * @param  string  $string
     * @param  mixed  $returnOnTrue
     * @param  mixed $returnOnFalse
     * @return mixed
     */
    protected function checkUriSegmentMatch($key, $string, $returnOnTrue = true, $returnOnFalse = false)
    {
        if ($this->parts[$key] === $string) {
            return $returnOnTrue;
        } else {
            return $returnOnFalse;
        }
    }

    /**
     * Perform a regular express match.
     * @param  string  $regex
     * @param  mixed $returnOnTrue
     * @param  mixed $returnOnFalse
     * @return mixed
     */
    protected function checkUriRegexMatch($regex, $returnOnTrue = true, $returnOnFalse = false)
    {
        if (preg_match('#^' . $regex . '$#', $this->uri) === 1) {
            return $returnOnTrue;
        } else {
            return $returnOnFalse;
        }
    }
}
