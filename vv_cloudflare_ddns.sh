#!/usr/bin/env bash

# This script will lookup the ZONE_ID/REC_ID itself automatically. If you could provide, the process will be much faster.
# STEP1: Create an A record on Cloudflare with the subdomain you chose. Your TOKEN/Global_API is here: https://www.cloudflare.com/a/account/my-account
# STEP2: wget https://raw.githubusercontent.com/vvcares/others/master/vv_cloudflare_ddns.sh -O /sbin//sbin/vv_cloudflare_ddns.sh
# STEP3: chmod +x /sbin/vv_cloudflare_ddns.sh
# STEP4: RUN this file with task scheduler with parameters as below..
# STEP5: bash vv_cloudflare_ddns.sh -s EMAIL GLOBAL_API SUB_DOMAIN ROOT_DOMAIN_FQDN CF_PROXY_TRUE_FALSE

# IF JUST WANT TO GET Cloudflare RECORD_ID ?: bash vv_cloudflare_ddns.sh -l EMAIL Global_API SUB_domain ROOT_DOMAIN ZONE_ID

EMAIL=$2
TOKEN=$3
SUBDOMAIN=$4
DOMAIN=$5
PROXIED=$6
# TTL=1

# This script will find the ZONE_ID/REC_ID automatically.
ZONE_ID=''
REC_ID=''

set -euo pipefail
set -x # enable for debugging

VERBOSE="[ '${1:-}' != '-s' ]"
LOOKUP="[ '${1:-}' == '-l' ]"

API_URL="https://api.cloudflare.com/client/v4"
CURL="curl -s \
  -H Content-Type:application/json \
  -H X-Auth-Key:$TOKEN \
  -H X-Auth-Email:$EMAIL "

if [ -z "$ZONE_ID" ] || $LOOKUP; then
  ZONE_ID="$($CURL "$API_URL/zones?name=$DOMAIN" | sed -e 's/[{}]/\n/g' | grep '"name":"'"$DOMAIN"'"' | sed -e 's/,/\n/g' | grep '"id":"' | cut -d'"' -f4)"
  $VERBOSE && echo "ZONE_ID='$ZONE_ID'"
fi

if [ -z "$REC_ID" ] || $LOOKUP; then
  REC_ID="$($CURL "$API_URL/zones/$ZONE_ID/dns_records" | sed -e 's/[{}]/\n/g' | grep '"name":"'"$SUBDOMAIN"'.'"$DOMAIN"'"' | sed -e 's/,/\n/g' | grep '"id":"' | cut -d'"' -f4)"
  $VERBOSE && echo "REC_ID='$REC_ID'"
fi

$LOOKUP && exit 0

IP="$(curl -s http://ipv4.icanhazip.com)"
RECORD_IP="$($CURL "$API_URL/zones/$ZONE_ID/dns_records/$REC_ID" | sed -e 's/[{}]/\n/g' | sed -e 's/,/\n/g' | grep '"content":"' | cut -d'"' -f4)"

if [ "$IP" == "$RECORD_IP" ]; then
  $VERBOSE && echo "IP Unchanged"
  exit 0
fi

$VERBOSE && echo "Setting IP to $IP"

$CURL -X PUT "$API_URL/zones/$ZONE_ID/dns_records/$REC_ID" --data '{"type":"A","name":"'"$SUBDOMAIN"'","content":"'"$IP"'","proxied":'"$6"',"ttl":'"$7"'}' 1>/dev/null

exit 0
