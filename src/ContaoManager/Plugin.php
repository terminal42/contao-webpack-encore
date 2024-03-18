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
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\Finder;
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

        $outputPath = null;

        foreach (array_reverse($extensionConfigs) as $config) {
            if (isset($config['output_path'])) {
                $outputPath = $config['output_path'];
                break;
            }
        }

        if (null === $outputPath) {
            $projectDir = $container->getParameter('kernel.project_dir');
            $outputPath = $this->findOutputPath($projectDir) ?: '%kernel.project_dir%/public/build';

            if (empty($extensionConfigs)) {
                $extensionConfigs[] = [];
            }

            $extensionConfigs[0]['output_path'] = $outputPath;
        }

        $container->setParameter('terminal42_webpack_encore.entrypoints_json', $outputPath.'/entrypoints.json');

        return $extensionConfigs;
    }

    private function findOutputPath(string $projectDir): ?string
    {
        $publicDir = $this->findPublicDir($projectDir);

        $finder = new Finder();
        $finder->files()->name('entrypoints.json')->depth('< 2')->in($publicDir);

        if (!$finder->hasResults()) {
            return null;
        }

        $files = $finder->getIterator();
        $files->rewind();

        return '%kernel.project_dir%/'.Path::makeRelative($files->current()->getPath(), $projectDir);
    }

    private function findPublicDir(string $projectDir): string
    {
        $fs = new Filesystem();

        if ($fs->exists($composerJsonFilePath = Path::join($projectDir, 'composer.json'))) {
            $composerConfig = json_decode(file_get_contents($composerJsonFilePath), true, 512, JSON_THROW_ON_ERROR);

            if (null !== ($publicDir = $composerConfig['extra']['public-dir'] ?? null)) {
                return Path::join($projectDir, $publicDir);
            }
        }

        if ($fs->exists($publicDir = Path::join($projectDir, 'public'))) {
            return $publicDir;
        }

        if ($fs->exists($publicDir = Path::join($projectDir, 'web'))) {
            return $publicDir;
        }

        return Path::join($projectDir, 'public');
    }
}
