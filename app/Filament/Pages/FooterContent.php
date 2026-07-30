<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\SavesSettings;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class FooterContent extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettings;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3BottomLeft;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Footer İçeriği';

    protected static ?string $title = 'Footer metni';

    protected static ?int $navigationSort = 32;

    protected string $view = 'filament.pages.settings-form';

    protected function settingGroup(): string
    {
        return 'footer';
    }

    protected function settingKeys(): array
    {
        return ['footer_about', 'footer_about__en'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Footer tanıtım metni')
                    ->description('Footer\'da logonun altında görünen kısa paragraf. 1-2 cümle.')
                    ->schema([
                        Textarea::make('footer_about')->label('Metin (TR)')->rows(3)->maxLength(300),
                        Textarea::make('footer_about__en')->label('Text (EN)')->rows(3)->maxLength(300),
                    ]),
            ])
            ->statePath('data');
    }
}
