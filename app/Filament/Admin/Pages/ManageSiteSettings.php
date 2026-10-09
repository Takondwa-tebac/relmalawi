<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $content
 */
class ManageSiteSettings extends Page
{
    /**
     * The settings this page manages (all stored as strings in the settings table).
     *
     * @var list<string>
     */
    public const KEYS = [
        'banner_text', 'contact_email', 'footer_name', 'est_year', 'codes_count', 'codes_pattern',
        'home_hero_title_tail', 'home_hero_secondary',
    ];

    /**
     * Defaults mirror the fallbacks in HandleInertiaRequests.
     *
     * @var array<string, string>
     */
    public const DEFAULTS = [
        'banner_text' => "REL is building the infrastructure behind Malawi's next media games.",
        'contact_email' => 'hello@relmalawi.com',
        'footer_name' => 'Radio Entertainment Limited',
        'est_year' => '2024',
        'codes_count' => '40',
        'codes_pattern' => '*4342*{n}#',
        'home_hero_title_tail' => 'Media Games.',
        'home_hero_secondary' => 'We work with radio stations and media partners to bring trusted gaming products to new audiences through the reach and influence of broadcast media.',
    ];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Site settings';

    protected static ?int $navigationSort = 90;

    protected static ?string $title = 'Site settings';

    protected static ?string $slug = 'site-settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->can('manage site settings') ?? false;
    }

    public function mount(): void
    {
        $this->form->fill(
            collect(self::KEYS)
                ->mapWithKeys(fn (string $key) => [$key => Setting::get($key, self::DEFAULTS[$key])])
                ->all(),
        );
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Textarea::make('banner_text')
                    ->label('Banner text')
                    ->helperText('Shown in the announcement banner at the top of every page.')
                    ->required()
                    ->rows(2)
                    ->maxLength(255),
                TextInput::make('contact_email')
                    ->label('Contact email')
                    ->helperText('Public contact address; new contact-form messages are also emailed here.')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('footer_name')
                    ->label('Footer name')
                    ->required()
                    ->maxLength(120),
                TextInput::make('est_year')
                    ->label('Established year')
                    ->required()
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100)
                    ->length(4),
                TextInput::make('codes_count')
                    ->label('Codes in the marquee')
                    ->helperText('How many shortcodes the scrolling marquee displays.')
                    ->required()
                    ->integer()
                    ->minValue(1)
                    ->maxValue(200),
                TextInput::make('codes_pattern')
                    ->label('Code pattern')
                    ->helperText('Use {n} where the station number goes, e.g. *4342*{n}#')
                    ->required()
                    ->maxLength(60)
                    ->regex('/^[0-9*#]*\{n\}[0-9*#]*$/')
                    ->validationMessages(['regex' => 'The code pattern must contain {n} and only digits, * or #.']),
                TextInput::make('home_hero_title_tail')
                    ->label('Home hero: last headline line')
                    ->helperText('The third line under "Umoja Promo" on the home page. The first two lines are edited under Pages.')
                    ->required()
                    ->maxLength(80),
                Textarea::make('home_hero_secondary')
                    ->label('Home hero: second paragraph')
                    ->rows(3)
                    ->maxLength(400),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Save settings')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (self::KEYS as $key) {
            Setting::put($key, isset($data[$key]) ? (string) $data[$key] : null);
        }

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
