<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * PaginationService class
 */
class PaginationService
{
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var integer
     */
    private $limit = 10;

    /**
     * @var integer
     */
    private $currentPage = 1;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var Twig\Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $templatePath;

    /**
     * @param EntityManagerInterface $manager
     * @param Environment $twig
     * @param RequestStack $request
     */
    public function __construct(
        EntityManagerInterface $manager,
        Environment            $twig,
        RequestStack           $request,
        String                 $templatePath
    ) {
        $this->manager      = $manager;
        $this->twig         = $twig;
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->templatePath = $templatePath;
    }

    /**
     *
     * @return void
     */
    public function display() 
    {
        $this->twig->display($this->templatePath, [
            'page'  => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
        ]);
    }

    /**
     * 
     * @throws Exception
     * @return integer
     */
    public function getPages(): int 
    {
        if(empty($this->entityClass)) 
        {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer ! Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }

        // Connaitre le total des enregistrements de la table
        $total = count($this->manager->getRepository($this->entityClass)->findAll());

        return ceil($total / $this->limit);
    }

    
    /**
     * 
     * @throws Exception
     * @return array
     */
    public function getData(): array 
    {
        if(empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer !
                Utilisez la méthode setEntityClass() de votre objet PaginationService !");
        }

        // Calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        return $this->manager->getRepository($this->entityClass)->findBy([], [], $this->limit, $offset);
    }
    
    
    /**
    * @return string
    */
    public function getEntityClass(): string 
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     * @return self
     */
    public function setEntityClass(string $entityClass): self 
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLimit(): int 
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return self
     */
    public function setLimit(int $limit): self 
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPage(): int 
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return self
     */
    public function setPage(int $currentPage): self 
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute(): string 
    {
        return $this->route;
    }

    /**
     * @param string
     * @return self
     */
    public function setRoute(string $route): self 
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */ 
    public function getTemplatePath(): string 
    {
        return $this->templatePath;
    }

    /**
     * @param string $templatePath
     * @return self
     */
    public function setTemplatePath(string $templatePath): self 
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    

}