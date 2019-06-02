<?php

return [
    'from' => 'required|min:4|phone:mobile,PH',
    'to' => 'required|min:4|phone:mobile,PH',
    'message' => 'string|max:800'
];
