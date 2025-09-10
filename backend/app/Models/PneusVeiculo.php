<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PneusVeiculo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["veiculo_id","quilometragem","quantidade","valor","aro","marca",'pessoa_id'];

    protected $searchableFields = ["pneus_veiculos.veiculo_id","pneus_veiculos.quilometragem","pneus_veiculos.quantidade","pneus_veiculos.valor","pneus_veiculos.aro","pneus_veiculos.marca"];


   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
