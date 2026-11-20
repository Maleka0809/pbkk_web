<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductImageModel extends Model
{
    protected $table         = 'product_images';
    protected $primaryKey    = 'id_image';
    protected $returnType    = 'array';
    protected $allowedFields = ['id_product', 'image', 'is_primary', 'created_at'];
    protected $useTimestamps = false;
}
