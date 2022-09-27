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
    /**
     * LabelController constructor.
     * @param EntityRepository $logEntryRepository
     * @param ProductLabelsService $productLabelsService
     */
    public function __construct(
        protected EntityRepository $logEntryRepository,
        protected ProductLabelsService $productLabelsService
    ) { }

    /**
     * @Route(
     *     "/api/wexo/product-labels/run-schedule",
     *     name="api.wexo.product-labels.run-schedule",
     *     methods={"POST"}
     * )
     */
    public function runSchedule(Request $request, Context $context): JSONResponse
    {
        try {
            $this->productLabelsService->updateLabels($context);
        } catch (\Exception $e) {
            $this->logEntryRepository->create(
                [
                    [
                        'message'   => 'Error processing SetProductLabelsHandler',
                        'context'   => [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'errorType' => get_class($e)
                        ],
                        'level'     => Logger::ERROR,
                        'channel'   => WexoProductLabels::LOG_CHANNEL
                    ]
                ],
                Context::createDefaultContext()
            );

            return new JsonResponse([
                'message' => 'ERROR: ' . $e->getMessage()
            ], 400);
        }

        return new JsonResponse('Success', 200);
    }
}
