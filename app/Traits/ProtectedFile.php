<?php


namespace App\Traits;


trait ProtectedFile
{

    public function getPath() {
        return storage_path("app\\" . str_replace('/', '\\', $this->path));
    }

}
