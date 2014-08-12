<?php
/**
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @date 12.08.2014
 */

namespace opus\enum;


use yii\base\InvalidParamException;
use yii\base\Object;
use yii\base\View;
use yii\base\ViewContextInterface;
use yii\helpers\Inflector;

/**
 * ClassBuilder builds enumeration definition class files from configuration
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\enum
 */
class ClassBuilder extends Object implements ViewContextInterface
{
    /**
     * @var string
     */
    public $templateFile = 'views/template.php';

    /**
     * @var array
     */
    public $definition;

    /**
     * @param array $definition
     * @param array $params
     */
    public function __construct(array $definition, $params = [])
    {
        $this->definition = $definition;
        parent::__construct($params);
    }

    /**
     * Build class files
     * @param string $targetPath Full path of the directory for the class files
     * @param string $namespace Namespace, e.g. project\enum
     */
    public function build($targetPath, $namespace)
    {
        $files = $this->generateCode($namespace);

        foreach ($files as $name => $contents) {
            $filePath = $this->preparePath($targetPath, $name);
            file_put_contents($filePath, $contents);
        }
    }

    /**
     * @param array $values
     * @return array
     */
    private function generateConstants(array $values)
    {
        $constants = [];

        foreach ($values as $value) {
            $constKey = $constValue = null;

            if (is_string($value)) {
                $constKey = $value;
                $constValue = "'$value'";
            } elseif (is_array($value)) {
                list($partKey, $partVal) = each($value);
                if (is_string($partKey) && isset($partVal['value'])) {
                    $constKey = $partKey;
                    $constValue = $partVal['value'];
                }
            }

            if (null === $constKey || null === $constValue) {
                throw new InvalidParamException('Parse error in definition');
            }

            $constants[$constKey] = $constValue;
        }

        return $constants;
    }

    /**
     * @return string the view path that may be prefixed to a relative view name.
     */
    public function getViewPath()
    {
        return __DIR__;
    }

    /**
     * @param string $namespace
     * @return array
     */
    private function generateCode($namespace)
    {
        $files = [];
        foreach ($this->definition as $category => $values) {
            $view = new View();

            $params = [
                'constants' => $this->generateConstants($values),
                'namespace' => $namespace,
                'className' => $category
            ];
            $code = $view->render($this->templateFile, $params, $this);

            $files[$category] = $code;
        }

        return $files;
    }

    /**
     * @param string $targetPath
     * @param string $name
     * @return string
     */
    private function preparePath($targetPath, $name)
    {
        if (!is_dir($targetPath) && !@mkdir($targetPath, 0777, true)) {
            throw new \RuntimeException('Could not create directory: ' . $targetPath);
        }
        $path = $targetPath . DIRECTORY_SEPARATOR . $name . '.php';
        return $path;
    }
}
