package main

import (
	"net/http"
	"net/http/httptest"
	"testing"
)

func TestRouting(t *testing.T) {
	tests := []struct {
		name       string
		method     string
		path       string
		wantStatus int
	}{
		{
			name:       "health check",
			method:     http.MethodGet,
			path:       "/healthz",
			wantStatus: http.StatusOK,
		},
		{
			name:       "root path",
			method:     http.MethodGet,
			path:       "/",
			wantStatus: http.StatusNotFound,
		},
		{
			name:       "nested path",
			method:     http.MethodGet,
			path:       "/foo/bar",
			wantStatus: http.StatusNotFound,
		},
		{
			name:       "trailing slash",
			method:     http.MethodGet,
			path:       "/abc123/",
			wantStatus: http.StatusNotFound,
		},
		{
			name:       "wrong method",
			method:     http.MethodPost,
			path:       "/abc123",
			wantStatus: http.StatusMethodNotAllowed,
		},
	}

	for _, tt := range tests {
		t.Run(tt.name, func(t *testing.T) {
			req := httptest.NewRequest(
				tt.method,
				tt.path,
				nil,
			)

			response := httptest.NewRecorder()

			testHandler := http.HandlerFunc(
				func(w http.ResponseWriter, r *http.Request) {
					w.WriteHeader(http.StatusFound)
				},
			)

			mux := newMux(testHandler)

			mux.ServeHTTP(response, req)

			if response.Code != tt.wantStatus {
				t.Fatalf(
					"expected status %d, got %d",
					tt.wantStatus,
					response.Code,
				)
			}
		})
	}
}
