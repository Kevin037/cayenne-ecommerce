<?php

namespace App\Filament\Resources\TestimonialResource\Pages;

use App\Filament\Resources\TestimonialResource;
use App\Models\TransactionDetail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = TestimonialResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $transaction_detail = TransactionDetail::find($data['transaction_detail_id']);
        $data['transaction_detail_key'] = $transaction_detail->transaction_detail_key;
        $data['product_id'] = $transaction_detail->product_id;
        return $data;
    }
}
