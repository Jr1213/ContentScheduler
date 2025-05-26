<?php

namespace App\Filament\Resources;

use App\Enums\PostStatusEnum;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Model;
use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('Title')->required()->maxLength(255),
                Textarea::make('content')->label('Content')->characterLimit(1000)->required(),
                FileUpload::make('image_url')->label('Image')->nullable()->image()->maxSize(2048)->directory('posts')->disk('public')->visibility('public'),
                DateTimePicker::make('scheduled_time')->label('Scheduled Time')->format('Y-m-d H:i:s')->required()->afterOrEqual(now()),
            ])
            ->columns(1);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereUserId(request()->user()->id);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('content'),
                TextColumn::make('scheduled_time'),
                ImageColumn::make('image_url')->circular()->label('Image'),
                TextColumn::make('status')
                    ->badge()->color(fn(PostStatusEnum $state): string => match ($state) {
                        PostStatusEnum::DRAFT => 'danger',
                        PostStatusEnum::PUBLISHED => 'success',
                        PostStatusEnum::SCHEDULED => 'gray',
                    })




            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            // 'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
