<?php


namespace MissionNext\Models\Subscription;


class Partnership
{
    const LIMITED = 'limited',
          BASIC = 'basic',
          PLUS  ='plus';

    const PERIOD_YEAR = 'year',
          PERIOD_MONTH = 'month';

    public static function levels()
    {

        return [static::LIMITED, static::BASIC, static::PLUS];
    }

} 