<?php

declare(strict_types=1);

namespace Terminal42\WebpackEncoreBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;

/**
 * @Hook("replaceInsertTags")
 */
class InsertTagsListener
{
    private EntryFilesTwigExtension $twigExtension;

    public function __construct(EntryFilesTwigExtension $twigExtension)
    {
        $this->twigExtension = $twigExtension;
    }

    /**
     * @return string|false
     */
    public function __invoke(string $tag)
    {
        $args = explode('::', $tag);
        $name = array_shift($args);

        switch ($name) {
            case 'encore_entry_script_tags':
                return $this->twigExtension->renderWebpackScriptTags(...$args);

            case 'encore_entry_link_tags':
                return $this->twigExtension->renderWebpackLinkTags(...$args);
        }

        return false;
    }
}
