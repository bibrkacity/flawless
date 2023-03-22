<?php

namespace App\Http\Controllers;

use App\Services\Node;
use Illuminate\Http\Request;

/**
 * Робота з бінарним деревом
 */
class TreeController extends Controller
{

    public function render(Request $request): array
    {
        $data = [];
        $node = $this->node($request);


        return view('tree',['data'=>$data]);

    }

    public function node(Request $request): array
    {
        try{

            $response = [
                'error'=>0,
            ];

            $node = new Node($request);
            $response['data'] = $node->toJson();

        } catch(\Exception $e) {
            $response['error'] = 1;
            $response['message'] = $e->getMessage();
        }

        return $response;

    }

    public function add(Request $request): array
    {
        try{

            $response = [
                'error'=>0,
            ];

            $node = new Node($request);
            $position = null;
            if(isset($request->position))
                $position = (int)$request->position;
            $response['data'] = $node->add($position);

        } catch(\Exception $e) {
            $response['error'] = 1;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

    public function remove(Request $request): array
    {
        try{

            $response = [
                'error'=> 0,
            ];

            $node = new Node($request);

            $node->remove();

        } catch(\Exception $e) {
            $response['error'] = 1;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }

}
