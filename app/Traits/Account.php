<?php

namespace App\Traits;

trait Account {

    public function admin() {
        return auth()->guard('admins')->user();
    }

    public function user() {
        return auth()->guard('users')->user();
    }

}
