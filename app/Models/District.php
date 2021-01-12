<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;

    protected $table = 'districts';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'city_code',
        'name',
        'level',
        'parent_code',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_code', 'code');
    }

    public function children()
    {
        return $this->hasMany(self::class,'parent_code','code');
    }

    public static function info($id)
    {
        if (empty($id)) {
            return null;
        }
        $_district = self::where('level', 'district')->with('parent.parent')->find($id);
        if (empty($_district)) {
            return null;
        }
        $district = [
            'code' => $_district->code,
            'name' => $_district->name,
            'parent_code' => $_district->parent_code,
        ];

        $parent1 = $_district->parent;
        if ($parent1->level == 'city') {
            $city = [
                'code' => $parent1->code,
                'name' => $parent1->name,
                'parent_code' => $parent1->parent_code,
            ];

            $parent2 = $parent1->parent;
            $province = [
                'code' => $parent2->code,
                'name' => $parent2->name,
                'parent_code' => $parent2->parent_code,
            ];
        } else {
            $city = null;
            $province = [
                'code' => $parent1->code,
                'name' => $parent1->name,
                'parent_code' => $parent1->parent_code,
            ];
        }

        return compact('province', 'city', 'district');
    }
}
