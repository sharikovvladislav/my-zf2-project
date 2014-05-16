<?php
namespace News\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use \News\Form\CategoryInputFilter as CategoryInputFilter;

class CategoryForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('categoryAdd');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new CategoryInputFilter());
        $this->add(array(
            'name' => 'security',
            'type' => 'Zend\Form\Element\Csrf',
        ));
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'created',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'min' => 3,
                'max' => 30,
                'label' => 'Название',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Добавить',
                'id' => 'submitbutton',
            ),
        ));
    }
}