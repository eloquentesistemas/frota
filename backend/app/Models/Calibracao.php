<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calibracao extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["data","pessoa_id","veiculo_id","servico","km","valor"];

    protected $searchableFields =  ["calibracaos.data","calibracaos.pessoa_id","calibracaos.veiculo_id","calibracaos.servico","calibracaos.km","calibracaos.valor"];


   public function Pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }
   public function Veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }



}
