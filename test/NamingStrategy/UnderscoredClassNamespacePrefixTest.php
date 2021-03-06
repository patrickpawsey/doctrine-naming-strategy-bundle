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

use DailyInfo\Bundle\DoctrineNamingStrategy\NamingStrategy\UnderscoredClassNamespacePrefix;

class UnderscoredClassNamespacePrefixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function classToTableNameLowercase()
    {
        $strategy = new UnderscoredClassNamespacePrefix(array(
            'map' => array(
                'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
            )
        ));

        $this->assertSame('my_prefix_some_entity', $strategy->classToTableName('DailyInfo\\Bundle\\TestNamespace\\Entity\\SomeEntity'));
    }

    /**
     * @test
     */
    public function classToTableNameUppercase()
    {
        $strategy = new UnderscoredClassNamespacePrefix(array(
            'map' => array(
                'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
            ),
            'case' => CASE_UPPER
        ));

        $this->assertSame('MY_PREFIX_SOME_ENTITY', $strategy->classToTableName('DailyInfo\\Bundle\\TestNamespace\\Entity\\SomeEntity'));
    }

    /**
     * @test
     */
    public function joinTableName()
    {
        $strategy = new UnderscoredClassNamespacePrefix(array(
            'map' => array(
                'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix',
                'DailyInfo\\Bundle\\TestNamespace\\Other' => 'my_other_prefix'
            )
        ));

        $this->assertSame('my_prefix_some_entity_my_other_prefix_some_entity_field_name', $strategy->joinTableName(
            'DailyInfo\\Bundle\\TestNamespace\\Entity\\SomeEntity',
            'DailyInfo\\Bundle\\TestNamespace\\Other\\SomeEntity',
            'fieldName'
        ));
    }

    /**
     * @test
     *
     * @expectedException \RuntimeException
     */
    public function invalidConfiguration()
    {
        new UnderscoredClassNamespacePrefix(array(
            'map' => array(
                'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
            ),
            'blacklist' => array(
                'Test\\Bundle'
            ),
            'whitelist' => array(
                'Test\\Bundle2'
            )
        ));
    }

    /**
     * @test
     */
    public function blacklisted()
    {
        $strategy = new UnderscoredClassNamespacePrefix(array(
            'map' => array(
                'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
            ),
            'blacklist' => array(
                'DailyInfo\\Bundle'
            )
        ));

        $this->assertSame('some_entity', $strategy->classToTableName('DailyInfo\\Bundle\\DoctrineNamingStrategy\\Entity\\SomeEntity'));
    }

    /**
     * @test
     */
    public function whitelisted()
    {
        $strategy = new UnderscoredClassNamespacePrefix(array(
            'map' => array(
                'DailyInfo\\Bundle\\TestNamespace\\Entity' => 'my_prefix'
            ),
            'whitelist' => array(
                'Test\\Bundle2'
            )
        ));

        $this->assertSame('some_entity', $strategy->classToTableName('DailyInfo\\Bundle\\DoctrineNamingStrategy\\Entity\\SomeEntity'));
    }
}
