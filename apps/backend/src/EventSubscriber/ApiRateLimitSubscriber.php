<?php

namespace App\EventSubscriber;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\RateLimiter\RateLimiterFactory;

final class ApiRateLimitSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire(service: 'limiter.short_url_creation')]
        private RateLimiterFactory $shortUrlCreationLimiter,

        #[Autowire(service: 'limiter.abuse_report_submission')]
        private RateLimiterFactory $abuseReportSubmissionLimiter,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        $factory = match ($route) {
            '_api_/short_urls{._format}_post' => $this->shortUrlCreationLimiter,

            '_api_/abuse_reports{._format}_post' => $this->abuseReportSubmissionLimiter,

            default => null,
        };

        if (null === $factory) {
            return;
        }

        $clientKey = $request->getClientIp() ?? 'unknown';

        $limit = $factory
            ->create($clientKey)
            ->consume();

        if (!$limit->isAccepted()) {
            throw new TooManyRequestsHttpException(max(1, $limit->getRetryAfter()->getTimestamp() - time()));
        }
    }
}
