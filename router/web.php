<?php

//Param1 : 传值方式： GET/POST 大写
//Param2 : 参数：  / 斜杠就是没有参数（默认参数）
//Param3 : 命名空间+控制器@方法名：  这里我们后面会在dispath方法中定义默认在App/Controller、所以可以省略一部分命名空间
$r->addRoute('GET', '/', 'Back/view/IndexController@showIndex');
$r->addRoute('POST', '/post', 'Back/view/IndexController@job');

//这个时候再打印getUrl中的self::$routeInfo,结果：
//php
//array(3) { [0]=> int(1) [1]=> string(35) "Back/view/IndexController@showIndex" [2]=> array(0) { } }