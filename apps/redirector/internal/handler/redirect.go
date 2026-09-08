package handler

import (
	"net/http"

	"github.com/fkdiy/shawdy/apps/redirector/internal/resolver"
)

type RedirectHandler struct {
	resolver *resolver.Resolver
}

func NewRedirectHandler(r *resolver.Resolver) *RedirectHandler {
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

	if err != nil {
		// later: handle not found & redis unavailable
		return
	}

	http.Redirect(
		w,
		r,
		url,
		http.StatusFound,
	)
}
