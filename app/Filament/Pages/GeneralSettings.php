<?php

namespace App\Filament\Pages;

use App\Filament\Pages\Concerns\SavesSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class GeneralSettings extends Page implements HasForms
{
    use InteractsWithForms;
    use SavesSettings;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static UnitEnum|string|null $navigationGroup = 'Sistem';

    protected static ?string $navigationLabel = 'Genel Ayarlar';

    protected static ?string $title = 'Genel ayarlar ve bakım modu';

    protected static ?int $navigationSort = 33;

    protected string $view = 'filament.pages.settings-form';

    protected function settingGroup(): string
    {
        return 'general';
    }

    protected function settingKeys(): array
    {
        return [
            'maintenance_mode',
            'maintenance_title', 'maintenance_title__en',
            'maintenance_text', 'maintenance_text__en',
        ];
    }

    /** Ayar tabloda '1'/'0' metni; Toggle boole bekler. */
    protected function castFromStorage(array $values): array
    {
        $values['maintenance_mode'] = ($values['maintenance_mode'] ?? '0') === '1';

        return $values;
    }

    protected function castToStorage(array $values): array
    {
        $values['maintenance_mode'] = ! empty($values['maintenance_mode']) ? '1' : '0';

        return $values;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bakım modu')
                    ->description('Açıkken ziyaretçiler "yapım aşamasında" ekranını görür. Admin paneli etkilenmez; buradan kapatabilirsiniz.')
                    ->schema([
                        Toggle::make('maintenance_mode')
                            ->label('Bakım modu açık')
                            ->helperText('Arama motorlarına 503 döner; uzun süre açık bırakmayın.')
                            ->columnSpanFull(),

                        TextInput::make('maintenance_title')->label('Başlık (TR)')->maxLength(120),
                        TextInput::make('maintenance_title__en')->label('Heading (EN)')->maxLength(120),
                        Textarea::make('maintenance_text')->label('Metin (TR)')->rows(2)->maxLength(300),
                        Textarea::make('maintenance_text__en')->label('Text (EN)')->rows(2)->maxLength(300),
                    ])->columns(2),
            ])
            ->statePath('data');
    }
}
