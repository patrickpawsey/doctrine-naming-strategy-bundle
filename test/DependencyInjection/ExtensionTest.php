<?php
/*
 * This file is part of the Doctrine Naming Strategy Bundle, an DailyInfo project.
 *
 * (c) 2016 DailyInfo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DailyInfo\Bundle\DoctrineNamingStrategy\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use DailyInfo\Bundle\DoctrineNamingStrategy\DependencyInjection\Extension;
use DailyInfo\Bundle\DoctrineNamingStrategy\NamingStrategy\NamerCollection;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class ExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function configureUnderscoredBundlePrefixNamer()
    {
        $configuration = array(
            'case' => 'lowercase',
            'map' => array(
                'MyLongNameOfTheBundle' => 'my_prefix',
                'MyOtherLongNameOfTheBundle' => 'my_prefix_2'
            ),
            'joinTableFieldSuffix' => true,
            'blacklist' =>
                array(
                    'DoNotPrefixThisBundle'
                ),
            'whitelist' => array()
        );

        $this->load(array('underscored_bundle_prefix' => $configuration));

        $this->assertContainerBuilderHasService('daily_info.doctrine.orm.naming_strategy.underscored_bundle_prefix');

        $configuration['case'] = CASE_LOWER;

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'daily_info.doctrine.orm.naming_strategy.underscored_bundle_prefix',
            1,
            $configuration
        );
    }

    /**
     * @test
     */
    public function configureUnderscoredClassNamespacePrefixNamer()
    {
        $configuration = array(
            'case' => 'lowercase',
            'map' => array(
                'My\Class\Namespace\Entity' => 'my_prefix'
            ),
            'joinTableFieldSuffix' => true,
            'blacklist' =>
                array(
                    'My\Class\Namespace\Entity\ThisShouldBeSkipped',
                    'My\Class\Namespace\Entity\ThisShouldBeSkippedAsWell'
                ),
            'whitelist' => array()
        );

        $this->load(array('underscored_class_namespace_prefix' => $configuration));

        $this->assertContainerBuilderHasService('daily_info.doctrine.orm.naming_strategy.underscored_class_namespace_prefix');

        $configuration['case'] = CASE_LOWER;

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'daily_info.doctrine.orm.naming_strategy.underscored_class_namespace_prefix',
            0,
            $configuration
        );
    }

    /**
     * @test
     */
    public function configureNamerCollection()
    {
        $configuration = array(
            'default' => 'doctrine.orm.naming_strategy.underscore',
            'namers' => array(
                'daily_info.doctrine.orm.naming_strategy.underscored_class_namespace_prefix',
                'daily_info.doctrine.orm.naming_strategy.underscored_bundle_prefix'
            ),
            'concatenation' => NamerCollection::UNDERSCORE,
            'joinTableFieldSuffix' => true
        );

        $this->load(array('namer_collection' => $configuration));

        $this->assertContainerBuilderHasService('daily_info.doctrine.orm.naming_strategy.namer_collection');

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'daily_info.doctrine.orm.naming_strategy.namer_collection',
            1,
            $configuration['namers']
        );

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(
            'daily_info.doctrine.orm.naming_strategy.namer_collection',
            2,
            array(
                'concatenation' => $configuration['concatenation'],
                'joinTableFieldSuffix' => $configuration['joinTableFieldSuffix']
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new Extension()
        );
    }
}