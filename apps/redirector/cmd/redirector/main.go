package main

import (
	"log"
	"net"
	"net/http"

	"github.com/fkdiy/shawdy/apps/redirector/internal/config"
	"github.com/fkdiy/shawdy/apps/redirector/internal/handler"
	"github.com/fkdiy/shawdy/apps/redirector/internal/resolver"
	"github.com/redis/go-redis/v9"
)

func main() {
	cfg := config.Load()

	redisClient := redis.NewClient(&redis.Options{
		Addr: net.JoinHostPort(
			cfg.Redis.Host,
			cfg.Redis.Port,
		),
	})

	redirectResolver := resolver.New(redisClient)
	redirectHandler := handler.NewRedirectHandler(redirectResolver)

	mux := newMux(redirectHandler)

	log.Fatal(
		http.ListenAndServe(
			cfg.Redirector.Address,
			mux,
		),
	)
}

func newMux(
	redirectHandler http.Handler,
) *http.ServeMux {
	mux := http.NewServeMux()

	mux.Handle(
		"GET /{shortCode}",
		redirectHandler,
	)

	mux.HandleFunc(
		"GET /healthz",
		handler.Health,
	)

	return mux
}
