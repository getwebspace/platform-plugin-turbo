<?php declare(strict_types=1);

namespace Plugin\WSETurbo;

use App\Domain\Plugin\AbstractLegacyPlugin;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class WSETurboPlugin extends AbstractLegacyPlugin
{
    const NAME = 'WSETurboPlugin';
    const TITLE = 'WSE Turbo';
    const DESCRIPTION = 'Turbo App settings';
    const AUTHOR = 'Aleksey Ilyin';
    const AUTHOR_SITE = 'https://getwebspace.org';
    const VERSION = '1.0.0';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->setTemplateFolder(__DIR__ . '/templates');

        $this->addSettingsField([
            'label' => 'Turbo App host',
            'type' => 'text',
            'name' => 'host',
        ]);

        $this->addSettingsField([
            'label' => 'Short title',
            'type' => 'text',
            'name' => 'short_name',
        ]);

        $this->addSettingsField([
            'label' => 'Text message',
            'type' => 'text',
            'name' => 'prompt',
            'args' => [
                'placeholder' => 'Check out our mobile web app!',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Logo image (URL)',
            'type' => 'text',
            'name' => 'logo',
        ]);

        $this->addSettingsField([
            'label' => 'Background color',
            'type' => 'color',
            'name' => 'color_bg',
            'args' => [
                'value' => '#F9F9F9',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Text color',
            'type' => 'color',
            'name' => 'color_text',
            'args' => [
                'value' => '#141515',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Card background color',
            'type' => 'color',
            'name' => 'color_card_bg',
            'args' => [
                'value' => '#FFFFFF',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Card text color',
            'type' => 'color',
            'name' => 'color_card_text',
            'args' => [
                'value' => '#141515',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Primary background color',
            'type' => 'color',
            'name' => 'color_btn_bg',
            'args' => [
                'value' => '#1E74FD',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Primary text color',
            'type' => 'color',
            'name' => 'color_btn_text',
            'args' => [
                'value' => '#FFFFFF',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Border color',
            'type' => 'color',
            'name' => 'color_border_bg',
            'args' => [
                'value' => '#E1E1E1',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Link color',
            'type' => 'color',
            'name' => 'color_link_color',
            'args' => [
                'value' => '#1E74FD',
            ]
        ]);

        $this->addSettingsField([
            'label' => 'Image fit',
            'type' => 'select',
            'name' => 'image_fit',
            'args' => [
                'selected' => 'off',
                'option' => [
                    'contain' => 'Contain',
                    'cover' => 'Cover',
                ],
            ],
        ]);

        $this->addSettingsField([
            'label' => 'Terms of Use (URL)',
            'type' => 'text',
            'name' => 'page_terms',
        ]);

        $this->addSettingsField([
            'label' => 'Privacy Policy (URL)',
            'type' => 'text',
            'name' => 'page_privacy',
        ]);

        // turbo app load parameters
        $this
            ->map([
                'methods' => ['get'],
                'pattern' => '/api/turbo/parameters',
                'handler' => \Plugin\WSETurbo\Actions\Parameters::class,
            ])
            ->setName('api:turbo:parameters');

        $this->setHandledRoute('common:main');
    }

    public function before(Request $request, string $routeName): void
    {
        // nothing
    }

    public function after(Request $request, Response $response, string $routeName): Response
    {
        $body = $response->getBody()->__toString();
        $pos = strpos($body, '<body>');

        if ($pos !== false) {
            $response->getBody()->rewind();
            $response->getBody()->write(implode('', [
                substr($body, 0, $pos + 6),
                $this->render('prompt.twig'),
                substr($body, $pos + 6),
            ]));
        }

        return $response;
    }
}
