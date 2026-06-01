<?php

namespace App\Enums;

enum SiteLeadSegment: string
{
    case HEALTHCARE = 'healthcare';
    case REAL_ESTATE = 'real_estate';
    case RETAIL = 'retail';
    case EDUCATION = 'education';
    case INDUSTRY = 'industry';
    case SERVICES = 'services';
    case TECHNOLOGY = 'technology';
    case FINANCE = 'finance';
    case LEGAL = 'legal';
    case FOOD = 'food';
    case AUTOMOTIVE = 'automotive';
    case LOGISTICS = 'logistics';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::HEALTHCARE => 'Saúde',
            self::REAL_ESTATE => 'Imobiliário',
            self::RETAIL => 'Varejo',
            self::EDUCATION => 'Educação',
            self::INDUSTRY => 'Indústria',
            self::SERVICES => 'Serviços',
            self::TECHNOLOGY => 'Tecnologia',
            self::FINANCE => 'Financeiro',
            self::LEGAL => 'Jurídico',
            self::FOOD => 'Alimentação',
            self::AUTOMOTIVE => 'Automotivo',
            self::LOGISTICS => 'Logística',
            self::OTHER => 'Outro',
        };
    }

    public static function labelFor(?string $value): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        $normalized = strtolower(trim(str_replace('-', '_', $value)));

        return self::tryFrom($normalized)?->label() ?? $value;
    }
}
