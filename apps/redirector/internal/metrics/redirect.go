package metrics

import "github.com/prometheus/client_golang/prometheus"

type RedirectMetrics struct {
	Requests    prometheus.Counter
	Misses      prometheus.Counter
	RedisErrors prometheus.Counter
}

func NewRedirectMetrics(
	registerer prometheus.Registerer,
) *RedirectMetrics {
	metrics := &RedirectMetrics{
		Requests: prometheus.NewCounter(
			prometheus.CounterOpts{
				Name: "shawdy_redirect_requests_total",
				Help: "Total number of redirect resolution attempts.",
			},
		),
		Misses: prometheus.NewCounter(
			prometheus.CounterOpts{
				Name: "shawdy_redirect_misses_total",
				Help: "Total number of unresolved Short Codes.",
			},
		),
		RedisErrors: prometheus.NewCounter(
			prometheus.CounterOpts{
				Name: "shawdy_redirect_redis_errors_total",
				Help: "Total number of Redis errors during redirect resolution.",
			},
		),
	}

	registerer.MustRegister(
		metrics.Requests,
		metrics.Misses,
		metrics.RedisErrors,
	)

	return metrics
}
