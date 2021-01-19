<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class GalleryDetail extends Model
{
    public $timestamps = false;
    protected $fillable = ['origin_filename', 'order', 'file_path'];
    protected $appends = ['thumbnail'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(['App\GalleryDetail', 'deleteFileAfterDeleted']);
    }

    public function getFilePathAttribute()
    {
        return env('APP_URL').$this->attributes['file_path'];
    }

    public function getThumbnailAttribute()
    {
        return  env('APP_URL').'uploads/gallery/thumbnail/'.$this->attributes['filename'];
    }

    protected static function deleteFileAfterDeleted($model)
    {
        $file_path = $model->getOriginal('file_path');
        $order_number = $model->order;
        if (file_exists(base_path('public/'.$file_path))) {
            unlink(base_path('public/'.$file_path));
        }

        if (file_exists(base_path('public/uploads/gallery/thumbnail/'.$model->filename))) {
            unlink(base_path('public/uploads/gallery/thumbnail/'.$model->filename));
        }

        GalleryDetail::where('gallery_id', $model->gallery_id)
                     ->where('order', '>', $model->order)
                     ->update([
                        'order' => DB::raw('`order` - 1 ')
                     ]);
    }
}
