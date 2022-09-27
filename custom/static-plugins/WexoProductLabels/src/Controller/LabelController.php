<?php declare(strict_types=1);

namespace Wexo\ProductLabels\Controller;

use Monolog\Logger;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\Framework\Context;
use Wexo\ProductLabels\WexoProductLabels;
use Wexo\ProductLabels\Service\ProductLabelsService;

/**
 * @Route(defaults={"_routeScope"={"api"}})
 */
class LabelController extends AbstractController
{

}
