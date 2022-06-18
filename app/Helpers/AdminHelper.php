<?php

if (!function_exists('createTranslation')) {
	/**
	 * Creates the base translation
	 *
	 * @param array $resources
	 * @param array $translation
	 *
	 * @return array
	 */
	function createTranslation(array $resources, array $translation)
	{
		foreach ($resources as $resource => $values) {
			if (isset($values['fields'])) {
				$translation['fields'][$resource] = $values['fields'];
				unset($values['fields']);
			}
			$translation['menu'][$resource] = $values;
			$translation[$resource] = $values;
		}
		return $translation;
	}
}

if (!function_exists('sumTheTime')) {
    /**
     * Sum Time with Format hh:mm:ss
     *
     * @param array $times
     *
     * @return string
     */
    function sumTheTime($times, $format = 'digital')
    {
        $seconds = 0;
        foreach ($times as $time)
        {
            list($hour,$minute,$second) = explode(':', $time);
            $seconds += $hour*3600;
            $seconds += $minute*60;
            $seconds += $second;
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;

        $resultTextual = '';
        if ($format == 'textual') {
            if ($hours) {
                $resultTextual .= $hours . ' saat ';
            }
            if ($minutes) {
                $resultTextual .= $minutes . ' dakika ';
            }
            if ($seconds) {
                $resultTextual .= $seconds . ' saniye';
            }

            return $resultTextual;
//            return sprintf('%02d saat %02d dakika %02d saniye', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}

if (!function_exists('beforeAndAfterKeys')) {
    /**
     * Get keys before and after specific value in array
     *
     * @param $array
     * @param $key
     *
     * @return array
     */
    function beforeAndAfterKeys($array, $key)
    {
        $currentKey = array_search($key, $array);

        $before = (isset($array[$currentKey - 1])) ? $array[$currentKey - 1] : null;
        $after = (isset($array[$currentKey + 1])) ? $array[$currentKey + 1] : null;

        return [
            'before' => $before,
            'after' => $after,
        ];
    }
}

if (!function_exists('collectTime')) {
    /**
     * Time to minute
     *
     * @param string $time : 00:00:00
     *
     * @return integer
     */
    function collectTime($time1, $time2)
    {
        $time2 = strtotime($time2);
        list($hour, $minute, $second) = explode(':', $time1);

        return date("H:i:s", $time2 + ($hour * 3600)+ ($minute * 60)+ $second);
    }
}

if (!function_exists('timeToMinute')) {
    /**
     * Time to minute
     *
     * @param string $time : 00:00:00
     *
     * @return integer
     */
    function timeToMinute($time)
    {
        $timeSplit = explode(':', $time);

        $min = ($timeSplit[0] * 60) + ($timeSplit[1]) + ($timeSplit[2] > 30 ? 1 : 0);

        return $min;
    }
}

if (!function_exists('createHashId')) {
    /**
     * Create hash id
     *
     * @param $id
     *
     * @return string
     */
    function createHashId($id)
    {
        return Vinkla\Hashids\Facades\Hashids::encode($id);
    }
}

if (!function_exists('createModalId')) {
    /**
     * Create modal id
     *
     * @return string
     */
    function createModalId()
    {
        $currentUrl = Illuminate\Support\Facades\URL::full();

        return md5($currentUrl);
    }
}

if (!function_exists('pdfPageNumber')) {
    /**
     * @param $pdf
     */
    function pdfPageNumber($pdf)
    {
        if ( isset($pdf) ) {
            $size = 9;
            $y = $pdf->get_height() - 17;
            $x = $pdf->get_width() - 70;
            $pdf->page_text($x, $y, 'Sayfa:{PAGE_NUM}/{PAGE_COUNT}', '', $size);
        }
    }
}
