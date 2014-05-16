<?php

namespace News\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class CategoryInputFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'name',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 30,
                    ),
                ),
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),

        ));
        $this->add(array(
            'name' => 'security',
            'required' => false,
        ));
    }
}