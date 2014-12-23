<?php

return array(
    // 首页
    '' => 'home/index',
    'home' => 'home/index',
    'index.html' => 'home/index',
    // 首页
    'welcome' => 'home/index',
    // api别名
    'api/([a-zA-Z0-9]+)(/?.*)' => '$1/api$2',
);
