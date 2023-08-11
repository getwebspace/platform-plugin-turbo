<?php declare(strict_types=1);

namespace Plugin\WSETurbo;

use App\Domain\AbstractPlugin;
use Psr\Container\ContainerInterface;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class WSETurboPlugin extends AbstractPlugin
{
    const NAME = 'WSETurboPlugin';
    const TITLE = 'WSE Turbo';
    const DESCRIPTION = 'Turbo App settings';
    const AUTHOR = 'Aleksey Ilyin';
    const AUTHOR_SITE = 'https://getwebspace.org';
    const VERSION = '1.0';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->setTemplateFolder(__DIR__ . '/templates');

        $this->addSettingsField([
            'label' => 'Адрес Turbo App',
            'type' => 'text',
            'name' => 'address',
        ]);
        $this->addSettingsField([
            'label' => 'Текст уведомления',
            'type' => 'text',
            'name' => 'prompt',
        ]);
        $this->addSettingsField([
            'label' => 'Короткое название магазина',
            'type' => 'text',
            'name' => 'short_name',
        ]);
        $this->addSettingsField([
            'label' => 'Логотип',
            'type' => 'text',
            'name' => 'logo',
        ]);
        $this->addSettingsField([
            'label' => 'Фоновый цвет',
            'type' => 'text',
            'name' => 'primary_bg',
        ]);
        $this->addSettingsField([
            'label' => 'Основной цвет',
            'type' => 'text',
            'name' => 'primary_color',
        ]);
        $this->addSettingsField([
            'label' => 'Расположение изображения',
            'type' => 'select',
            'name' => 'image_fit',
            'args' => [
                'selected' => 'off',
                'option' => [
                    'contain' => 'contain',
                    'cover' => 'cover',
                ],
            ],
        ]);
        $this->addSettingsField([
            'label' => 'Условия использования',
            'description' => 'Полная ссылка на страницу',
            'type' => 'text',
            'name' => 'page_terms',
        ]);
        $this->addSettingsField([
            'label' => 'Политика конфиденциальности',
            'description' => 'Полная ссылка на страницу',
            'type' => 'text',
            'name' => 'page_privacy',
        ]);

        $this->setHandledRoute('common:main');
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
