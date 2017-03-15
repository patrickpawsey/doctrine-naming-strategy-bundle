<?php
/*
 * This file is part of the Doctrine Naming Strategy Bundle, an DailyInfo project.
 *
 * (c) 2016 DailyInfo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DailyInfo\Bundle\DoctrineNamingStrategy\Tests\NamingStrategy;

use DailyInfo\Bundle\DoctrineNamingStrategy\NamingStrategy\NamerCollection;
use DailyInfo\Bundle\DoctrineNamingStrategy\NamingStrategy\UnderscoredClassNamespacePrefix;

class NamerCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function firstDifferentWins()
    {
        $namer = new NamerCollection(
            new UnderscoredClassNamespacePrefix(array(
                'map' => array(
                    'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
                )
            )),
            array(
                new UnderscoredClassNamespacePrefix(array(
                    'map' => array(
                        'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_other_prefix'
                    )
                )),
                new UnderscoredClassNamespacePrefix(array(
                    'map' => array(
                        'DailyInfo\\Bundle\\TestNamespace2\\Entity' => 'totaly_different_prefix'
                    )
                ))
            )
        );

        $this->assertSame('my_other_prefix_some_class', $namer->classToTableName('DailyInfo\\Bundle\\TestNamespace\\Entity\\SomeClass'));
    }

    /**
     * @test
     */
    public function differentStrategiesCanConcatenate()
    {
        $namer = new NamerCollection(
            new UnderscoredClassNamespacePrefix(array(
                'map' => array(
                    'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
                )
            )),
            array(
                new UnderscoredClassNamespacePrefix(array(
                    'map' => array(
                        'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_other_prefix'
                    )
                )),
                new UnderscoredClassNamespacePrefix(array(
                    'map' => array(
                        'DailyInfo\\Bundle\\TestNamespace2\\Entity' => 'totaly_different_prefix'
                    )
                ))
            )
        );

        $this->assertSame('my_other_prefix_some_class_totaly_different_prefix_some_other_class', $namer->joinTableName('DailyInfo\\Bundle\\TestNamespace\\Entity\\SomeClass', 'DailyInfo\\Bundle\\TestNamespace2\\Entity\\SomeOtherClass'));
    }
}

