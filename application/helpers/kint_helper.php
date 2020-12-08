<?php

require APPPATH . 'third_party/kint-php/kint/build/kint.php';
require APPPATH . 'third_party/kint-php/kint-js/init.php';

Kint::$enabled_mode = ENVIRONMENT === 'development';
