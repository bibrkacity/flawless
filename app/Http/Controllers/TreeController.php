<?php

namespace App\Http\Controllers;

use App\Services\Node;
use Illuminate\Http\Request;

/**
 * Робота з бінарним деревом
 */
class TreeController extends Controller
{

    /**
     * HTML-сторінка з бінарним деревом
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
     */
    public function render(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        try{
            $data = ['error' => 0];

            $node = new Node($request);

            $data['node'] = $node;
            $deepest = $node->deepestLevel();

            $data['levels'] = $deepest - $node->model->level ;

            $data['json'] = json_encode($node->toObject());

        } catch(\Exception $e) {
            $data['error'] = 1;
            $data['message'] = $e->getMessage();
        }

        return view('tree',['data'=>$data]);

    }

    /**
     * Додавання вузла
     * @param Request $request
     * @return string|\Illuminate\Http\RedirectResponse
     */
    public function add(Request $request): string|\Illuminate\Http\RedirectResponse
    {
        try{

            $node = new Node($request);
            $position = null;
            if(isset($request->position))
                $position = (int)$request->position;
            $node->add($position);

            $url = route('tree.render').'?id='.$node->id;

            return redirect()->to($url);

        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Видалення вузла
     * @param Request $request
     * @return int[]
     */
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
