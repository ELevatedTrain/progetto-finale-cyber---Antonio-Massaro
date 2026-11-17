#!/bin/bash

URL="http://external.user:8000/articles/search"
CONCURRENCY=200        # connessioni parallele (regola in base a CPU/RAM)
NUM_REQUESTS=5000
TIMEOUT=10             # evita curl appesi che consumano slot

# Payload unico grande (10MB dopo base64)
LARGE_PAYLOAD=$(head -c 100000 /dev/urandom | base64 -w0)

send_request() {
    # --data-urlencode è lento: meglio pre-encodare il payload una volta sola
    # e usare query string già pronta. '+' è la codifica URL-safe dello spazio.
    curl -s -o /dev/null -w "%{http_code} %{time_total}\n" \
         --max-time "$TIMEOUT" \
         --connect-timeout 5 \
         --compressed \
         -G "$URL" --data-urlencode "query=$1"
}
export -f send_request
export URL TIMEOUT

echo "Avvio: $NUM_REQUESTS richieste, $CONCURRENCY in parallelo..."

seq "$NUM_REQUESTS" | xargs -P "$CONCURRENCY" -I{} bash -c 'send_request "$LARGE_PAYLOAD"'

echo "Completato."
