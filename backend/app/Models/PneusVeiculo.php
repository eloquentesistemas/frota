<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PneusVeiculo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["veiculo_id","quilometragem","quantidade","valor"];

    protected $searchableFields = ["pneus_veiculos.veiculo_id","pneus_veiculos.quilometragem","pneus_veiculos.quantidade","pneus_veiculos.valor"];


   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
