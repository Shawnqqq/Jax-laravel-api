<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cache;
use App\Models\District;

class DestrictController extends Controller
{
    public function index(Request $request)
    {
        $districts = Cache::get('all_distincts');
        if (!$districts) {
            $districts = Cache::rememberForever('all_distincts', function () {
                return $this->allDistincts();
            });
        }
        return $this->success($districts);
    }
    /**
    * 因为目前只有此处会返回所有地理数据，暂时放在此处，如果多处需要，可以考虑抽离重用
    * @return array
    */
    protected function allDistincts()
    {
        $districts = District::whereNull('parent_code')
            ->with('children.children')
            ->orderBy('code')
            ->get();

        $provinceArr = [];
        $i = 0;
        foreach ($districts as $province) {
            $provinceArr[$i] = [
                'label' => $province->name,
                'value' => $province->code
            ];
            $j = 0;
            foreach ($province->children as $city) {
                $provinceArr[$i]['children'][$j] = [
                    'label' => $city->name,
                    'value' => $city->code
                ];
                $k = 0;
                foreach ($city->children as $district) {
                    $provinceArr[$i]['children'][$j]['children'][$k] = [
                        'label' => $district->name,
                        'value' => $district->code
                    ];
                    $k++;
                }
                $j++;
            }
            $i++;
        }
        return $provinceArr;
    }
}
