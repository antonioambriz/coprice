<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    // Catálogo SAT c_FormaPago (CFDI)
    const PAYMENT_METHODS = [
        '01' => 'Efectivo',
        '02' => 'Cheque nominativo',
        '03' => 'Transferencia electrónica de fondos',
        '04' => 'Tarjeta de crédito',
        '05' => 'Monedero electrónico',
        '06' => 'Dinero electrónico',
        '08' => 'Vales de despensa',
        '12' => 'Dación en pago',
        '13' => 'Pago por subrogación',
        '14' => 'Pago por consignación',
        '15' => 'Condonación',
        '17' => 'Compensación',
        '23' => 'Novación',
        '24' => 'Confusión',
        '25' => 'Remisión de deuda',
        '26' => 'Prescripción o caducidad',
        '27' => 'A satisfacción del acreedor',
        '28' => 'Tarjeta de débito',
        '29' => 'Tarjeta de servicios',
        '30' => 'Aplicación de anticipos',
        '31' => 'Intermediario pagos',
        '99' => 'Por definir',
    ];

    const CREDIT_DAYS_OPTIONS = [7, 15, 30, 60, 90];

    protected $fillable = [
        'company_name',
        'rfc',
        'contact_person',
        'email',
        'street',
        'ext_number',
        'int_number',
        'municipality',
        'state',
        'postal_code',
        'country',
        'payment_method',
        'credit_days',
        'activo',
    ];

    public function generators()
    {
        return $this->belongsToMany(Generator::class, 'client_generator_wastes')
                    ->withPivot(['sub_generator_id', 'waste_id', 'final_destination_id']);
    }

    public function wastes()
    {
        return $this->belongsToMany(Waste::class, 'client_generator_wastes')
                    ->withPivot(['generator_id', 'sub_generator_id', 'final_destination_id']);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function wastePrices()
    {
        return $this->hasMany(WastePrice::class);
    }
}
