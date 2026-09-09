package handler

import (
	"context"
	"errors"
	"net/http"

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
}

func NewRedirectHandler(r Resolver) *RedirectHandler {
	return &RedirectHandler{
		resolver: r,
	}
}

func (h *RedirectHandler) ServeHTTP(
	w http.ResponseWriter,
	r *http.Request,
) {
	shortCode := r.PathValue("shortCode")

	url, err := h.resolver.Resolve(
		r.Context(),
		shortCode,
	)

	// Send 404 Not Found if shortCode could not be found in Redis
	if errors.Is(err, redis.Nil) {
		http.NotFound(w, r)
		return
	}

	// Send 503 Service Unavailable if Redis is unavailable
	if err != nil {
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
