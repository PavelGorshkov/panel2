<?php

namespace app\modules\core\helpers;

use yii\base\BaseObject;

/**
 * Class LoggerFileParser
 * @package app\modules\core\helpers
 */
class LoggerFileParser extends BaseObject
{

    /**
     * @param $log
     * @return array|null
     */
    public function getData($log)
    {
        $path = $log['filePath'];
        $content = file_get_contents($path);
        $content = $this->processingSourceFile($content);
        return $content;
    }

    /**
     * @param string $content
     * @return array|null
     */
    private function processingSourceFile($content)
    {
        $array = [];
        $index = 1;
        $pattern = "/(\d{1,4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2} \[\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}\])/";
        $content = preg_split($pattern, $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        if (!empty($content)) {
            foreach ($content as $key => $item) {
                if ($key == 0 || $key % 2 == 0) {
                    $dateIp = $this->strToArrayDate($item);
                    !empty($dateIp['date']) ? $array[$index]['date'] = $dateIp['date'] : $array[$index]['date'] = '';
                    !empty($dateIp['ip']) ? $array[$index]['ip'] = $dateIp['ip'] : $array[$index]['ip'] = '';
                } else {
                    $messageLevel = $this->strToArrayMesLevel($item);
                    !empty($messageLevel['level']) ? $array[$index]['level'] = $messageLevel['level'] : $array[$index]['level'] = '';
                    !empty($messageLevel['information']) ? $array[$index]['information'] = $messageLevel['information'] : $array[$index]['information'] = '';
                    $index += 1;
                }
            }
            return $array;
        } else {
            return null;
        }
    }


    /**
     * @param string $string
     * @return array
     */
    private function strToArrayDate($string)
    {
        $pattern = "/(\[\d{1,3}.\d{1,3}.\d{1,3}.\d{1,3}\])/";
        $arr = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $date['date'] = $arr['0'];
        $date['ip'] = str_replace(['[', ']'], '', $arr['1']);
        return $date;
    }

    /**
     * @param string $string
     * @return mixed
     */
    private function strToArrayMesLevel($string)
    {
        $pattern = "/(\[-]|\[\w+])/";
        $arr = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $info['level'] = str_replace(['[', ']'], '', $arr['2']);
        $info['information'] = $string;
        return $info;
    }

}