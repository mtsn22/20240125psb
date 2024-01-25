<?php

namespace App\Filament\Pages;


use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{

    use BaseDashboard\Concerns\HasFiltersForm;
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        //AYAH KANDUNG
                        Placeholder::make('')
                            ->content(new HtmlString('<div class="border-b">
                                         <p class="text-2xl">SELAMAT DATANG</p>
                                     </div>')),

                        Placeholder::make('')
                            ->content(new HtmlString('<div>
                                         <p>Butuh bantuan?</p>
                                         <p>Silakan mengubungi admin dengan klik link di bawah ini:</p>
                                         <p><a href="https://wa.me/6282210862400">> Link Admin Putra</a></p>
                                         <p><a href="https://wa.me/6281232171109">> Link Admin Putri</a></p>
                                     </div>')),

                    ])
            ]);
    }

}

