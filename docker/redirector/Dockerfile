FROM golang:1.27.1-alpine3.23 AS development

WORKDIR /app

COPY go.mod go.sum ./
RUN go mod download

COPY . .

EXPOSE 8081

CMD ["go", "run", "./cmd/redirector"]


FROM golang:1.27.1-alpine3.23 AS builder

WORKDIR /app

COPY go.mod go.sum ./
RUN go mod download

COPY . .

RUN CGO_ENABLED=0 go build \
    -trimpath \
    -ldflags="-s -w" \
    -o /redirector \
    ./cmd/redirector


FROM alpine:3.23 AS production

# hadolint ignore=DL3018
RUN apk add --no-cache \
    ca-certificates \
    wget \
    && addgroup -S redirector \
    && adduser -S -D -H -G redirector redirector

COPY --from=builder /redirector /usr/local/bin/redirector

# hadolint ignore=DL3066
USER redirector

EXPOSE 8081

ENTRYPOINT ["/usr/local/bin/redirector"]
