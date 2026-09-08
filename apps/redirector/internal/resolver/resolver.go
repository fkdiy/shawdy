package resolver

import (
	"context"

	"github.com/redis/go-redis/v9"
)

type Resolver struct {
	client *redis.Client
}

func New(client *redis.Client) *Resolver {
	return &Resolver{
		client: client,
	}
}

func (r *Resolver) Resolve(
	ctx context.Context,
	shortCode string,
) (string, error) {
	return r.client.Get(
		ctx,
		"redirect:"+shortCode,
	).Result()
}
