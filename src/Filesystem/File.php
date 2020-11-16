<?php
/**
 * An abstract class for uploaded files in laravel.
 * Created by PhpStorm.
 * User: Chan Nyein Thaw
 * Date: 9/21/2017
 * Time: 8:48 PM
 */

namespace Lumos\Lustom\Filesystem;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

abstract class File {
    protected $allowedMimeTypes = [];
    protected $path;
    private $file;
    private $storedName;

    public function __construct($file) {
        $this->storedName = '';
        if ($file instanceof UploadedFile) {
            $this->file = $file;
        }

        if (is_string($file)) {
            $this->storedName = $file;
        }
    }

    public function getPath() : string {
        return $this->path;
    }

    public function getStoredName() : string {
        return $this->storedName;
    }

    public function getClientOriginalName() : ?string {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientOriginalName();
        }

        return '';
    }

    public function getClientOriginalExtension() : string {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientOriginalExtension();
        }

        return '';
    }

    public function getClientSize() : ?int {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientSize();
        }

        return null;
    }

    public function store() : bool {
        if (!($this->file instanceof UploadedFile)) return false;

        if (!$this->checkMimeType($this->file->getMimeType())) return false;

        $result = false;
        try {
            $fileName = $this->file->store($this->path);
            if ($fileName) {
                $this->storedName = basename($fileName);
                $result = true;
            }
        } catch(\ErrorException $e) { }

        return $result;
    }

    public function delete() : bool {
        return Storage::delete($this->path . '/' . $this->storedName);
    }

    public function exists() : bool {
        return Storage::exists($this->path . '/' . $this->storedName);
    }

    public function getFileResponse() {
        return response()->file(storage_path() . '/app/' .$this->path . '/' . $this->storedName);
    }

    private function checkMimeType($mimeType) : bool {
        if (count($this->allowedMimeTypes) > 0)
            return in_array($mimeType, $this->allowedMimeTypes);
        else
            return true;
    }
}