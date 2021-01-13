<?php

namespace App\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Widgets\BaseDimmer;

class StockRecordDimmer extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = \App\Stock::count();
        $string = 'Stock Records';

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-pie-chart',
            'title'  => "{$count} {$string}",
            'text'   => __('voyager::dimmer.user_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => 'Stock Records',
                'link' => route('update-stock.index'),
            ],
            'image' => voyager_asset('images/widget-backgrounds/01.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
//        return Auth::user()->can('browse', Voyager::model('User'));

        return true;
    }
}
