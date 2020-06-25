<?php
return [
    'model_path' => '\App\Models',
    'user_model' => [
        'class' => \App\Models\User::class,
        'key' => 'id'
    ],
    'roles' => [
        'Admin'     => 'Admin',
        'Salesrep'  => 'Salesrep',
        'ProjectManager' => 'Project Manager',
    ],
];
