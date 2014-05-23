<?

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \News\Entity\Item as Item;
use \News\Form\NewsItemForm as NewsItemForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;


class NewsController extends AbstractActionController {
    protected $objectManager;

    public function __construct($objectManager = null) {
        if($objectManager) {
            $this->objectManager = $objectManager;
            $this->queryBuilder = $objectManager->createQueryBuilder();
        }
    }

    public function indexAction() {
        $page = (int)$this->params('page');
        return new ViewModel(array(
            'news' => $this->getItems($page),
            'categoryName' => null,
        ));
    }

    public function categoryAction() {
        $page = (int)$this->params('page');
        $categoryUrl = (string)$this->params('category');

        if($categoryUrl) { // add category to the 'where'
            $category = $this->objectManager
                ->getRepository('\News\Entity\Category')
                ->findOneByUrl($categoryUrl);
            if(!$category) {
                return $this->redirect()->toRoute('news');
            }
        }

        return new ViewModel(array(
            'news' => $this->getItems($page, array('category' => $category->getId())),
            'categoryName' => $category->getName(),
        ));
    }

    private function getItems($page, $options = array()) {
        $news = $this->objectManager
            ->getRepository('\News\Entity\Item');

        $query = $news->createQueryBuilder('i')
            ->orderBy('i.created', 'DESC');

        $paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(5);
        $array = array();
        $array['getTotalItemCount method'] = $paginator->getTotalItemCount();
        $array['count method'] = $paginator->count();

        echo "<pre>";var_dump($array); echo "</pre>";
        //die();

        $items = array();
        foreach ($paginator as $item) {
            $buffer = $item->getArrayCopy();
            $buffer['category'] = $item->getCategory()->getName();
            $buffer['categoryUrl'] = $item->getCategory()->getUrl();
            $buffer['user'] = $item->getUser()->getDisplayName();
            if($item->getVisible()) {
                $items[] = $buffer;
            }
        }
        return $items;
    }

    public function listAction() {
        $news = $this->objectManager
            ->getRepository('\News\Entity\Item')
            ->findBy(array(), array('created' => 'DESC'));

        $items = array();
        foreach ($news as $item) {
            $buffer = $item->getArrayCopy();
            $buffer['category'] = $item->getCategory()->getName();
            $buffer['user'] = $item->getUser()->getDisplayName();
            $items[] = $buffer;
        }

        $view = new ViewModel(array(
            'news' => $items,
        ));

        return $view;
    }

    public function addAction() {
        $form = new NewsItemForm();
        
        $form->get('visible')->setCheckedValue(0);
        $form->get('visible')->setUncheckedValue(1);
        
        $form->get('submit')->setValue('Добавить');

        $categories = $this->objectManager
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

            if ($form->isValid()) {
                $item = new Item();

                $item->exchangeArray($form->getData());

                $item->setCreated(time());
                
                $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
                $user = $this->objectManager
                    ->getRepository('\SamUser\Entity\User')
                    ->find($userId);
                $item->setUser($user);
                
                $categoryId = $request->getPost('category');
                $category = $this->objectManager
                    ->getRepository('\News\Entity\Category')
                    ->find($categoryId);
                $item->setCategory($category);
                
                $this->objectManager->persist($item);
                $this->objectManager->flush();
                $message = 'Новость добавлена';
                $this->flashMessenger()->addMessage($message);
                // Redirect to list of items
                return $this->redirect()->toRoute('zfcadmin/news');
            }
        }
        
        return array(
            'form' => $form
        );
    }

    public function deleteAction() {
        $id = (int)$this->params('id');
        if(!$id) {
            return $this->redirect()->toRoute('zfcadmin/news');
        }
        $item = $this->objectManager
            ->getRepository('\News\Entity\Item')
            ->find($id);
        
        if (!$item) {
            $this->flashMessenger()->addErrorMessage(sprintf('Новость с идентификатором "%s" не найдена', $id));
            return $this->redirect()->toRoute('zfcadmin/news');
        }
        
        $result = array(
            'result' => true,
            'title' => $item->getTitle(),
        );
        
        $this->objectManager->remove($item);
        $this->objectManager->flush();

        $view = new ViewModel($result);
        return $view;
    }

    public function editAction() {
        $form = new NewsItemForm();
        
        $form->get('visible')->setCheckedValue(0);
        $form->get('visible')->setUncheckedValue(1);
        
        $form->get('submit')->setValue('Изменить');

        $categories = $this->objectManager
            ->getRepository('\News\Entity\Category')
            ->findAll();
        $items = array();
        foreach ($categories as $item) {
            $items[$item->getId()] = $item->getName();
        }
        $form->get('category')->setValueOptions($items);
        
        //handling request
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                
                $itemId = $data['id'];
                
                try {
                    $item = $this->objectManager->find('\News\Entity\Item', $itemId);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('zfcadmin/news');
                }
                $created = $item->getCreated();
                $item->exchangeArray($form->getData());
                $item->setCreated($created);
                $categoryId = $data['category'];
                $category = $this->objectManager
                    ->getRepository('\News\Entity\Category')
                    ->find($categoryId);
                $item->setCategory($category);
                
                $this->objectManager->persist($item);
                $this->objectManager->flush();

                $message = 'Новость изменена';
                $this->flashMessenger()->addMessage($message);
                // Redirect to list of items
                return $this->redirect()->toRoute('zfcadmin/news');
            } else {
                $message = 'Ошибка при изменении новости';
                $this->flashMessenger()->addErrorMessage($message);
            }
        } else {
            $id = (int)$this->params('id');
            if(!$id) {
                return $this->redirect()->toRoute('zfcadmin/news');
            }

            $item = $this->objectManager
                ->getRepository('\News\Entity\Item')
                ->findOneBy(array('id' => $id));

            if (!$item) {
                $this->flashMessenger()->addErrorMessage(sprintf('Новость с идентификатором "%s" не найдена', $id));
                return $this->redirect()->toRoute('zfcadmin/news');
            }

            // Fill form data.
            $form->bind($item);
            return array('form' => $form);
        }
        
        return array(
            'form' => $form
        );
    }
    
    public function fullAction() {
        $itemId = (int)$this->params('id');
        if(!$itemId) {
            return $this->redirect()->toRoute('news');
        }

        $item = $this->objectManager
            ->getRepository('\News\Entity\Item')
            ->find($itemId);

        $itemArray = $item->getArrayCopy();
        $itemArray['category'] = $item->getCategory()->getName();
        $itemArray['user'] = $item->getUser()->getDisplayName();
        
        return new ViewModel(array(
            'item' => $itemArray,
        ));
    }
}