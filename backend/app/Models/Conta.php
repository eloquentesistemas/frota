<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conta extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["data_ocorrido","nome","modalidade","natureza_financeira_id","valor","parcelas","descritivo"];

    protected $searchableFields = ["contas.data_ocorrido","contas.nome","contas.modalidade","contas.natureza_financeira_id","contas.valor","contas.parcelas","contas.descritivo"];


   public function NaturezaFinanceira()
    {
        return $this->belongsTo(NaturezaFinanceira::class);
    }

    public function Pagamento()
    {
        return $this->hasMany(Pagamento::class);
    }


}
