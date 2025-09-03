<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PessoaVeiculo extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["pessoa_id","veiculo_id"];

    protected $searchableFields = ["pessoa_veiculos.pessoa_id","pessoa_veiculos.veiculo_id"];


   public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
