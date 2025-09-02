<?php
        namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pessoa extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = ["nome","cpf_cnpj","tipo","telefone","numero_cnh","categoria_cnh","vencimento_cnh","situacao","cidade_id","rua","numero","descritivo"];

    protected $searchableFields = ["pessoas.nome","pessoas.cpf_cnpj","pessoas.tipo","pessoas.telefone","pessoas.numero_cnh","pessoas.categoria_cnh","pessoas.vencimento_cnh","pessoas.situacao","pessoas.cidade_id","pessoas.rua","pessoas.numero","pessoas.descritivo"];


   public function Cidade()
    {
        return $this->belongsTo(Cidade::class);
    }

    public function PessoaVeiculo()
    {
        return $this->hasMany(PessoaVeiculo::class);
    }


}
