<?php
namespace News\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use \News\Form\AddNewsItemInputFilter as AddNewsItemInputFilter;

class AddNewsItemForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('newsAdd');
        $this->setAttribute('method', 'post');
        $this->setInputFilter(new AddNewsItemInputFilter());
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
            'name' => 'user_id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'min' => 3,
                'max' => 25,
                'label' => 'Title',
            ),
        ));
        $this->add(array(
            'name' => 'category',
            'type' => 'select',
            'options' => array(
                'label' => 'Категория: ',
            ),
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'Textarea',
            'options' => array(
                'label' => 'Text',
            ),
        ));
        $this->add(array(
            'name' => 'visible',
            'type' => 'Checkbox',
            'options' => array(
                'label' => 'скрытая',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        ));
    }
}