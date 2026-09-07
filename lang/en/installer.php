<?php


return [
    'title' => 'Food Scan Installer',
    'next'  => 'Next Step',
    'welcome' => [
        'templateTitle' => 'Welcome',
        'title'         => 'Food Scan Installer',
        'message'       => 'Easy Installation and Setup Wizard.',
        'next'          => 'Check Requirements',
    ],
    'requirement' => [
        'templateTitle' => 'Step 1 | Server Requirements',
        'title'         => 'Server Requirements',
        'next'          => 'Check Permissions',
        'version'       => 'version',
        'required'      => 'required'
    ],
    'permission' => [
        'templateTitle'       => 'Step 2 | Permissions',
        'title'               => 'Permissions',
        'next'                => 'Site Setup',
        'permission_checking' => 'Permission Checking'
    ],
    'site'     => [
        'templateTitle' => 'Step 3 | Site Setup',
        'title'         => 'Site Setup',
        'next'          => 'Database Setup',
        'label'         => [
            'app_name' => 'App Name',
            'app_url'  => 'App Url',
        ]
    ],
    'database' => [
        'templateTitle' => 'Step 4 | Database Setup',
        'title'         => 'Database Setup',
        'next'          => 'Final Setup',
        'fail_message'  => 'Could not connect to the database.',
        'label'         => [
            'database_connection' => 'Database Connection',
            'database_host'       => 'Database Host',
            'database_port'       => 'Database Port',
            'database_name'       => 'Database Name',
            'database_username'   => 'Database Username',
            'database_password'   => 'Database Password',
        ]
    ],
    'final'    => [
        'templateTitle'   => 'Step 5 | Final Setup',
        'title'           => 'Final Setup',
        'success_message' => 'Application has been successfully installed.',
        'login_info'      => 'Login Information',
        'email'           => 'Email',
        'password'        => 'Password',
        'email_info'      => 'admin@example.com',
        'password_info'   => '123456',
        'next'            => 'Finish',
    ],
    'installed' => [
        'success_log_message' => 'Food Scan installer successfully INSTALLED on ',
        'update_log_message'  => 'Food Scan Installer successfully UPDATED on ',
    ],
];
