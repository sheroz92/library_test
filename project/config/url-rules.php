<?php

return [
    'GET <controller:[\w-]+>' => '<controller>/index',
    'GET <controller:[\w-]+>/<id:\d+>' => '<controller>/view',
    'OPTIONS <controller:[\w-]+>' => '<controller>/options',
    'OPTIONS <controller:[\w-]+>/<id:\d+>' => '<controller>/options',
    'POST <controller:[\w-]+>' => '<controller>/create',
    'PUT <controller:[\w-]+>/<id:\d+>' => '<controller>/update',
    'DELETE <controller:[\w-]+>/<id:\d+>' => '<controller>/delete',

    '<controller:[\w-]+>/<action:[\w-]+>' => '<controller>/<action>',
    '<controller:[\w-]+>/<action:[\w-]+>/<id:\d+>' => '<controller>/<action>',
];
