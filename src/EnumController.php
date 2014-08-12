<?php
/**
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @date 12.08.2014
 */

namespace opus\enum;


use Symfony\Component\Yaml\Yaml;
use yii\console\Controller;

/**
 * Use this controller to execute the build command
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\enum
 */
class EnumController extends Controller
{
    /**
     * @var string
     */
    public $definitionAlias;

    /**
     * @var array
     */
    public $classBuilder = [
        'class' => 'opus\enum\ClassBuilder'
    ];

    /**
     * @param string $namespace
     * @param string $targetAlias
     * @throws \yii\base\InvalidConfigException
     */
    public function actionBuild($namespace, $targetAlias)
    {
        $definitionPath = \Yii::getAlias($this->definitionAlias);
        $targetPath = \Yii::getAlias($targetAlias);

        $definition = Yaml::parse($definitionPath);
        /** @var ClassBuilder $builder */
        $builder = \Yii::createObject($this->classBuilder, [$definition]);
        $builder->build($targetPath, $namespace);
        echo "Done\n";
    }

    /**
     * @inheritdoc
     */
    public function options($actionId)
    {
        return ['definitionAlias', 'targetAlias', 'namespace'];
    }
} 
