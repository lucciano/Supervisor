#!/usr/bin/env bash

. "{log_dir}/conf.sh"

# Space separated list of emails :
SUPERVISOR_MAIL_TO="abc@def.com ghi@jkl.com"
SUPERVISOR_MAIL_SUBJECT_PREFIX='[DW] '
SUPERVISOR_MAIL_MUTT_CMD="$ROOT_DIR/tests/resources/mutt.sh $LOG_DIR"
SUPERVISOR_MAIL_MUTT_CFG="set content_type=text/html"
SUPERVISOR_MAIL_SEND_ON_STARTUP=1
SUPERVISOR_MAIL_SEND_ON_SUCCESS=0
SUPERVISOR_MAIL_SEND_ON_WARNING=0
SUPERVISOR_MAIL_SEND_ON_ERROR=0
