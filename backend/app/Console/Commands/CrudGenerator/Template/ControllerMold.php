<?php

namespace App\Console\Commands\CrudGenerator\Template;

use App\Console\Commands\CrudGenerator\Util\WriteArchive;
use App\Console\Commands\CrudGenerator\Util\WriteModelText;
use Illuminate\Support\Facades\DB;

class ControllerMold
{

    public static function buildController(string $table)
    {
        $beLongTo = "";
        $database = getenv('DB_DATABASE');

        $sql = "select COLUMN_NAME as name, DATA_TYPE as type, CHARACTER_MAXIMUM_LENGTH as length,IS_NULLABLE as is_nullable
from information_schema.COLUMNS
where TABLE_NAME = ':table'
    and TABLE_SCHEMA = ':database'
    and COLUMN_NAME not in ('created_at','updated_at','id')
    ";

        $sql = str_replace(':table', $table, $sql);
        $sql = str_replace(':database', $database, $sql);

        $columns = DB::select($sql);
        if(empty($columns)){
            echo 'The table not exists'.PHP_EOL;
            return false;
        }
        $validatedStoreUpdate = self::validatedStoreUpdate($columns);

        $columns = self::setColumns($columns);
        if(!$columns){
            echo 'The table not exists'.PHP_EOL;
            return false;
        }

        $useModel = self::useModel($table);

        $model = self::setModel($table);

        $tableSingular = self::setTableSingular($table);
        $writeModelText = WriteModelText::render(self::mold(),[':useModel'=>$useModel,':validatedStoreUpdate'=>$validatedStoreUpdate,':tableSingular'=>$tableSingular,':table'=>$table,':model'=>$model,':columns'=>$columns]);
        $dir = app_path('/Http/Controllers');
        $filename = $model.'Controller.php';
       WriteArchive::now($writeModelText,$filename,$dir);

        return true;
    }

    public static function mold(): string
    {
        $texto =   file_get_contents(__DIR__ . '/Molds/ControllerMold.mold');
        return $texto;

    }

    private static function validatedStoreUpdate(array $columns)
    {
        $scriptValidate = 'if($type=="store"){
        $request->validate(
        :validated
        );
    }else{
        $request->validate(:validated);
    }';
        $validated = "[".PHP_EOL ;

        foreach ($columns as $column) {

            $quote = "'";
            $comma = ",";
            $tab = "    ";
            $columnValidated = $tab.$tab.$tab.$quote.$column->name.$quote."=>[";


            if (strpos($column->name,'email') !== false) {
                $columnValidated .= $quote . 'email' . $quote . $comma;
            }
            if( $column->is_nullable=="YES"){
                $columnValidated .=$quote.'nullable'.$quote.$comma;
            }else{
                $columnValidated .=$quote.'required'.$quote.$comma;
            }
            if($column->length>0 || !empty($column->length)){
                $columnValidated .= $quote."max:".$column->length.$quote.$comma;
            }


            $columnValidated .=  self::SetType($column->type);



            $validated.=$columnValidated.'],'.PHP_EOL;
        }

        $validated .= $tab.$tab."]" ;

         $scriptValidate = str_replace(':validated',$validated, $scriptValidate);
         $scriptValidate = str_replace(',]',']', $scriptValidate);
         return $scriptValidate;

    }

    private static function useModel(string $table)
    {
        $use = "use App\Models\:model;";
        $names = explode('_',$table);
        $model ='';
        foreach ($names as $name) {
            $model .= ucfirst($name);
        }

        $use = str_replace(':model', rtrim($model, 's'),$use);
        return $use;
    }

    private static function setTable(array $columns)
    {

    }

    private static function setTableSingular($table)
    {
        return rtrim($table, 's');
    }

    private static function SetType($type)
    {
        $quote = "'";
        $comma = ",";
       $typeData = array(
            'tinyint' => $quote.'boolean'.$quote.$comma,
            'smallint' => '',
            'mediumint' => '',
            'int' => '',
            'integer' => '',
            'bigint' => '',
            'float' => $quote.'numeric'.$quote.$comma,
            'double' => $quote.'numeric'.$quote.$comma,
            'decimal' => $quote.'numeric'.$quote.$comma,

            'char' => $quote.'string'.$quote.$comma,
            'varchar' => $quote.'string'.$quote.$comma,
            'text' => $quote.'string'.$quote.$comma,
            'tinytext' => $quote.'string'.$quote.$comma,
            'mediumtext' => $quote.'string'.$quote.$comma,
            'longtext' => $quote.'string'.$quote.$comma,

            'date' => $quote.'date'.$quote.$comma,
            'time' => '',
            'datetime' => $quote.'boolean'.$quote.$comma,
            'timestamp' => '',
            'year' => '',

            'binary' => $quote.'boolean'.$quote.$comma,
            'varbinary' => $quote.'boolean'.$quote.$comma,
            'blob' =>$quote.'file'.$quote.$comma,
            'tinyblob' => $quote.'file'.$quote.$comma,
            'mediumblob' => $quote.'file'.$quote.$comma,
            'longblob' => $quote.'file'.$quote.$comma,

            'boolean' =>$quote.'boolean'.$quote.$comma,
            'bool' => $quote.'boolean'.$quote.$comma,
            'enum' => '',
            'set' => '',
            'json' =>  $quote.'json'.$quote.$comma,
        );

       return $typeData[$type]??"";

    }

    private static function setModel($table)
    {
        $names = explode('_',$table);
        $model ='';
        foreach ($names as $name) {
            $model .= ucfirst($name);
        }
        return rtrim($model, 's');
    }

    private static function setColumns(array $columns)
    {
        if(empty($columns)){
            return false;
        }
        $listName = [];
        foreach ($columns as $column) {
            $listName[] = $column->name;
        }
        return  json_encode($listName);

    }


}
