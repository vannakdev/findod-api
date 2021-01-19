<?php

return [
    //================Form validation===============================
    'unique' => 'The :attributes has already been taken. (kh)',
    'min' => 'The :attribute name may not be greater than 50 characters. (kh)',
    'max'=> 'The :attribute must be at least 8 characters. (kh)',
    'numeric' => ':attribute can only contain numbers. (kh)',
    'mimes' => 'The :attribute must be a file of type: mimes. (kh)',
    'image'=>'The :attribute must be an image. (kh)',

    'numeric' => ':attribute is not a valid number. (kh)',
    'size' => ':attribute is too big or contains too many characters. (kh)',
    'regex'=> 'The :attribute format is invalid.(kh) (kh)',
    'require'=>'The :attribute field is required. (kh)',
    'email'=>'The :attribute must be a valid email address. (kh)',
    'same'=>'The :attribute and password must match.(kh)', 'attributes' => [
        'email' => '',
        'first_name'=>'first name',
        'last_name'=> 'last name',
    ],
];
