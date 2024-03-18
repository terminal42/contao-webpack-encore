<?php

declare(strict_types=1);

namespace Terminal42\WebpackEncoreBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use Contao\StringUtil;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;

/**
 * @Hook("generatePage")
 */
class GeneratePageListener
{
    private EntryFilesTwigExtension $twigExtension;

    public function __construct(EntryFilesTwigExtension $twigExtension)
    {
        $this->twigExtension = $twigExtension;
    }

    public function __invoke(PageModel $pageModel, LayoutModel $layoutModel, PageRegular $pageRegular): void
    {
        $entrypoints = StringUtil::deserialize($layoutModel->encoreEntrypoints);

        if (empty($entrypoints) || !\is_array($entrypoints)) {
            return;
        }

        $styles = [];
        $scripts = [];

        foreach ($entrypoints as $entryName) {
            $styles[] = $this->twigExtension->renderWebpackLinkTags($entryName);
            $scripts[] = $this->twigExtension->renderWebpackScriptTags($entryName);
        }

        $styles = array_filter($styles);
        $scripts = array_filter($scripts);

        if (!empty($styles)) {
            $GLOBALS['TL_HEAD'][] = implode('', $styles);
        }

        if (!empty($scripts)) {
            $GLOBALS[$layoutModel->encoreScriptPosition][] = implode('', $scripts);
        }
    }
}
