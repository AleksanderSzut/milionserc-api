<?php


namespace App\Traits;


use App\Models\Confession;

trait ProtectedFile
{
    public function getLink() {
        if($this->Confession->is_public === Confession::PUBLIC_YES)
            return env("APP_API_URL").'/'.$this->urlPath.'/'.$this->id;
        else
            return env("APP_API_URL").'/'.$this->urlPath.'/'.$this->id.'/'.$this->confession->access_code;
    }

    public function getPath() {
        return storage_path("app\\" . str_replace('/', '\\', $this->path));
    }

}
