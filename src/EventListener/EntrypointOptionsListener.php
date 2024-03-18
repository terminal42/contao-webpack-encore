<?php

declare(strict_types=1);

namespace Terminal42\WebpackEncoreBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Callback;

/**
 * @Callback(table="tl_layout", target="fields.encoreEntrypoints.options")
 */
class EntrypointOptionsListener
{
    private string $entrypointJsonPath;

    public function __construct(string $entrypointJsonPath)
    {
        $this->entrypointJsonPath = $entrypointJsonPath;
    }

    public function __invoke(): array
    {
        if (!file_exists($this->entrypointJsonPath)) {
            return [];
        }

        $json = json_decode(file_get_contents($this->entrypointJsonPath), true);

        if (null === $json || !isset($json['entrypoints']) || !\is_array($json['entrypoints'])) {
            return [];
        }

        return array_keys($json['entrypoints']);
    }
}
