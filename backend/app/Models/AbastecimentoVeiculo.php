<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AbastecimentoVeiculo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["veiculo_id","quilometragem","litros","valor",'pessoa_id','numero_nota',"tipo","descritivo"];

    protected $searchableFields = ["abastecimento_veiculos.veiculo_id","abastecimento_veiculos.quilometragem","abastecimento_veiculos.litros","abastecimento_veiculos.valor"];


   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
