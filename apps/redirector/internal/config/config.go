package config

import "os"

type Config struct {
	Redirector RedirectorConfig
	Redis      RedisConfig
}

type RedirectorConfig struct {
	Address string
}

type RedisConfig struct {
	Host string
	Port string
}

const (
	defaultRedirectorAddr = ":8081"
	defaultRedisHost      = "redis"
	defaultRedisPort      = "6379"
)

func Load() Config {
	cfg := Config{
		Redirector: RedirectorConfig{
			Address: defaultRedirectorAddr,
		},
		Redis: RedisConfig{
			Host: defaultRedisHost,
			Port: defaultRedisPort,
		},
	}

	if value := os.Getenv("REDIRECTOR_ADDR"); value != "" {
		cfg.Redirector.Address = value
	}

	if value := os.Getenv("REDIS_HOST"); value != "" {
		cfg.Redis.Host = value
	}

	if value := os.Getenv("REDIS_PORT"); value != "" {
		cfg.Redis.Port = value
	}

	return cfg
}
