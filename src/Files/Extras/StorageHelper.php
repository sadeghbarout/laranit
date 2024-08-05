<?php

namespace Colbeh\Laranit\Files\Extras;

use App\Exceptions\ErrorMessageException;
use App\Extras\StatusCodes;
use App\Extras\Tools;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use function App\Extras\config;

/*
 * if using S3 then define tmp disk in config/filesystem to upload files here temporary
 * if using private define a private disk in config/filesystem to use as private storage / also define it in env file as FILESYSTEM_DRIVER_PRIVATE
 */


class StorageHelper {

    private string|null $disk = null;
    private int|null $width = null;
    private int|null $height = null;
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
    private string $s3TmpDisk = 'tmp';

    public function __construct(string $disk = null) {
        $this->disk = $disk ?? config('filesystems.default');
    }

    public function width(int $width): static {
        $this->width = $width;
        return $this;
    }

    public function height(int $height): static {
        $this->height = $height;
        return $this;
    }

    public function extensions(array $allowedExtensions): static {
        $this->allowedExtensions = $allowedExtensions;
        return $this;
    }

    private function uploadAndCompressImage(string $path, $file): bool|string {
        $driver = config('filesystems.disks.' . $this->disk . '.driver');

        if ($driver === 's3') {
            $tmpDriverPath = config("filesystems.disks." . $this->s3TmpDisk . ".root") . '/';
            $imageName = Tools::uploadAndCompressImage($file, $tmpDriverPath, $this->width, $this->height);
            $filename = Storage::disk($this->disk)->putFileAs($path, new File($tmpDriverPath . $imageName), $imageName);
            Storage::disk($this->s3TmpDisk)->delete($imageName);
        } else {
            $driverPath = config('filesystems.disks.' . $this->disk . '.root') . "/$path/";
            $filename = Tools::uploadAndCompressImage($file, $driverPath, $this->width, $this->height);
            $filename = "$path/$filename";
        }

        return $filename;
    }

    public function put(string $path, $file, $filename=null): string {
        $ext = $file->getClientOriginalExtension();

        if (in_array(strtolower($ext), $this->allowedExtensions)) {
            if ($this->width === null && $this->height === null) { // only save file
                $filename = $filename ??  md5($file->getClientOriginalName() . time()) . '.' . $ext;
                $filename = Storage::disk($this->disk)->putFileAs($path , $file, $filename);
            } else { //save and compress image
                $filename = $this->uploadAndCompressImage($path, $file);
            }

            return basename($filename);
        } else {
            $extensionsStr = implode(', ', $this->allowedExtensions);
            throw new ErrorMessageException("'این پسوند مجاز نمی باشد. پسوند های مجاز:'.$extensionsStr پسوند ارسالی  : $ext", StatusCodes::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function delete(string$path, $file): bool {
        if(Storage::disk($this->disk)->exists($path . basename($file))){
            return Storage::disk($this->disk)->delete($path . basename($file));
        }

        return false;
    }

    public function url(string $path, $file): string {
        return Storage::disk($this->disk)->url($path . basename($file));
    }

    public function get(string $path, $file): ?string {
        return Storage::disk($this->disk)->get($path . basename($file));
    }

    public function allFiles($path) {
        return Storage::disk($this->disk)->allFiles($path);
    }

    public function lastModified($path, $filename) {
        return Storage::disk($this->disk)->lastModified($path . $filename);
    }
}
