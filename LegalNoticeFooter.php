<?php

/**
 * Custom footer with the privacy policy for dutch locale environment.
 */

declare(strict_types=1);

namespace LegalNoticeFooterNameSpace;

use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Module\ModuleFooterInterface;
use Fisharebest\Webtrees\Module\ModuleFooterTrait;
use Fisharebest\Webtrees\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LegalNoticeFooter extends AbstractModule implements ModuleCustomInterface, ModuleFooterInterface {
    use ModuleCustomTrait;
    use ModuleFooterTrait;

    /**
     * @return string
     */
    public function title(): string
    {
        return I18N::translate('Legal Notice');
    }

 /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleAuthorName()
     */
    public function customModuleAuthorName(): string
    {
        return 'Lars van Ravenzwaaij';

    }
    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleVersion()
     */
    public function customModuleVersion(): string
    {
        return '1.0.1';
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleLatestVersionUrl()
     */
    public function customModuleLatestVersionUrl(): string
    {
        return 'https://github.com/LarsRabe/LegalNoticeFooter/latest-version.txt';
    }

    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleSupportUrl()
     */
    public function customModuleSupportUrl(): string
    {
        return 'https://github.com/LarsRabe/LegalNoticeFooter';
    }


    /**
     * Additional translations.
     *
     * @param string $language
     *
     * @return array<string>
     */
    public function customTranslations(string $language): array
    {
        switch ($language) {
            case 'de':
                return [
                    'Legal Notice'  => 'Impressum',
                ];
            case 'nl':
                return [
                    'Legal Notice'  => 'Juridische Kennisgeving',
                ];
            default:
                return [];
        }
    }

    /**
     * Bootstrap the module
     */
    public function boot(): void
    {
        // Register a namespace for our views.
        View::registerNamespace($this->name(), $this->resourcesFolder() . 'views/');
    }

    /**
     * Where does this module store its resources
     *
     * @return string
     */
    public function resourcesFolder(): string
    {
        return __DIR__ . '/resources/';
    }

   /**
     * A footer, to be added at the bottom of every page.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function getFooter(ServerRequestInterface $request): string
    {
        $tree = $request->getAttribute('tree');

        $url = route('module', [
            'module' => $this->name(),
            'action' => 'Page',
            'tree'   => $tree ? $tree->name() : null,
        ]);

        return view($this->name() . '::footer', ['url' => $url]);
    }

    /**
     * Generate the page that will be shown when we click the link in the footer.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function getPageAction(ServerRequestInterface $request): ResponseInterface
    {
        $page = '';
        switch (I18N::languageTag()) {
            case 'de':
                $page = '::page-de';
                break;
            case 'nl':
                $page = '::page-nl';
                break;
            default:
                $page = '::page';
                break;
        }

        return $this->viewResponse($this->name() . $page, [
            'title' => $this->title(),
            'tree'  => $request->getAttribute('tree'),
        ]);
    }
};
