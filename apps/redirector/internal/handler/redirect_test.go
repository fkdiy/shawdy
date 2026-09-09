package handler

import (
	"context"
	"errors"
	"net/http"
	"net/http/httptest"
	"testing"

	"github.com/redis/go-redis/v9"
)

type fakeResolver struct {
	targetURL string
	err       error
}

func (f fakeResolver) Resolve(
	ctx context.Context,
	shortCode string,
) (string, error) {
	return f.targetURL, f.err
}

func TestRedirectHandlerRedirectsResolvedShortCode(t *testing.T) {
	r := fakeResolver{
		targetURL: "https://example.com",
	}

	h := NewRedirectHandler(r)

	req := httptest.NewRequest(
		http.MethodGet,
		"/abc123",
		nil,
	)

	req.SetPathValue("shortCode", "abc123")

	response := httptest.NewRecorder()

	h.ServeHTTP(response, req)

	if response.Code != http.StatusFound {
		t.Fatalf(
			"expected status %d, got %d",
			http.StatusFound,
			response.Code,
		)
	}

	if location := response.Header().Get("Location"); location != "https://example.com" {
		t.Fatalf(
			"expected Location %q, got %q",
			"https://example.com",
			location,
		)
	}
}

func TestRedirectHandlerReturnsNotFoundForUnknownShortCode(t *testing.T) {
	r := fakeResolver{
		err: redis.Nil,
	}

	h := NewRedirectHandler(r)

	req := httptest.NewRequest(
		http.MethodGet,
		"/abc123",
		nil,
	)

	req.SetPathValue("shortCode", "abc123")

	response := httptest.NewRecorder()

	h.ServeHTTP(response, req)

	if response.Code != http.StatusNotFound {
		t.Fatalf(
			"expected status %d, got %d",
			http.StatusNotFound,
			response.Code,
		)
	}
}

func TestRedirectHandlerReturnsServiceUnavailableOnResolverError(t *testing.T) {
	r := fakeResolver{
		err: errors.New("redis unavailable"),
	}

	h := NewRedirectHandler(r)

	req := httptest.NewRequest(
		http.MethodGet,
		"/abc123",
		nil,
	)

	req.SetPathValue("shortCode", "abc123")

	response := httptest.NewRecorder()

	h.ServeHTTP(response, req)

	if response.Code != http.StatusServiceUnavailable {
		t.Fatalf(
			"expected status %d, got %d",
			http.StatusServiceUnavailable,
			response.Code,
		)
	}
}
