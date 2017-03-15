<?php
/*
 * This file is part of the Doctrine Naming Strategy Bundle, an DailyInfo project.
 *
 * (c) 2016 DailyInfo
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DailyInfo\Bundle\DoctrineNamingStrategy;

use DailyInfo\Bundle\DoctrineNamingStrategy\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineNamingStrategyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new Extension();
    }
}
