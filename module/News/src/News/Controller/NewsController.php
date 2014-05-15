<?

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \News\Entity\Item as Item;
use \News\Form\AddNewsItemForm as AddNewsItemForm;

class NewsController extends AbstractActionController {
    public function indexAction() {
        
    }
    public function listAction() {

        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $news = $objectManager
            ->getRepository('\News\Entity\Item')
            ->findBy(array(), array('created' => 'DESC'));

        $items = array();
        foreach ($news as $item) {
            $items[] = $item->getArrayCopy();
        }

        $view = new ViewModel(array(
            'news' => $items,
        ));

        return $view;
    }

    public function addAction() {
        $form = new AddNewsItemForm();
        
        $form->get('visible')->setCheckedValue(0);
        $form->get('visible')->setUncheckedValue(1);
        
        $form->get('submit')->setValue('Add');
        
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $categories = $objectManager
            ->getRepository('\News\Entity\Category')
            ->findAll();
        $items = array();
        foreach ($categories as $item) {
            $items[$item->getId()] = $item->getName();
        }
        $form->get('category')->setValueOptions($items);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        
            $form->setData($request->getPost());
            //var_dump($form->isValid());
            //var_dump($form->getMessages()); //error messages
            //var_dump($form->getInputFilter()->getMessages());
            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                                    
                $item = new Item();

                $item->exchangeArray($form->getData());

                $item->setCreated(time());
                
                $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $user = $objectManager
                    ->getRepository('\SamUser\Entity\User')
                    ->find($userId);
                $item->setUserId($user);
                
                $categoryId = $request->getPost('category');
                $category = $objectManager
                    ->getRepository('\News\Entity\Category')
                    ->find($categoryId);
                $item->setCategoryId($category);
                
                $objectManager->persist($item);
                $objectManager->flush();
                $message = 'Новость добавлена';
                $this->flashMessenger()->addMessage($message);
                // Redirect to list of blogposts
                return $this->redirect()->toRoute('zfcadmin/news');
            }
        }
        
        return array(
            'form' => $form
        );
    }

    public function deleteAction() {
        $id = (int)$this->params('id');
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $item = $entityManager
            ->getRepository('\News\Entity\Item')
            ->find($id);
        
        if(isset($item)) {
            $result = array(
                'result' => true,
                'title' => $item->getTitle(),
            );
            
            $entityManager->remove($item);
            $entityManager->flush();
        } else {
            $result = array(
                'result' => false,
            );
        }
        $view = new ViewModel($result);
        return $view;
    }

    public function editAction(){
        return new ViewModel();
    }
}