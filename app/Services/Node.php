<?php
namespace App\Services;

use App\Exceptions\ThirdWheelException;
use App\Models\Tree;
use Illuminate\Http\Request;

/**
 * Вузол бінарного дерева
 */
class Node
{
    const ROOT = 1;

    /**
     * Модель даних вузла
     * @var Tree|Request
     */
    private Tree $model;

    /**
     * Конструктор
     * @param int|Request|Tree $data Дані для отримання моделі вузла
     */
    public function __construct(int|Request|Tree $data)
    {
        if( is_int($data) ){
            $this->model = Tree::find($data);
        }else{
            $class = get_class($data);

            if(str_contains($class, 'Request')){
                if( isset($data->id) )
                    $this->model = Tree::find((int)$data->id);
                else
                    $this->model = Tree::find(self::ROOT);
            } else {
                $this->model = $data;
            }
        }
    }

    public function __get($property){
        if($property == 'model')
            return $this->model;
    }

    /**
     * Повернення вузла з усіма нащадками в stdClass
     * @return \stdClass
     */
    public function toObject(): \stdClass
    {
        $node = new \stdClass();
        $node->id = $this->model->id;
        $node->parent_id = $this->model->parent_id;
        $node->position = $this->model->position;
        $node->path = $this->model->path ;
        $node->level = $this->model->level;

        $node->children = [];

        $children = $this->model->children();

        foreach($children as $one){
            $child = new Node($one);
            $node->children[] =$child->toObject();
            unset($child);
        }

        return $node;
    }

    /**
     * Додавання дитини до вузла
     * @param int|null $position
     * @return Tree
     * @throws ThirdWheelException
     */
    public function add(int|null $position = 1):Tree
    {
        $count = $this->model->childrenCount();
        if( $count >= 2 ){
            throw new ThirdWheelException("Node id=".$this->model->id." already have 2 children");
        }elseif($count == 1){
            $child = $this->model->children()[0];
            $position = $child->position == 1 ? 2 : 1;
        }

        $node = new Tree();
        $node->parent_id = $this->model->id;
        $node->position = $position;
        $node->path = $this->model->path ;
        $node->level = $this->model->level + 1;

        $node->save(['updatePath'=>1]); // path will be corrected by model Tree

        return $node;

    }

    /**
     * Видалення вузла бінарного дерева
     * @return void
     */
    public function remove():void
    {

        Tree::where('path','like','%.'.$this->model->id.'.%')
            ->delete();

        $this->model->delete();

    }

    /**
     * Визначення найглибшого рівня нащадка
     * @return int
     */
    public function deepestLevel():int
    {

        $max = Tree::where('path','like','%.'.$this->model->id.'.%')
            ->orWhere('path','like','%.'.$this->model->id)
            ->max('level');

        if(!$max)
            $max = $this->model->level;

        return $max;

    }


}
