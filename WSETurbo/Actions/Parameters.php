<?php declare(strict_types=1);

namespace Plugin\WSETurbo\Actions;

use App\Domain\AbstractAction;
use App\Domain\Service\Reference\ReferenceService;
use Plugin\TradeMaster\TradeMasterPlugin;

class Parameters extends AbstractAction
{
    protected function action(): \Slim\Psr7\Response
    {
        $referenceService = $this->container->get(ReferenceService::class);

        $social_netwokrs = $referenceService
            ->read([
                'type' => \App\Domain\Types\ReferenceTypeType::TYPE_SOCIAL_NETWORKS,
                'status' => true,
                'order' => ['order' => 'asc'],
            ])
            ->pluck('value.url', 'title');

        $locations = $referenceService
            ->read([
                'type' => \App\Domain\Types\ReferenceTypeType::TYPE_STORE_LOCATION,
                'status' => true,
                'order' => ['order' => 'asc'],
            ])
            ->pluck('value', 'title');

        $deliveries = $referenceService
            ->read([
                'type' => \App\Domain\Types\ReferenceTypeType::TYPE_DELIVERY,
                'status' => true,
                'order' => ['order' => 'asc'],
            ])
            ->pluck('value', 'title');

        return $this->respondWithJson([
            'status' => 200,
            'data' => [
                'common' => [
                    'title' => $this->parameter('common_title', ''),
                    'description' => $this->parameter('common_description', ''),
                    'homepage' => $this->parameter('common_homepage', ''),
                ],
                'login_type' => $this->parameter('user_login_type', 'username'),
                'params' => [
                    'logo' => $this->parameter('WSETurboPlugin_logo', ''),
                    'colors' => [
                        'bg' => $this->parameter('WSETurboPlugin_color_bg', '#F9F9F9'),
                        'text' => $this->parameter('WSETurboPlugin_color_text', '#141515'),
                        'card_bg' => $this->parameter('WSETurboPlugin_color_card_bg', '#FFFFFF'),
                        'card_text' => $this->parameter('WSETurboPlugin_color_card_text', '#141515'),
                        'btn_bg' => $this->parameter('WSETurboPlugin_color_btn_bg', '#1E74FD'),
                        'btn_text' => $this->parameter('WSETurboPlugin_color_btn_text', '#FFFFFF'),
                        'link_color' => $this->parameter('WSETurboPlugin_color_link_color', '#1E74FD'),
                        'border_bg' => $this->parameter('WSETurboPlugin_color_border_bg', '#E1E1E1'),
                    ],
                    'image_fit' => $this->parameter('WSETurboPlugin_image_fit', ''),
                    'short_name' => $this->parameter('WSETurboPlugin_short_name', ''),
                ],
                'page' => [
                    'terms' => $this->parameter('WSETurboPlugin_page_terms', ''),
                    'privacy' => $this->parameter('WSETurboPlugin_page_privacy', ''),
                ],
                'social_networks' => $social_netwokrs,
                'locations' => $locations,
                'catalog' => [
                    'sort_by' => $this->parameter('catalog_sort_by', 'order'),
                    'sort_direction' => $this->parameter('catalog_sort_direction', 'asc'),
                ],
                'deliveries' => $deliveries,
            ],
        ]);
    }
}
