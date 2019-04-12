<?php
/**
 * PHP Version 7.1
 * @category  PHP
 * @package   Com.womenshigaoruanjiande.hyj
 * @author    hyj <hanyoujun@gmail.com>
 * @copyright 2018 womenshigaoruanjiande.com
 * @license   http://www.womenshigaoruanjiande.com/license/ No License
 * @link      http://www.womenshigaoruanjiande.com
 *
 */

namespace app\common;

use DateTime;

/**
 * Class Date
 * @package app\common
 */
class Date
{
    const FORMAT_DATE = 'Y-m-d';
    const FORMAT_DATE_TIME = 'Y-m-d H:i:s';
    const FORMAT_DATE_TIME2 = 'Y-m-d H:i';
    const FORMAT_SHORT = 'Ymd';
    const FORMAT_TIME = 'H:i';
    /** 30分钟毫秒数 */
    const MINUTE_30 = '1800000';
    /** 1天的毫秒数 */
    const MINUTE_DAY = "86400000";
    
    /**
     * 时间戳转日期
     * 如果time为null，返回空
     * @param        $time
     * @param string $defaultValue
     * @param string $format
     * @return string
     */
    public static function format2($time, $defaultValue = '', $format = self::FORMAT_DATE_TIME)
    {
        if (null === $time || empty($time)) {
            return $defaultValue;
        }

        return self::format($time, $format);
    }

    /**
     * 时间戳转日期
     * @param        $time
     * @param string $format
     * @return string
     */
    public static function format($time, $format = self::FORMAT_DATE)
    {
        if (null === $time) {
            $time = self::time();
        }
        return date($format, $time);
    }

    /**
     * 返回1970到现在的秒数
     * @return int
     */
    public static function time()
    {
        return time();
    }

    /**
     * 获取年月日
     * @return false|string 例:20190318
     */
    public static function shortDate()
    {
        return date(self::FORMAT_SHORT);
    }

    /**
     * 时间戳与当前时间比较
     * '>' 时间戳大于当前，比当前时间晚
     * '<' 时间戳小于当前，比当前时间早
     * @param $time
     * @return string
     */
    public static function diffNow($time)
    {
        return self::time() < $time ? '>' : '<';
    }
    
    /**
     * 格式化日期 明天、后天 小时分
     * @param        $time   毫秒数
     * @param bool   $showToday 显示天
     * @param string $format
     * @return string
     * 例如: 今天 23:04
     */
    public static function formatDayTime($time, $showToday = false, $format = self::FORMAT_TIME)
    {
        $day = self::formatDay($time, $showToday);
        if (empty($day)) {
            return '';
        }
        $hi = self::format($time, $format);

        return $day . $hi;
    }

    /**
     * 格式化日期 明天、后天
     * @param string $time
     * @param bool   $showToday 是否返回今天
     * @return string
     */
    public static function formatDay($time, $showToday = false)
    {
        if (empty($time)) {
            return '';
        }
        $today = self::format(null);
        $timeDay = self::format($time);
        $count = self::diff($today, $timeDay);
        if ($count < 0) {
            return abs($count) . '天前 ';
        }
        if ($showToday && $count === 0) {
            return '今天 ';
        }
        if ($count === 1) {
            return '明天 ';
        }
        if ($count === 2) {
            return '后天 ';
        }
        if ($count > 2) {
            return ($count - 1) . '天后 ';
        }

        return '';
    }

    /**
     * 计算两个时间的差值
     * @param string $date1 开始时间
     * @param string $date2 结束时间
     * @param string $key
     * @return int
     * 例:
     * Date::diff("2019-01-02", "2019-01-10");
     */
    public static function diff($date1, $date2, $key = 'days')
    {
        try {
            $d1 = new DateTime($date1);
            $d2 = new DateTime($date2);
            $dateInterval = $d1->diff($d2);
            if ($dateInterval->invert === 1) {
                return -(int)$dateInterval->$key;
            }
            return (int)$dateInterval->$key;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 计算2个时间的差值
     * @param $t1
     * @param $t2
     * @return array
     * diffTimeDetail("1552921718", '1554921718')
     */
    public static function diffTimeDetail($t1, $t2)
    {
        try {
            $d1 = new DateTime(self::format($t1, self::FORMAT_DATE_TIME));
            $d2 = new DateTime(self::format($t2, self::FORMAT_DATE_TIME));
            $dateInterval = $d1->diff($d2);
            if ($dateInterval->invert === 1) {
                return [-$dateInterval->days, -$dateInterval->h, -$dateInterval->i];
            }
            return [$dateInterval->days, $dateInterval->h, $dateInterval->i];
        } catch (\Exception $e) {
            return [0, 0, 0];
        }
    }

    /**
     * 计算时间差值
     * @param int    $time1
     * @param int    $time2
     * @param string $key
     * @return int
     * 例: diffTime("1552921718", '1554921718') 返回小时(天数)
     */
    public static function diffTime($time1, $time2, $key = 'days')
    {
        $date1 = self::format($time1, self::FORMAT_DATE_TIME);
        $date2 = self::format($time2, self::FORMAT_DATE_TIME);

        return self::diff($date1, $date2, $key);
    }

    /**
     * 判断字符串是否日期
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function isDate($date, $format = self::FORMAT_DATE)
    {
        $unixTime = strtotime($date);
        if ($unixTime === false) {
            return false;
        }
        if (date($format, $unixTime) === $date) {
            return true;
        }

        return false;
    }

    /**
     * 获取早晚2个时间戳
     * @param $date
     * @return array
     */
    public static function get2Time($date)
    {
        $firstTime = self::strtotime($date . ' 00:00:00');
        $lastTime = self::strtotime($date . ' 23:59:59');

        return [$firstTime, $lastTime];
    }

    /**
     * 字符串转时间戳
     * @param null $date
     * @param null $defaultValue
     * @return false|int|null
     */
    public static function strtotime($date = null, $defaultValue = null)
    {
        if (null === $date || $date === '') {
            return $defaultValue;
        }

        return strtotime($date);
    }
    
    /**
     * 获取时间是否在有效范围内(一天内)
     * @param $targetTime 目标时间比较
     */
    public static function effectiveTime($targetTime){
        return abs(Date::time() - $targetTime) <= self::MINUTE_DAY;
    }
    
    /**
     * 判断时间, 是否在指定时间范围内
     * 比如验证码是否还在有效期范围内
     * 思路: 当前时间 - 验证时间 <= 范围时间(60秒等)
     */
    public static function effectiveTime2($targetTime, $vTime){
        return (Date::time() - $targetTime) <= $vTime;
    }
} 