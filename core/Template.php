<?php

namespace core;

/**
 * Template for views
 * 
 * @author Lisheng Ye
 * @version 1.0
 */
class Template
{

    /**
     *
     * @var string $template
     * @var string $path
     */
    protected $template;
    protected $path;

    /**
     * Constructor
     * 
     * @return Template
     */
    public function __construct($template = null)
    {
        $this->path = ROOT_DIR . "/view/";
        if (empty($template) === false) {
            $this->load($template);
        }
    }

    /**
     * Set template path
     * 
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = ROOT_DIR . "/$path/";
    }

    /**
     * Load template file
     * 
     * @param string $template
     */
    public function load($template)
    {
        $file = $this->path . $template . '.html';
        if (file_exists($file) === true) {
            $this->template = file_get_contents($file);
        } else {
            throw new \Exception("Template not found: $template");
        }
    }

    /**
     * Set value to template, value is html escaped
     * 
     * @param string $var
     * @param string $content
     */
    public function set($var, $content)
    {
        $this->setRaw($var, htmlspecialchars((string) $content, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Set html to template without escape, only use for trusted html
     * 
     * @param string $var
     * @param string $content
     */
    public function setRaw($var, $content)
    {
        $this->template = str_replace("##" . $var . "##", (string) $content, $this->template);
    }

    /**
     * Parse template code
     * 
     * @return string
     */
    public function parse()
    {
        return $this->template;
    }

    /**
     * Show template
     */
    public function execute()
    {
        $this->template = preg_replace('/##\w+##/', "", $this->template);
        echo $this->template;
    }

}
