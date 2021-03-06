<?

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \News\Entity\Item as Item;
use \News\Form\NewsItemForm as NewsItemForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;
use Zend\View\Helper\PaginationControl as ZendPaginationControl;

class NewsController extends AbstractActionController {
    protected $objectManager;

    public function __construct($objectManager = null) {
        if($objectManager) {
            $this->objectManager = $objectManager;
            $this->queryBuilder = $objectManager->createQueryBuilder();
        }
    }

    public function indexAction() {
        return new ViewModel(array(
            'news' => $this->getItems(),
            'category' => null,
        ));
    }

    public function categoryAction() {
        $categoryUrl = (string)$this->params('category');

        if($categoryUrl) { // add category to the 'where'
            $category = $this->objectManager
                ->getRepository('\News\Entity\Category')
                ->findOneByUrl($categoryUrl);
            if(!$category) {
                return $this->redirect()->toRoute('news');
            }
        }

        $viewModel =  new ViewModel(array(
            'news' => $this->getItems($category->getId()),
            'category' => $category,
        ));
        $viewModel->setTemplate('news/news/index.phtml');
        return $viewModel;
    }

    private function getItems($categoryId = null) {
        $page = (int)$this->params('page');

        $news = $this->objectManager
            ->getRepository('\News\Entity\Item');

        $query = $news->createQueryBuilder('i')
            ->orderBy('i.created', 'DESC')
            ->where('i.visible = 1');

        if($categoryId) {
            $query->andWhere($query->expr()->eq('i.category', $categoryId));
        }

        $paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(1); // @TODO Set up normal items per page
        $normalizedPage = $paginator->normalizePageNumber($page);

        if($page > $paginator->count()) {
            $message = sprintf('Страницы %s не существует! Будет отображена страница %s.', $page, $normalizedPage);
            $this->flashMessenger()->addWarningMessage($message);
        }

        return $paginator;
    }

    public function listAction() {
        $page = (int)$this->params('page');

        $news = $this->objectManager
            ->getRepository('\News\Entity\Item');

        $query = $news->createQueryBuilder('i')
            ->orderBy('i.created', 'DESC');

        $paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(10);

        if($page > $paginator->count()) {
            $message = sprintf('Страницы %s не существует!', $page);
            $this->flashMessenger()->addErrorMessage($message);
            return $this->redirect()->toRoute('zfcadmin/news');
        }

        return new ViewModel(array(
            'news' => $paginator,
        ));
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
                $message = 'Новость успешно добавлена!';
                $this->flashMessenger()->addMessage($message);
                // Redirect to list of items
                return $this->redirect()->toRoute('zfcadmin/news');
            } else {
                $message = 'Форма заполнена неправильно!';
                $this->flashMessenger()->addErrorMessage($message);
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

        $this->flashMessenger()->addMessage(sprintf('Новость "%s" успешно удалена', $item->getTitle()));

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

                $message = 'Новость успешно изменена!';
                $this->flashMessenger()->addMessage($message);
                // Redirect to list of items
                return $this->redirect()->toRoute('zfcadmin/news');
            } else {
                $message = 'Форма заполнена неправильно!';
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
            $this->flashMessenger()->addErrorMessage(sprintf('Новость с идентификатором "%s" не найдена', $id));
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