<?php

namespace App\Filament\Resources\PendaftaranResource\Widgets;

use App\Filament\Resources\PendaftaranResource;
use App\Filament\Walisantri\Resources\WalisantriResource;
use App\Models\Walisantri;
use Egulias\EmailValidator\Parser\Comment;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\ActionsPosition;

class TahapPertama extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->query(
                Walisantri::where('user_id',Auth::user()->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('placeholder')
                ->label('Mulai Pendaftaran Tahap 1'),
            ])
            ->actions([
                Action::make('Mulai Proses Pendaftaran')
                    ->url(fn (Walisantri $record): string => PendaftaranResource::getUrl('edit', ['record' => $record]))
                    ->button(),

                    Action::make('Tambah Calon Santri')
                    ->url(fn (Walisantri $record): string => PendaftaranResource::getUrl('edit', ['record' => $record]))
                    ->button()

            ], position: ActionsPosition::BeforeColumns);
    }


}
