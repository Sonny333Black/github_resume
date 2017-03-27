<?php
namespace ResumeBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('prozent', array($this, 'prozentFilter')),
        );
    }

    public function prozentFilter($number,$all)
    {
    $number = ($number/$all)*100;
    $number = round($number, 2);

    return $number;
    }
}