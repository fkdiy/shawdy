package handler

import (
	"context"
	"errors"
	"net/http"

	"github.com/fkdiy/shawdy/apps/redirector/internal/metrics"
	"github.com/redis/go-redis/v9"
)

type Resolver interface {
	Resolve(
		ctx context.Context,
		shortCode string,
	) (string, error)
}

type RedirectHandler struct {
	resolver Resolver
	metrics  *metrics.RedirectMetrics
}

func NewRedirectHandler(
	r Resolver,
	m *metrics.RedirectMetrics,
) *RedirectHandler {
	return &RedirectHandler{
		resolver: r,
		metrics:  m,
	}
}

func (h *RedirectHandler) ServeHTTP(
	w http.ResponseWriter,
	r *http.Request,
) {
	h.metrics.Requests.Inc()

	shortCode := r.PathValue("shortCode")

	url, err := h.resolver.Resolve(
		r.Context(),
		shortCode,
	)

	// Send 404 Not Found if shortCode could not be found in Redis
	if errors.Is(err, redis.Nil) {
		h.metrics.Misses.Inc()

		http.NotFound(w, r)
		return
	}

	// Send 503 Service Unavailable if Redis is unavailable
	if err != nil {
		h.metrics.RedisErrors.Inc()

		http.Error(
			w,
			"Service Unavailable",
			http.StatusServiceUnavailable,
		)
		return
	}

	// Send 302 Found if shortCode could be resolved
	http.Redirect(
		w,
		r,
		url,
		http.StatusFound,
	)
}
