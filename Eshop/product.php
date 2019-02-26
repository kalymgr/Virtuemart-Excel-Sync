<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Michalis Tsougranis
 * Date: 6/6/2012
 * Time: 1:18 μμ
 * To change this template use File | Settings | File Templates.
 */

class product
{
    private $kodikos;
    private $perigrafi;

    function __construct($kodikos, $perigrafi)
    {//constructor
        $this->kodikos=$kodikos;
        $this->perigrafi=$perigrafi;
    }

    function get_kodikos()
    {
        return $this->kodikos;
    }

    function set_kodikos($kod)
    {
        $this->kodikos=$kod;
    }

    function get_perigrafi()
    {
        return $this->perigrafi;
    }

    function set_perigrafi($perigr)
    {
        $this->perigrafi=$perigr;
    }
}