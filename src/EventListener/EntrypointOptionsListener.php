<?php

declare(strict_types=1);

namespace Terminal42\WebpackEncoreBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @Callback(table="tl_layout", target="fields.encoreEntrypoints.options")
 */
class EntrypointOptionsListener
{
    private string $entrypointJsonPath;

    private ?Filesystem $filesystem;

    public function __construct(string $entrypointJsonPath, ?Filesystem $filesystem = null)
    {
        $this->entrypointJsonPath = $entrypointJsonPath;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    public function __invoke(): array
    {
        if (!$this->filesystem->exists($this->entrypointJsonPath)) {
            return [];
        }

        $json = json_decode(file_get_contents($this->entrypointJsonPath), true);

        if (null === $json || !isset($json['entrypoints']) || !\is_array($json['entrypoints'])) {
            return [];
        }

        return array_keys($json['entrypoints']);
    }
}
