<?php
namespace App\Controller\Back\View;

use libs\Request;

class IndexController
{
    public function showIndex(Request $request)
    {
        $a = $request->get('a', '0');
        dump($a);
    }

    public function job(Request $request)
    {
        $a = $request->get('a');
        $b = $request->post('b');
        dump('get.a', $a);
        dump('post.b', $b);
    }
}