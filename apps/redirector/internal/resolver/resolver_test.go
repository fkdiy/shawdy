package resolver

import (
	"context"
	"net"
	"testing"

	"github.com/fkdiy/shawdy/apps/redirector/internal/config"
	"github.com/redis/go-redis/v9"
)

func TestResolverReturnsStoredTargetURL(t *testing.T) {
	cfg := config.Load()

	client := redis.NewClient(&redis.Options{
		Addr: net.JoinHostPort(
			cfg.Redis.Host,
			cfg.Redis.Port,
		),
	})

	t.Cleanup(func() {
		client.Close()
	})

	ctx := context.Background()

	err := client.Set(
		ctx,
		"redirect:abc123",
		"https://example.com",
		0,
	).Err()

	if err != nil {
		t.Fatalf("failed to prepare Redis test data: %v", err)
	}

	t.Cleanup(func() {
		client.Del(ctx, "redirect:abc123")
	})

	resolver := New(client)

	targetURL, err := resolver.Resolve(ctx, "abc123")
	if err != nil {
		t.Fatalf("expected no error, got %v", err)
	}

	if targetURL != "https://example.com" {
		t.Fatalf(
			"expected target URL %q, got %q",
			"https://example.com",
			targetURL,
		)
	}
}
