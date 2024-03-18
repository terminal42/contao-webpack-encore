
# terminal42/contao-webpack-encore

`terminal42/contao-webpack-encore` is an extension for the [Contao CMS](https://contao.org).

It allows you to integrate Encore entrypoints into Contao, either through the page layout,
through .html5-Template methods or through insert tags.

If you use Twig templates, you can use the regular Symfony Encore Bundle features.


## Installation

Choose the installation method that matches your workflow!

### Installation via Contao Manager

Search for `terminal42/contao-webpack-encore` in the Contao Manager and add it
to your installation. Apply changes to update the packages.

### Manual installation

Add a composer dependency for this bundle. Therefore, change in the project root and run the following:

```bash
composer require terminal42/contao-webpack-encore
```

Depending on your environment, the command can differ, i.e. starting with `php composer.phar …` if you do not have
composer installed globally.

Then, update the database via the `contao:migrate` command or the Contao install tool.


## Configuration

The Contao Manager Plugin will automatically try to detect an `entrypoints.json` in your public web directory.
If none is found, or you would like to manually configure it, adjust the Symfony `webpack_encore.output_path`
accordingly: https://symfony.com/bundles/WebpackEncoreBundle/current/index.html

## License

This bundle is released under the [MIT license](LICENSE)
