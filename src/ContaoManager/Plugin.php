<?php

declare(strict_types=1);

namespace Terminal42\WebpackEncoreBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;
use Terminal42\WebpackEncoreBundle\Terminal42WebpackEncoreBundle;

class Plugin implements BundlePluginInterface, ExtensionPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            new BundleConfig(WebpackEncoreBundle::class),
            (new BundleConfig(Terminal42WebpackEncoreBundle::class))->setLoadAfter([ContaoCoreBundle::class, WebpackEncoreBundle::class]),
        ];
    }

    public function getExtensionConfig($extensionName, array $extensionConfigs, ContainerBuilder $container): array
    {
        if ('webpack_encore' !== $extensionName) {
            return $extensionConfigs;
        }

        if (empty($extensionConfigs) && (new Filesystem())->exists($container->getParameter('kernel.project_dir').'/web/layout/entrypoints.json')) {
            $extensionConfigs = [
                ['output_path' => '%kernel.project_dir%/web/layout'],
            ];
        }

        $container->setParameter('terminal42_webpack_encore.output_path', $extensionConfigs[0]['output_path'].'/entrypoints.json');

        return $extensionConfigs;
    }
}
