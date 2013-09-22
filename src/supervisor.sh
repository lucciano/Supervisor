#!/bin/bash

# /bin/bash ~/eclipse-workspace-4.2/himedia-common/lib/common.db/supervisor/supervisor.sh deployment.php tests tests_gitexport v4.12.0
# /bin/bash /home/gaubry/supervisor/supervisor.sh --add deployment.php tests tests_gitexport v4.12.0
# find /home/gaubry/supervisor -type f -name "*sh" -exec chmod +x {} \;;  ~/deployment/supervisor.sh deployment.php project1 dev
# CRON : * * * * * /bin/bash /home/gaubry/supervisor/supervisor.sh --do-cron 1>/dev/null 2>>/home/gaubry/supervisor/logs/supervisor.error.log
# CRON : * * * * * /bin/bash /home/gaubry/supervisor/supervisor_monitoring.sh 1>/dev/null 2>>/home/gaubry/supervisor/logs/supervisor.error.log

# echo "mail_msg" | mutt -e "set content_type=text/html" -s "mail_subject" -- geoff.abury@gmail.com gaubry@hi-media.com

set -o nounset
set -o pipefail
shopt -s extglob

# Globales :
CONFIG_FILE="$(dirname $0)/../conf/supervisor.sh"
SCRIPT_NAME=''
SCRIPT_PARAMETERS=''
EXECUTION_ID=''
INSTIGATOR_EMAIL=''
SUPERVISOR_MAIL_ADD_ATTACHMENT=''

function getOpts () {
    local j=0
    local long_option=''

    for i in "$@"; do
        if [ ! -z "$long_option" ]; then
            i="$long_option=$i"
            long_option=''
        fi

        case $i in
            -c)
                long_option="--conf" ;;
            --conf=*)
                CONFIG_FILE=${i#*=}
                [ -f "$CONFIG_FILE" ] || die "Config file missing: '<b>$CONFIG_FILE</b>'"
                ;;

            --instigator-email=*)
                INSTIGATOR_EMAIL=${i#*=}
                ;;

            *)
                case $j in
                    0) SCRIPT_NAME="$i" ;;
                    1) SCRIPT_PARAMETERS="$i" ;;
                    *) ;;
                esac
                j=$(($j + 1))
                ;;
        esac
    done
}

function runDemand () {
    checkScriptCalled
    initScriptLogs
    initExecutionOfScript
    nb_warnings=0
    warning_messages=()
    executeScript
    displayResult
}

getOpts "$@"

# Includes:
. $(dirname $0)/../conf/supervisor-dist.sh
. $CONFIG_FILE
. $INC_DIR/common.sh

# Duplication du flux d'erreur :
exec 2> >(tee -a $SUPERVISOR_ERROR_LOG_FILE >&2)

#[ $# -eq 0 ] && displayHelp
EXECUTION_ID="$(date +'%Y%m%d%H%M%S')_$(printf '%05d' $RANDOM)"
runDemand
