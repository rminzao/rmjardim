<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteDataSeeder extends Seeder
{
    public function run(): void
    {
        // Site Settings
        $settings = [
            ['key' => 'logo_text', 'value' => 'RM Jardim'],
            ['key' => 'hero_badge', 'value' => '🌿 Transformamos seu espaço em um paraíso verde'],
            ['key' => 'hero_title', 'value' => 'Jardins que'],
            ['key' => 'hero_title_highlight', 'value' => 'Encantam'],
            ['key' => 'hero_description', 'value' => 'Especialistas em paisagismo, manutenção de jardins e projetos personalizados. Deixe a natureza fazer parte do seu dia a dia.'],
            ['key' => 'hero_button_primary', 'value' => 'Solicitar Orçamento Grátis'],
            ['key' => 'hero_button_secondary', 'value' => 'Ver Trabalhos'],
            ['key' => 'services_tag', 'value' => 'Nossos Serviços'],
            ['key' => 'services_title', 'value' => 'O que oferecemos'],
            ['key' => 'services_description', 'value' => 'Soluções completas em jardinagem e paisagismo para residências e empresas.'],
            ['key' => 'portfolio_tag', 'value' => 'Portfólio'],
            ['key' => 'portfolio_title', 'value' => 'Trabalhos Realizados'],
            ['key' => 'portfolio_description', 'value' => 'Confira alguns dos nossos projetos e transformações que fizemos para nossos clientes.'],
            ['key' => 'contact_tag', 'value' => 'Orçamento'],
            ['key' => 'contact_title', 'value' => 'Solicite seu Orçamento'],
            ['key' => 'contact_description', 'value' => 'Preencha o formulário abaixo e entraremos em contato em breve para conversar sobre seu projeto.'],
            ['key' => 'footer_description', 'value' => 'Transformando espaços em jardins dos sonhos. Paisagismo profissional com dedicação e qualidade.'],
            ['key' => 'footer_phone', 'value' => '(11) 91137-2201'],
            ['key' => 'footer_email', 'value' => 'contato@rmjardim.com'],
            ['key' => 'footer_address', 'value' => 'Limeira, São Paulo'],
            ['key' => 'footer_copyright', 'value' => 'RM Jardim'],
            ['key' => 'instagram_url', 'value' => '#'],
            ['key' => 'facebook_url', 'value' => '#'],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // Services
        $services = [
            ['icon' => 'TreeDeciduous', 'title' => 'Paisagismo', 'description' => 'Projetos personalizados para transformar seu espaço em um ambiente único e acolhedor.', 'order' => 1],
            ['icon' => 'Scissors', 'title' => 'Poda e Manutenção', 'description' => 'Manutenção regular de gramados, arbustos e árvores para manter seu jardim sempre bonito.', 'order' => 2],
            ['icon' => 'Flower2', 'title' => 'Plantio de Flores', 'description' => 'Criação de canteiros coloridos com flores sazonais para alegrar seu jardim.', 'order' => 3],
            ['icon' => 'Droplets', 'title' => 'Irrigação', 'description' => 'Instalação de sistemas de irrigação automatizados para economia de água.', 'order' => 4],
            ['icon' => 'Shovel', 'title' => 'Preparação de Solo', 'description' => 'Análise e preparação do solo para garantir o melhor desenvolvimento das plantas.', 'order' => 5],
            ['icon' => 'Sun', 'title' => 'Consultoria', 'description' => 'Orientação especializada para cuidar do seu jardim da melhor forma.', 'order' => 6],
        ];

        foreach ($services as $service) {
            DB::table('services')->updateOrInsert(
                ['title' => $service['title']],
                array_merge($service, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}