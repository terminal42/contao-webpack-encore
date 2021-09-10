<?php

declare(strict_types=1);

namespace Terminal42\WebpackEncoreBundle\EventListener;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\Template;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;

/**
 * @Hook("parseTemplate")
 */
class TemplateHelperListener
{
    private EntryFilesTwigExtension $twigExtension;

    public function __construct(EntryFilesTwigExtension $twigExtension)
    {
        $this->twigExtension = $twigExtension;
    }

    public function __invoke(Template $template): void
    {
        if (!isset($template->encore_entry_js_files)) {
            $template->encore_entry_js_files = function (...$args) {
                return $this->twigExtension->getWebpackJsFiles(...$args);
            };
        }

        if (!isset($template->encore_entry_css_files)) {
            $template->encore_entry_css_files = function (...$args) {
                return $this->twigExtension->getWebpackCssFiles(...$args);
            };
        }

        if (!isset($template->encore_entry_script_tags)) {
            $template->encore_entry_script_tags = function (...$args) {
                return $this->twigExtension->renderWebpackScriptTags(...$args);
            };
        }

        if (!isset($template->encore_entry_link_tags)) {
            $template->encore_entry_link_tags = function (...$args) {
                return $this->twigExtension->renderWebpackLinkTags(...$args);
            };
        }
    }
}
