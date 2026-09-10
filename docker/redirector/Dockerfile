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

RUN apk add --no-cache \
    ca-certificates=20260611-r0 \
    wget=1.25.0-r2

COPY --from=builder /redirector /usr/local/bin/redirector

EXPOSE 8081

ENTRYPOINT ["/usr/local/bin/redirector"]