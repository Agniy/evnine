file_name=$1
CURDIR=`pwd`
#echo CURDIR:${CURDIR}/${file_name}
SCRIPT_NAME=`echo ${CURDIR}/${file_name}`
SCRIPT_NAME_RU=`echo ${CURDIR}/${file_name} | sed s/\.js$/\.ru\.js/`
SCRIPT_NAME_EN=`echo ${CURDIR}/${file_name} | sed s/\.js$/\.en\.js/`
#echo SCRIPT_NAME_RU:$SCRIPT_NAME_RU
#echo SCRIPT_NAME_EN:$SCRIPT_NAME_EN
cat "$SCRIPT_NAME" | grep -v ^.*\s*en:\s*.* | sed s/ru:// > "$SCRIPT_NAME_RU"
cat "$SCRIPT_NAME" | grep -v ^.*\s*ru:\s*.* | sed s/en:// > "$SCRIPT_NAME_EN"
