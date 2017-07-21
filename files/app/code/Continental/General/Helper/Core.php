<?php
namespace Continental\General\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;
/**
 * Created by PhpStorm.
 * User: MattB
 * Date: 06/07/2017
 * Time: 16:07
 */

class Core extends AbstractHelper
{

    /** @var Url $_customerUrl */
    protected $_customerUrl;

    /** @var Context $httpContext */
    protected $httpContext;


    /**
     * @param $str
     */
    public function strip_additional_para($str)
    {
        preg_match('/(^.*<\/p>).*?<p>/', $str, $matches);
        return !empty($matches[0]) ? $matches[0] : $str;
    }

    /**
     * @param $str - string to be abbreviated
     * @param int $desktop - cut off point for desktop
     * @param int $tablet - cut off point for tablets
     * @param int $mobile - cut off point for mobile phones
     * @param string $elipsis
     * @return string
     */

    public function responsiveTruncateText($str, $desktop = 150, $tablet = 100, $mobile = 50, $elipsis = '...')
    {

        if (strlen($str) > $desktop) {
            $x = explode(' ', substr($str, 0, $desktop));
            array_pop($x);
            $str = implode(' ', $x);
            $str .= $elipsis;
        }

        if (strlen($str) > $tablet) {
            $str = $this->insertSpan($str, $tablet, 'tablet', 'desktop');
        }

        if (strlen($str) > $mobile) {
            $str = $this->insertSpan($str, $mobile, 'mobile', 'tablet');
        }

        return $str;
    }

    /**
     * @param $str
     * @param $position  - position in string to insert span
     * @param $class     - name of class to assign to span
     * @param $parentClass - name of parent class to hide
     * @param string $elipsis
     * @return string
     */
    private function insertSpan($str, $position, $class, $parentClass, $elipsis = '...')
    {

        $x = explode(' ', $str);

        $measure = '';

        $flag = false;

        $i = 0;

        foreach ($x as $val) {
            $measure .= $val . ' ';
            if ((strlen($measure) > $position) && ($flag === false)) {
                $x[$i] = '<span class="' . $class . '">' . $elipsis . '</span><span class="' . $parentClass . '-hidden">';
                $flag = true;
            }
            $i++;
        }

        $str = implode(' ', $x) . '</span>';
        return $str;
    }
}
