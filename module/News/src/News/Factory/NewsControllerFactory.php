<?

namespace News\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use News\Controller\NewsController;

class NewsControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        var_dump("bob");
        $objectManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return new NewsController($objectManager);
    }
}