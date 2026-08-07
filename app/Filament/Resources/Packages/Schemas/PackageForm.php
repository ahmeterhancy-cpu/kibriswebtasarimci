<?php

namespace App\Filament\Resources\Packages\Schemas;

use App\Models\Package;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([

                Tab::make('Türkçe')->schema([
                    Select::make('type')
                        ->label('Paket türü')
                        ->options(Package::TYPES)
                        ->default(Package::TYPE_PROJECT)
                        ->required()
                        ->live()
                        ->helperText('Bakım paketleri Paketler sayfasının alt bölümünde aylık olarak listelenir; teklif sihirbazına ve ana sayfa vitrinine girmez.')
                        ->columnSpanFull(),

                    TextInput::make('name')
                        ->label('Paket adı')
                        ->required()
                        ->maxLength(190)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (?string $state, callable $set, string $operation) {
                            if ($operation === 'create' && filled($state)) {
                                $set('slug', Str::slug($state, '-', 'tr'));
                            }
                        }),

                    TextInput::make('slug')
                        ->label('Kod (slug)')
                        ->required()
                        ->maxLength(190)
                        ->unique(ignoreRecord: true)
                        ->helperText('Teklif sihirbazı bu kodu kullanır: /teklif-al?paket=<slug>.'),

                    Textarea::make('tagline')->label('Tek cümlelik tanım')->rows(2)->maxLength(500),

                    TextInput::make('delivery')->label('Teslim süresi')->placeholder('8-12 iş günü')->maxLength(190),

                    TagsInput::make('features')
                        ->label('Kapsam maddeleri')
                        ->placeholder('Madde yazıp Enter\'a basın')
                        ->columnSpanFull(),
                ])->columns(2),

                Tab::make('English')->schema([
                    TextInput::make('name_en')->label('Package name')->maxLength(190),
                    Textarea::make('tagline_en')->label('One-line description')->rows(2)->maxLength(500),
                    TextInput::make('delivery_en')->label('Delivery time')->placeholder('8-12 working days')->maxLength(190),
                    TagsInput::make('features_en')->label('Scope items')->columnSpanFull(),
                ])->columns(2),

                Tab::make('Fiyat')->schema([
                    Section::make('Fiyatlar')
                        ->description('Tutarları KDV hariç, sade sayı olarak girin (örn. 17900).')
                        ->schema([
                            TextInput::make('price')
                                ->label('Fiyat (panelsiz)')
                                ->numeric()
                                ->minValue(0)
                                ->suffix('₺')
                                ->helperText('E-ticaret paketlerinde tek fiyat budur.'),

                            TextInput::make('price_with_panel')
                                ->label('Fiyat (panelli)')
                                ->numeric()
                                ->minValue(0)
                                ->suffix('₺')
                                ->helperText('Boş bırakılırsa panelsiz/panelli seçici bu pakette çalışmaz.'),

                            TextInput::make('price_regular')
                                ->label('Normal fiyat (üstü çizili)')
                                ->numeric()
                                ->minValue(0)
                                ->suffix('₺')
                                ->helperText('Kampanya vurgusu için. Boş bırakılabilir.'),

                            TextInput::make('currency')->label('Para birimi')->default('₺')->maxLength(8),
                        ])->columns(2),
                ]),

                Tab::make('Yayın')->schema([
                    Toggle::make('is_active')->label('Yayında')->default(true),
                    Toggle::make('is_popular')
                        ->label('Öne çıkar')
                        ->helperText('Kartta kırmızı çerçeve ve "En çok tercih edilen" rozeti gösterilir.'),
                    Toggle::make('is_ecommerce')
                        ->label('E-ticaret paketi')
                        ->helperText('İşaretlenirse paketler sayfasının koyu E-Ticaret bölümünde listelenir.'),
                    TextInput::make('sort_order')->label('Sıra')->numeric()->default(0),

                    CheckboxList::make('project_types')
                        ->label('Teklif sihirbazında hangi proje türlerinde çıksın')
                        ->options(Package::PROJECT_TYPES)
                        ->columns(2)
                        ->columnSpanFull()
                        ->helperText('Hiçbiri seçilmezse paket sihirbazda hiç görünmez; yalnızca Paketler sayfasında listelenir.'),
                ])->columns(2),

            ])->columnSpanFull(),
        ]);
    }
}
