<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tree extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table='tree';

    public function childrenCount():int
    {
        return Tree::where('parent_id',$this->id)
            ->count();
    }

    public function children():array
    {
        $children = [];
        $result = Tree::where('parent_id',$this->id)
            ->orderBy('position','asc')
            ->get();

        foreach($result as $one){
            $children[] = $one;
        }

        return $children;
    }

    /**
     * Збереження моделі в базі даних
     * @param array $options
     * @return bool
     */
    public /*override*/ function save(array $options = [] )
    {
        $result = parent::save($options);
        if($result && isset($options['updatePath'])){
            $this->path = $this->id.'.'.$this->path;
            $result = parent::save($options);
        }
        return $result;
    }

}
