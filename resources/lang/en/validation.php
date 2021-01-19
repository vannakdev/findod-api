<?php

return [
//================Form validation===============================
    'unique' => "The :attributes has already been taken.",
    'min' => 'The :attribute name may not be greater than 50 characters.',
    'max' => 'The :attribute must be at least 8 characters.',
    'numeric' => ':attribute can only contain numbers.',
    'mimes' => 'The :attribute must be a file of type :mimes.',
    'image' => 'The :attribute must be an image.',
    'numeric' => ':attribute is not a valid number.',
    'size' => ':attribute is too big or contains too many characters.',
    'regex' => 'The :attribute format is invalid.(en)',
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'same' => 'The :attribute and password must match.',
    'exists' => 'The selected :attribute is invalid.',
    'active_url' => "The :attribute is not a valid URL.",
    //=============7/26/2018==========
    'digits_between' => "The :attribute under validation must have a length between the given :min and :max.",
    'dimensions' => 'The :attribute  must be an image meeting the dimension constraints.',
    'attributes' => array(
        //==============User module========================
        'email' => 'email',
        'first_name' => "first name",
        'last_name' => 'last name',
        'country_code' => 'contry code',
        'company_name' => 'company name',
        'company_number' => 'company number',
        'company_address' => 'comapny address',
        'company_licence' => 'company licence',
        'retype' => 'retype password',
        'photoUrl' => 'Photo URL',
        'planUrl' => 'Plan URL',
        'active_url' => 'URL',
        //=============7/26/2018==========
        'pro_bed_rooms' => 'Property bed room',
        'pro_bath_rooms' => 'Property bash room',
        'pro_floor' => 'Property floor',
        'pro_residence' => 'Property residence',
        //========01/8/2018====================
        'pro search type' => 'Property search type',
        'pro_square_feet' => 'Property square feet',
        'pro_price' => 'Property price',
        'pro_residence' => 'Property residence',
        'pro_bed_rooms' => 'Property bed rooms',
        'pro_bath_rooms' => 'Property bath rooms',
        'pro_floor' => 'Property floor',
        'pro_lng' => 'Property longitude',
        'pro_lat' => 'Property latitude',
        'pro_address' => 'Property address',
        'pro_status' => 'Property status',
        'pro_age' => 'Property age',
        'pro_detail' => 'Property detail',
        
    )
];

