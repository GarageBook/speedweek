<?php
namespace App\Filament\Resources\MailTemplates\Schemas;
use Filament\Forms\Components\DatePicker; use Filament\Forms\Components\DateTimePicker; use Filament\Forms\Components\Select; use Filament\Forms\Components\TextInput; use Filament\Forms\Components\Textarea; use Filament\Forms\Components\Toggle; use Filament\Schemas\Schema;
class MailTemplateForm { public static function configure(Schema $schema): Schema { return $schema->components([TextInput::make('name')->required(), TextInput::make('subject')->required(), Textarea::make('body')->required()->columnSpanFull(), Select::make('type')->options(['onboarding'=>'Onboarding','reminder'=>'Reminder','bulk'=>'Bulk','payment'=>'Payment','general'=>'General'])->required(), Toggle::make('is_active')]); } }
